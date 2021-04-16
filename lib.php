<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Library of interface functions and constants.
 *
 * @package     mod_tresipuntvimeo
 * @copyright   2021 Tresipunt
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_tresipuntvimeo\output\view_page;
use mod_tresipuntvimeo\uploads;

defined('MOODLE_INTERNAL') || die();

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return int True if the feature is supported, null otherwise.
 */
function tresipuntvimeo_supports(string $feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_MOD_ARCHETYPE:
            return MOD_ARCHETYPE_RESOURCE;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_tresipuntvimeo into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param null $mform The form.
 * @return int The id of the newly inserted record.
 * @throws dml_exception
 */
function tresipuntvimeo_add_instance(object $moduleinstance, $mform = null): int {
    global $DB;
    $moduleinstance->timecreated = time();
    $id = $DB->insert_record('tresipuntvimeo', $moduleinstance);
    $moduleinstance->instance = $id;
    uploads::update($moduleinstance, $mform);
    return $id;
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 * See get_array_of_activities() in course/lib.php
 *
 * @param object $coursemodule
 * @return cached_cm_info|null
 * @throws coding_exception
 * @throws moodle_exception
 */
function tresipuntvimeo_get_coursemodule_info(object $coursemodule): cached_cm_info {
    global $PAGE;
    $PAGE->set_context(context_module::instance($coursemodule->id));
    $output = $PAGE->get_renderer('mod_tresipuntvimeo');
    $page = new view_page($coursemodule->id, false);
    $content = $output->render($page);
    $info = new cached_cm_info();
    $info->content = $content;
    return $info;
}

/**
 * Updates an instance of the mod_tresipuntvimeo in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance
 * @param mod_tresipuntvimeo_mod_form|null $mform
 * @return bool
 * @throws dml_exception
 * @throws moodle_exception
 */
function tresipuntvimeo_update_instance(object $moduleinstance, mod_tresipuntvimeo_mod_form $mform = null): bool {
    global $DB;
    $moduleinstance = uploads::update($moduleinstance, $mform);
    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;
    return $DB->update_record('tresipuntvimeo', $moduleinstance);
}

/**
 * Removes an instance of the mod_tresipuntvimeo from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 * @throws dml_exception
 */
function tresipuntvimeo_delete_instance(int $id): bool {
    global $DB;

    $sql = "UPDATE {tresipuntvimeo_uploads}
            SET status = :newstatus  
            WHERE instance = :instance AND status = :statuscurrent";

    $params = array(
        'newstatus' => uploads::STATUS_DELETED,
        'instance' => $id,
        'statuscurrent' => uploads::STATUS_NOT_EXECUTED);
    $DB->execute($sql, $params);

    $exists = $DB->get_record('tresipuntvimeo', array('id' => $id));
    if (!$exists) {
        return false;
    }
    $DB->delete_records('tresipuntvimeo', array('id' => $id));
    return true;
}


