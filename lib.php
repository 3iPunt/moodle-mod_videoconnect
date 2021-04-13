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

    return $id;
}

/**
 * Updates an instance of the mod_tresipuntvimeo in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param null $mform The form.
 * @return bool True if successful, false otherwise.
 * @throws dml_exception
 */
function tresipuntvimeo_update_instance(object $moduleinstance, $mform = null): bool {
    global $DB;

    // TODO: Upload file to Vimeo.



    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;
    $moduleinstance->src = 'https://player.vimeo.com/video/536287845?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479';

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

    $exists = $DB->get_record('tresipuntvimeo', array('id' => $id));
    if (!$exists) {
        return false;
    }

    $DB->delete_records('tresipuntvimeo', array('id' => $id));

    return true;
}
