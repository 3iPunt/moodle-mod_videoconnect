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
 * Defines backup_tresipuntvimeo_activity_task class
 *
 * @package    mod_tresipuntvimeo
 * @copyright  2024 Tresipunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $CFG;

require_once($CFG->dirroot . '/mod/tresipuntvimeo/backup/moodle2/backup_tresipuntvimeo_stepslib.php');

/**
 * Provides the steps to perform one complete backup of the TresipuntVimeo instance
 */
class backup_tresipuntvimeo_activity_task extends backup_activity_task {
    /**
     * No specific settings for this activity
     */
    protected function define_my_settings() {
    }

    /**
     * Defines a backup step to store the instance data in the tresipuntvimeo.xml file
     */
    protected function define_my_steps() {
        $this->add_step(new backup_tresipuntvimeo_activity_structure_step(
            'tresipuntvimeo_structure',
            'tresipuntvimeo.xml'
        ));
    }

    /**
     * No content encoding needed for this activity
     *
     * @param string $content some HTML text that eventually contains URLs
     * to the activity instance scripts
     * @return string the same content with no changes
     */
    public static function encode_content_links($content) {
        return $content;
    }
}