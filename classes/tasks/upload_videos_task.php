<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Class upload_videos_task.
 *
 * @package     mod_tresipuntvimeo
 * @copyright   2021 Tresipunt
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tresipuntvimeo\tasks;

use coding_exception;
use core\task\scheduled_task;
use course_modinfo;
use dml_exception;
use mod_tresipuntvimeo\uploads;
use mod_tresipuntvimeo\vimeo;
use moodle_exception;
use stdClass;

defined('MOODLE_INTERNAL') || die();

/**
 * Class upload_videos_task
 *
 * @package     mod_tresipuntvimeo
 * @copyright   2021 Tresipunt
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class upload_videos_task extends scheduled_task {

    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     * @throws coding_exception
     */
    public function get_name(): string {
        return get_string('task_upload_videos', 'mod_tresipuntvimeo');
    }

    /**
     * Execute the task.
     *
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function execute(): void {
        global $DB;
        mtrace("***** INICIO");
        $uploads = $DB->get_records(
            'tresipuntvimeo_uploads',
            [ 'status' => uploads::STATUS_NOT_EXECUTED ],
            'timecreated DESC', '*');

        mtrace("Subir videos: " . count($uploads));
        $vimeo = new vimeo();
        foreach ($uploads as $upload) {

            list($course, $cm) = get_course_and_cm_from_instance($upload->instance, 'tresipuntvimeo');

            $filepath = $upload->filepath;

            $params = [
                'name' => $cm->name,
                'privacy' => [
                    'view' => 'nobody'
                ]
            ];

            $dataobject = new stdClass();
            $dataobject->id = $upload->id;
            $dataobject->status = uploads::STATUS_UPLOADING;
            $DB->update_record('tresipuntvimeo_uploads', $dataobject);
            mtrace("Subiendo: " . $cm->name);

            $response = $vimeo->upload($filepath, $params);
            mtrace("Subiendo: " . json_encode($response));

            if ($response->success) {
                $dataobject = new stdClass();
                $dataobject->id = $upload->id;
                $dataobject->http_response = $response->data;
                $dataobject->status = uploads::STATUS_COMPLETED;
                $dataobject->timeuploaded = time();
                $DB->update_record('tresipuntvimeo_uploads', $dataobject);
                $datamodule = new stdClass();
                $datamodule->id = $upload->instance;
                $datamodule->idvideo = str_replace('/videos/', '', $response->data);
                $datamodule->timemodified = time();
                $DB->update_record('tresipuntvimeo', $datamodule);
                mtrace("Subida completada: " . $cm->name);
            } else {
                $dataobject = new stdClass();
                $dataobject->id = $upload->id;
                $dataobject->status = uploads::STATUS_ERROR_UPLOADING;
                $dataobject->http_error_message = $response->error->message;
                $dataobject->http_error_code = $response->error->code;
                $DB->update_record('tresipuntvimeo_uploads', $dataobject);
                mtrace("Error en la subida: " . $cm->name);
            }
            rebuild_course_cache($course->id);
        }
        mtrace("***** FINAL");
    }
}
