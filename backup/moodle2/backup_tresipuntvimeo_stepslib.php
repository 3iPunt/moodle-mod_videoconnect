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
 * Backup Activity Structure Step.
 *
 * @package    mod_tresipuntvimeo
 * @copyright  2024 Tresipunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the backup steps that will be used by the backup_tresipuntvimeo_activity_task
 */

/**
 * Define the complete tresipuntvimeo structure for backup, with file and id annotations
 */
class backup_tresipuntvimeo_activity_structure_step extends backup_activity_structure_step {

    /**
     * Define Structure.
     *
     * @return backup_nested_element
     * @throws base_element_struct_exception
     * @throws base_step_exception
     */
    protected function define_structure() {

        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $tresipuntvimeo = new backup_nested_element('tresipuntvimeo', array('id'), array(
            'course', 'name', 'idvideo', 'intro', 'introformat', 'timecreated', 'timemodified'));

        // Build the tree.
        // (love this).

        // Define sources.
        $tresipuntvimeo->set_source_table('tresipuntvimeo', array('id' => backup::VAR_ACTIVITYID));

        // Define id annotations.
        // (none).

        // Define file annotations.
        $tresipuntvimeo->annotate_files('mod_tresipuntvimeo', 'intro', null);
        // This file area hasn't itemid.

        // Return the root element (tresipuntvimeo), wrapped into standard activity structure.
        return $this->prepare_activity_structure($tresipuntvimeo);
    }
}
