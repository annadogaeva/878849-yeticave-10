<?php

session_start();

define('CACHE_DIR', basename(__DIR__ . DIRECTORY_SEPARATOR . 'cache'));
define('UPLOAD_PATH', basename(__DIR__ . DIRECTORY_SEPARATOR . 'uploads'));

$user_name = '';
$is_auth = 0;

if (isset($_SESSION['user'])) {
    $is_auth = 1;
    $user_name = $_SESSION['user']['name'];
} else {
    $is_auth = 0;
}

$con = mysqli_connect('localhost', 'root', '', 'yeticave');

if ($con === false) {
    print("Ошибка:" . mysqli_connect_error());
}

mysqli_set_charset($con, 'utf8');



