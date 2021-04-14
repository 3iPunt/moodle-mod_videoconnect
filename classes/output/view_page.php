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
 * Block Tresipunt Support renderable
 *
 * @package    mod_tresipuntvimeo
 * @copyright  2021 Tresipunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tresipuntvimeo\output;
defined('MOODLE_INTERNAL') || die();

use dml_exception;
use renderable;
use renderer_base;
use stdClass;
use templatable;


/**
 * Main_content renderable class.
 *
 * @package    mod_tresipuntvimeo
 * @copyright  2021 Tresipunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class view_page implements renderable, templatable {

    /** @var stdClass Course Module */
    protected $cm;

    /** @var bool Has name? */
    protected $has_name;

    /**
     * view_page constructor.
     *
     * @param int $cmid
     * @param bool $has_name
     * @throws dml_exception
     */
    public function __construct(int $cmid, bool $has_name = true) {
        global $DB;
        $this->has_name = $has_name;
        $this->cm = $DB->get_record('course_modules', array( 'id'=> $cmid ));
    }


    /**
     * Export for Template.
     *
     * @param renderer_base $output
     * @return stdClass
     * @throws dml_exception
     */
    public function export_for_template(renderer_base $output): stdClass {
        global $DB;
        $vimeo_module = $DB->get_record('tresipuntvimeo', array('id'=>$this->cm->instance));
        $data = new stdClass();
        $data->name = $vimeo_module->name;
        $data->has_name = $this->has_name;
        $data->intro = $vimeo_module->intro;
        if (!empty($vimeo_module->src)) {
            $data->src = $vimeo_module->src;
            $data->width = '640';
            $data->height = '360';
            $data->has_vimeo = true;
        } else {
            $data->has_vimeo = false;
            $data->title = $vimeo_module->name;
        }
        return $data;
    }
}