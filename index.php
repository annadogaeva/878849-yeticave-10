<?php
require_once('helpers.php');
require_once('dbinit.php');
require_once('functions.php');

$categories = get_categories($con);

$page_content = include_template('main.php', [
    'categories' => $categories,
    'lots' => get_active_lots($con)
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Главная',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
?>

