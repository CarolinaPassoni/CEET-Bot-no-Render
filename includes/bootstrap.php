<?php

if (ob_get_level() === 0) {
    ob_start();
}

define('APP_ROOT', dirname(__DIR__));

$config = require APP_ROOT . '/config.php';

require_once APP_ROOT . '/includes/db.php';
require_once APP_ROOT . '/includes/functions.php';
