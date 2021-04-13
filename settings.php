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
 * Plugin administration pages are defined here.
 *
 * @package     mod_tresipuntvimeo
 * @copyright   2021 Tresipunt
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $ADMIN;

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_heading(
        'tresipuntcsvexport/csvsettings',
        get_string('vimeoheading', 'mod_tresipuntvimeo'),
        get_string('vimeoheadingdesc', 'mod_tresipuntvimeo')));

    $settings->add(new admin_setting_configtext(
        'mod_tresipuntvimeo/client_id',
        get_string('client_id', 'mod_tresipuntvimeo'),
        '', ''
    ));

    $settings->add(new admin_setting_configtext(
        'mod_tresipuntvimeo/client_secret',
        get_string('client_secret', 'mod_tresipuntvimeo'),
        '', ''
    ));

}