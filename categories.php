<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once('helpers.php');
require_once('init.php');
require_once('functions.php');

$categories = get_categories($con);
$category_info = get_category_info($con);
$get_category = $_GET['category'] ?? '';

$lots = [];

if ($category_info) {
    $cur_page = $_GET['page'] ?? 1; //текущая страница
    $page_items = 9; //кол-во элементов на странице
    $category_name = get_category_by_id($con, $get_category); //получаем имя категории по id
    $items_count = get_lots_count_by_cat($con, $get_category); //кол-во лотов, соотв категории
    $pages_count = ceil($items_count / $page_items); //кол-во страниц пагинации
    $offset = ($cur_page - 1) * $page_items; //смещение
    $pages = range(1, $pages_count); //массив с номерами страниц
    $lots = get_lots_for_one_cat_page($con, $page_items, $offset, $get_category); //лоты для 1 страницы пагинации

    $page_content = include_template('all-lots.php', [
        'lots' => $lots,
        'get_category' => $get_category,
        'pages' => $pages,
        'pages_count' => $pages_count,
        'cur_page' => $cur_page,
        'category_name' => $category_name
    ]);

} else {
        $page_content = show_error(404);
};

$navigation = include_template('navigation.php', [
    'categories' => $categories,
    'category_name' => $category_name
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'title' => 'Категории',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'category_name' => $category_name
]);

print($layout_content);
