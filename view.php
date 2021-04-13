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
 * Prints an instance of mod_tresipuntvimeo.
 *
 * @package     mod_tresipuntvimeo
 * @copyright   2021 Tresipunt
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_tresipuntvimeo\output\view_page;

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

global $DB, $PAGE, $OUTPUT;

// Course_module ID, or
$id = optional_param('id', 0, PARAM_INT);

// ... module instance id.
$t  = optional_param('t', 0, PARAM_INT);

if ($id) {
    $cm             = get_coursemodule_from_id(
        'tresipuntvimeo', $id, 0, false, MUST_EXIST);
    $course         = $DB->get_record(
        'course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record(
        'tresipuntvimeo', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($t) {
    $moduleinstance = $DB->get_record(
        'tresipuntvimeo', array('id' => $t), '*', MUST_EXIST);
    $course         = $DB->get_record(
        'course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm             = get_coursemodule_from_instance(
        'tresipuntvimeo', $moduleinstance->id, $course->id, false, MUST_EXIST);
} else {
    print_error(get_string('missingidandcmid', 'mod_tresipuntvimeo'));
}

require_login($course, true, $cm);

$modulecontext = context_module::instance($cm->id);

$event = \mod_tresipuntvimeo\event\course_module_viewed::create(array(
    'objectid' => $moduleinstance->id,
    'context' => $modulecontext
));
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('tresipuntvimeo', $moduleinstance);
$event->trigger();

$PAGE->set_url('/mod/tresipuntvimeo/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

echo $OUTPUT->header();

$output = $PAGE->get_renderer('mod_tresipuntvimeo');
$page = new view_page($cm->id);
echo $output->render($page);

echo $OUTPUT->footer();
