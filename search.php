<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once('helpers.php');
require_once('init.php');
require_once('functions.php');

$categories = get_categories($con);

$lots = [];
$search = $_GET['search'] ?? '';
$search = trim($search);


if ($search) {
    $cur_page = $_GET['page'] ?? 1; //текущая страница
    $page_items = 9; //кол-во элементов на странице
    $items_count = get_lots_count_by_search($con, $search); //кол-во лотов, соотв запросу
    $pages_count = ceil($items_count / $page_items); //кол-во страниц пагинации
    $offset = ($cur_page - 1) * $page_items; //смещение
    $pages = range(1, $pages_count); //массив с номерами страниц
    $lots = get_lots_for_one_search_page($con, $page_items, $offset, $search); //лоты для 1 страницы пагинации
}

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

$page_content = include_template('searchpage.php', [
    'lots' => $lots,
    'search' => $search,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'title' => 'Поиск',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'search' => $search
]);

print($layout_content);
