<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'recycling_system');

define('SITE_NAME', 'EcoRecycle Hub');
define('SITE_URL', 'http://localhost/Recycling%20System');
define('BASE_PATH', dirname(__DIR__));

define('UPLOAD_PATH', BASE_PATH . '/assets/images/uploads/');
define('UPLOAD_URL', SITE_URL . '/assets/images/uploads/');

define('SESSION_TIMEOUT', 3600);

date_default_timezone_set('Asia/Riyadh');

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

$POINTS_CONFIG = [
    'plastic' => 10,
    'paper' => 8,
    'glass' => 12,
    'metal' => 15,
    'electronics' => 20,
    'organic' => 5,
    'cardboard' => 7
];

?>

