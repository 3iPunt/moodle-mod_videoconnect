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

use curl;
use dml_exception;
use moodle_exception;
use moodle_url;
use Vimeo\Exceptions\VimeoRequestException;
use Vimeo\Exceptions\VimeoUploadException;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/lib/filelib.php');
require_once($CFG->dirroot . '/mod/tresipuntvimeo/vendor/autoload.php');

/**
 * Class Vimeo
 *
 * @package    mod_tresipuntvimeo
 * @copyright  2021 Tresipunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class vimeo {

    const TIMEOUT = 30;

    /** @var string Client ID */
    protected $client_id;

    /** @var string Client Secret */
    protected $client_secret;

    /** @var string Is authenticated? */
    protected $is_authenticated;

    /** @var string[] Scopes */
    protected $scopes = array('public', 'private', 'upload');

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
        $this->is_authenticated = get_config('mod_tresipuntvimeo', 'is_authenticated');
        $this->is_authenticated = get_config('mod_tresipuntvimeo', 'is_authenticated');
        $this->set_scopes();
        $this->init_vimeo();
    }

    /**
     * Set scopes.
     *
     * @throws dml_exception
     * @throws moodle_exception
     */
    protected function set_scopes() {
        $scopes = get_config('mod_tresipuntvimeo', 'scopes');
        if (!empty($scopes)) {
            $this->scopes = explode(',', $scopes);
        } else {
            $moodle_url = new moodle_url('/admin/settings.php?section=modsettingtresipuntvimeo');
            throw new moodle_exception(
                get_string('scopes_not_exist', 'mod_tresipuntvimeo') .
                '. ' . $moodle_url->out(false));
        }
    }

    /**
     * Init Vimeo
     *
     * @throws dml_exception
     * @throws moodle_exception
     */
    protected function init_vimeo() {
        if ($this->is_authenticated) {
            $this->access_token = get_config('mod_tresipuntvimeo', 'access_token');
            $this->vimeo = new \Vimeo\Vimeo($this->client_id, $this->client_secret, $this->access_token);
        } else {
            $this->vimeo = new \Vimeo\Vimeo($this->client_id, $this->client_secret);
            $token = $this->vimeo->clientCredentials($this->scopes);
            if (isset($token['body']['access_token'])) {
                $this->access_token = $token['body']['access_token'];
                $this->vimeo->setToken($this->access_token);
            } else {
                throw new moodle_exception($token["body"]["error_code"] . ': ' .$token["body"]["error"]);
            }
        }
    }

    /**
     * Upload Video to Vimeo.
     *
     * @param string $filepath
     * @param array $params https://developer.vimeo.com/api/reference/videos#upload_video
     * @return response
     */
    public function upload(string $filepath, array $params): response {
        try {
            $response = $this->vimeo->upload($filepath, $params);
            return new response(true, $response);
        } catch (VimeoRequestException $e) {
            return new response(false, null,
                new error(3001, $e->getMessage()));
        } catch (VimeoUploadException $e) {
            return new response(false, null,
                new error(3000, $e->getMessage()));
        }
    }

    /**
     * Add Domain whitelist
     *
     * @param int $video_id
     * @param string $domain
     * @return response
     */
    public function add_domain_whitelist(int $video_id, string $domain): response {
        $url = 'https://api.vimeo.com/videos/' . $video_id . '/privacy/domains/' . $domain;
        $params = [];
        return $this->curl_request($url, $params);
    }

    /**
     * Add Video to Folder
     *
     * @param int $video_id
     * @param int $folder_id
     * @return response
     */
    public function add_video_to_folder(int $video_id, int $folder_id): response {
        $url = 'https://api.vimeo.com/me/projects/' . $folder_id . '/videos/' . $video_id;
        $params = [];
        return $this->curl_request($url, $params);
    }

    /**
     * CURL Request.
     *
     * @param string $url
     * @param array $params
     * @return response
     */
    private function curl_request(string $url, $params = array()) {
        try {
            $curl = new curl();
            $headers = array();
            $headers[] = 'Content-type: application/json';
            $headers[] = 'Authorization: Bearer ' . $this->access_token;
            $curl->setHeader($headers);
            $result = $curl->put($url, json_encode($params), $this->get_options_curl());
            $result = json_decode($result, true);
            if ($result['error']) {
                return new response(false, null,
                    new error(4002,$result['error']));
            } else {
                return new response(true, '');
            }
        } catch (\Exception $e) {
            return new response(false, null,
                new error(4001, $e->getMessage()));
        }
    }

    /**
     * Get Options CURL.
     *
     * @return array
     */
    private function get_options_curl(): array {
        return [
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_TIMEOUT' => self::TIMEOUT,
            'CURLOPT_HTTP_VERSION' => CURL_HTTP_VERSION_1_1,
            'CURLOPT_SSLVERSION' => CURL_SSLVERSION_TLSv1_2,
            'CURLOPT_USERPWD' => "{$this->user}:{$this->token}",
        ];
    }

}