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
 * Class Vimeo
 *
 * @package    mod_tresipuntvimeo
 * @copyright  2021 Tresipunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tresipuntvimeo;
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/mod/tresipuntvimeo/vendor/autoload.php');

/**
 * Class Vimeo
 *
 * @package    mod_tresipuntvimeo
 * @copyright  2021 Tresipunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class vimeo {

    /** @var string Client ID */
    protected $client_id;

    /** @var string Client Secret */
    protected $client_secret;

    /** @var string[] Scope */
    protected $scope = array('public', 'private');

    /** @var \Vimeo\Vimeo Vimeo */
    protected $vimeo;

    /**
     * vimeo constructor.
     *
     */
    public function __construct() {
        $this->client_id = 'a2f14f01f0394e291636d372c167fce2b1c21433';
        $this->client_secret = 'SswrYmHvoW66TMuL41xD/GvyrBdRNBd6+cSK1KAFBidTfnHh0eUofj2sTbEOGCZ9tUgHe2r8sFWS/S4XYk+rUZuOJv3Jyo/svx12JIy9yJoOvOtLDqDGbyUV8vbzAbwV';
        $this->vimeo = new \Vimeo\Vimeo($this->client_id, $this->client_secret);
        $token = $this->vimeo->clientCredentials($this->scope);
        // usable access token
        var_dump($token['body']['access_token']);
        // accepted scopes
        var_dump($token['body']['scope']);
        // use the token
        $this->vimeo->setToken($token['body']['access_token']);
    }

    public function upload() {

    }

}