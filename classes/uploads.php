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
 * Uploads
 *
 * @package    mod_tresipuntvimeo
 * @copyright  2021 Tresipunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tresipuntvimeo;

use dml_exception;
use mod_tresipuntvimeo_mod_form;
use stdClass;

defined('MOODLE_INTERNAL') || die();


/**
 * Uploads
 *
 * @package    mod_tresipuntvimeo
 * @copyright  2021 Tresipunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class uploads {

    const STATUS_NOT_FILEPATH = 0;
    const STATUS_NOT_EXECUTED = 1;
    const STATUS_DISCARDED = 2;
    const STATUS_UPLOADING = 3;
    const STATUS_ERROR_UPLOADING = 4;
    const STATUS_COMPLETED = 5;
    const STATUS_DELETED = 6;
    const STATUS_UPLOADING_VIDEOID_MISSING = 7;
    const STATUS_UPLOADING_ERROR_WHITELIST = 8;

    const ERROR_MESSAGE = [
        'filepath_not_found',
        'not_executed',
        'discarded',
        'uploading',
        'error_uploading',
        'completed',
        'deleted',
        'id_video_missing',
        'error_whitelist',
    ];

    const CODE_NOT_FILEPATH = 10001;

    /**
     * Update.
     *
     * @param object $moduleinstance
     * @param mod_tresipuntvimeo_mod_form $mform
     * @return object
     * @throws dml_exception
     */
    static public function update(object $moduleinstance, mod_tresipuntvimeo_mod_form $mform): object {
        global $DB;

        if ($mform->get_data()) {
            $filepath = $mform->save_temp_file('filevimeo');

            if (!empty($filepath)) {

                $olds = $DB->get_records(
                    'tresipuntvimeo_uploads',
                    [ 'instance' => $moduleinstance->instance, 'status' => uploads::STATUS_NOT_EXECUTED ]);

                foreach ($olds as $old) {
                    $oldobject = new stdClass();
                    $oldobject->id = $old->id;
                    $oldobject->status = uploads::STATUS_DISCARDED;
                    $DB->update_record('tresipuntvimeo_uploads', $oldobject);
                }

                $moduleinstance->idvideo = '';

                $dataobject = new stdClass();
                $dataobject->instance = $moduleinstance->instance;
                $dataobject->filepath = $filepath;
                $dataobject->status = uploads::STATUS_NOT_EXECUTED;
                $dataobject->timecreated = time();
                $DB->insert_record('tresipuntvimeo_uploads', $dataobject);
            }  else {
                $dataobject = new stdClass();
                $dataobject->instance = $moduleinstance->instance;
                $dataobject->status = uploads::STATUS_NOT_FILEPATH;
                $dataobject->error_message = uploads::ERROR_MESSAGE[0];
                $dataobject->error_code = uploads::CODE_NOT_FILEPATH;
                $dataobject->timecreated = time();
                $DB->insert_record('tresipuntvimeo_uploads', $dataobject);
            }
        }

        return $moduleinstance;
    }

}