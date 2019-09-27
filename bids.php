<?php

require_once('helpers.php');
require_once('init.php');
require_once('functions.php');

$categories = get_categories($con);
$my_bids = get_my_bids($con);

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

$page_content = include_template('mybids.php', [
    'my_bids' => $my_bids
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'title' => 'Мои ставки',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
?>
