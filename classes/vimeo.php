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
use dml_exception;
use moodle_exception;
use Vimeo\Exceptions\VimeoRequestException;
use Vimeo\Exceptions\VimeoUploadException;

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

    /** @var string Access Token */
    protected $access_token;

    /**
     * vimeo constructor.
     *
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function __construct() {
        $this->client_id = get_config('mod_tresipuntvimeo', 'client_id');
        $this->client_secret = get_config('mod_tresipuntvimeo', 'client_secret');
        $this->init_vimeo();
    }

    /**
     * Init Vimeo
     * @throws moodle_exception
     */
    protected function init_vimeo() {
        $this->vimeo = new \Vimeo\Vimeo($this->client_id, $this->client_secret);
        $token = $this->vimeo->clientCredentials($this->scope);
        if (isset($token['body']['access_token'])) {
            $this->access_token = $token['body']['access_token'];
            $this->vimeo->setToken($this->access_token);
        } else {
            throw new moodle_exception($token["body"]["error_code"] . ': ' .$token["body"]["error"]);
        }
    }

    /**
     * Upload Video to Vimeo.
     *
     * @param string $filepath
     * @param array $params
     * @return response
     */
    public function upload(string $filepath, array $params): response {
        try {
            $response = $this->vimeo->upload($filepath, $params);
            var_dump($response);
            return new response(true, json_decode($response));
        } catch (VimeoRequestException $e) {
            var_dump($e);
            return new response(false, null,
                new error(3001, $e->getMessage()));
        } catch (VimeoUploadException $e) {
            var_dump($e->getMessage());
            return new response(false, null,
                new error(3000, $e->getMessage()));
        }
    }

}