<?php
require_once('helpers.php');
require_once('dbinit.php');
require_once('functions.php');

$categories = get_categories($con);

$my_bids = get_my_bids($con);


$page_content = include_template('mybids.php', [
    'categories' => $categories,
    'my_bids' => $my_bids
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Мои ставки',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
?>
