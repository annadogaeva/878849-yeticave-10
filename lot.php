<?php
require_once('helpers.php');
require_once('dbinit.php');
require_once('functions.php');

$categories = get_categories($con);
$lot_info = get_lot_info($con);

if ($lot_info) {
    $page_content = include_template('lotpage.php', [
        'categories' => $categories,
        'lot_info' => $lot_info,
        'is_auth' => $is_auth
    ]);
    $title = $lot_info['NAME'];
} else {
    http_response_code(404);
}

if (http_response_code()== 404) {
    $page_content = include_template('404.php', [
        'categories' => $categories
    ]);
    $title = 'Ошибка 404';
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
