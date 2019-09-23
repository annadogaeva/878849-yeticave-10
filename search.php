<?php
require_once('helpers.php');
require_once('dbinit.php');
require_once('functions.php');

$categories = get_categories($con);

$lots = [];
$search = $_GET['search'] ?? '';
$search = trim($search);

if($search) {
    $cur_page = $_GET['page'] ?? 1;
    $page_items = 9;

    //получаем кол-во лотов, соответствующих поисковому запросу
    $sql = 'SELECT COUNT(*) as cnt FROM lots WHERE MATCH(NAME, description) AGAINST(?)';
    $stmt = db_get_prepare_stmt($con, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items_count = mysqli_fetch_assoc($result)['cnt'];

    $pages_count = ceil($items_count / $page_items); //кол-во страниц пагинации
    $offset = ($cur_page - 1) * $page_items; //смещение

    $pages = range(1, $pages_count); //массив с номерами страниц

    //получаем информации по лотам для вывода постранично
    $sql = 'SELECT l.NAME, l.start_price, l.image, l.end_date, l.id, c.name FROM lots l JOIN categories c ON l.category_id = c.id WHERE MATCH(l.NAME, l.description) AGAINST(?) LIMIT ' . $page_items . ' OFFSET ' . $offset;
    $stmt = db_get_prepare_stmt($con, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$page_content = include_template('searchpage.php', [
    'categories' => $categories,
    'lots' => $lots,
    'search' => $search,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Поиск',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'search' => $search
]);

print($layout_content);
