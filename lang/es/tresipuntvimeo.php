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
 * Plugin strings are defined here.
 *
 * @package     mod_tresipuntvimeo
 * @category    string
 * @copyright   2021 Tresipunt
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Tresipunt Vimeo';
$string['pluginname'] = 'Tresipunt Vimeo';
$string['modulename'] = 'Tresipunt Vimeo';
$string['modulenameplural'] = 'Tresipunt Vimeos';
$string['tresipuntvimeoname'] = 'Nombre del módulo';
$string['tresipuntvimeoname_help'] = 'Seleccione un nombre para este recurso';
$string['selectvideo'] = 'Selecciona un vídeo';
$string['pluginadministration'] = 'Tresipunt Vimeo Administración';
$string['vimeoheading'] = 'Vimeo API configuración';
$string['vimeoheadingdesc'] = 'Rellene los siguientes campos con sus credenciales de Vimeo';
$string['client_id'] = 'Client ID';
$string['client_secret'] = 'Client Secret';
$string['access_token'] = 'Personal Access Token';
$string['is_authenticated'] = 'Utiliza autenticación';
$string['is_authenticated_desc'] = 'Seleccione esta opción si dispone de un personal access token';
$string['scopes'] = 'Scopes';
$string['scopes_desc'] = 'Alcances aceptados';
$string['scopes_not_exist'] = 'No existen scopes configurados en el plugin';
$string['missingidandcmid'] = 'No se encuentra el ID o el CMID en la URL';
$string['task_upload_videos'] = 'Tarea para subir vídeos a Vimeo';
$string['idvideo'] = 'ID del vídeo de Vimeo';
$string['idvideo_help'] = 'Ej: https://vimeo.com/<strong>536287845</strong>';
$string['we_are_sorry'] = 'Lo sentimos';
$string['filepath_not_found'] = 'No se ha seleccionado ningún vídeo para subir';
$string['not_executed'] = 'Este vídeo estará disponible para verlo dentro de poco';
$string['discarded'] = 'Este vídeo ha sido descartado por uno nuevo';
$string['uploading'] = 'Este vídeo estará disponible para verlo dentro de poco';
$string['error_uploading'] = 'Ha ocurrido un error al subir el vídeo a Vimeo';
$string['completed'] = 'Este vídeo estará disponible para verlo dentro de poco';
$string['deleted'] = 'Este course module ha sido borrado';
$string['id_video_missing'] = 'No se ha podido recuperar el ID del video en la respuesta de Vimeo';
$string['error_folder'] = 'Error al mover el video a la carpeta';
$string['error_whitelist'] = 'No se ha actualizado correctamente el whitelist de la privacidad';
$string['whitelist'] = 'Dominio Whitelist';
$string['whitelist_desc'] = "Dominio que queremos incluir a la lista blanca de privacidad. No incluir protocolo 'http://' or 'https://'. Ej: implika.test";
$string['folderid'] = 'Folder ID';
$string['folderid_desc'] = "Folder ID donde se alojará el video. Ej: https://vimeo.com/manage/folders/<strong>4206879</strong>";
