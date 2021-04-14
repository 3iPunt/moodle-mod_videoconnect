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
use dml_exception;
use mod_tresipuntvimeo\uploads;

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
     */
    public function execute(): void {
        global $DB;
        mtrace("pasa por aki");
        // TODO: recuperar todas los uploads en NOT_EXECUTED
        $uploads = $DB->get_records(
            'tresipuntvimeo_uploads',
            [ 'status' => uploads::STATUS_NOT_EXECUTED ],
            'timecreated DESC', '*');



        var_dump($uploads);
    }
}
