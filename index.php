<?php
require_once('helpers.php');
require_once('dbinit.php');
require_once('functions.php');

$is_auth = rand(0, 1);

$user_name = 'Анна Догаева'; // укажите здесь ваше имя


$page_content = include_template('main.php', [
    'categories' => get_categories($con),
    'lots' => get_active_lots($con)
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Главная',
    'categories' => get_categories($con),
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
?>

