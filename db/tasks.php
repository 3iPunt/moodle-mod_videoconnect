<?php

use mod_tresipuntvimeo\tasks\upload_videos_task;

defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        'classname' => upload_videos_task::class,
        'blocking' => 0,
        'minute' => '*',
        'hour' => '*',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
        'disabled' => 0
    ],
];
