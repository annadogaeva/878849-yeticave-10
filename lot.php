<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once('helpers.php');
require_once('init.php');
require_once('functions.php');

$categories = get_categories($con);
$lot_info = get_lot_info($con);

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

//если есть лот с таким id, то показать его
if ($lot_info) {
    $bids_info = get_bid_info($con, $lot_info['id']);
    $page_content = include_template('lotpage.php', [
        'lot_info' => $lot_info,
        'is_auth' => $is_auth,
        'bids' => $bids_info
    ]);
    $title = $lot_info['NAME'];
} else { //если нет, то показать ошибку 404
    $page_content = show_error(404);
};

//если отправлена форма ставки, то...
if ($_SERVER['REQUEST_METHOD'] === 'POST' and $is_auth) {
    $bid_post = $_POST;
    $bid_post['author_id'] = $_SESSION['user']['id'];
    $bid_post['lot_id'] = $lot_info['id'];

    //валидация ошибок
    $errors = [];
    $errors['cost'] = validate_bid('cost', $lot_info['start_price'], $lot_info['bid_step']);
    if (empty($_POST['cost'])) {
        $errors['cost'] = 'Введите сумму';
    };
    $errors = array_filter($errors);

    //если форма заполнена с ошибками, то...
    if (count($errors)) {
        $page_content = include_template('lotpage.php',
            [
                'lot_info' => $lot_info,
                'is_auth' => $is_auth,
                'errors' => $errors,
                'bids' => $bids_info
            ]);
    } else { //если ошибок нет, то добавить новую ставку и обновить цену лота

        $bid_added = insert_new_bid($con, $bid_post);

        if ($bid_added) {

            $price_updated = update_start_price($con, $lot_info['id']);

            if ($price_updated) {
                //если все обновилось, получаем новые данные и вставляем на страницу
                $lot_info = get_lot_info($con);
                $bids_info = get_bid_info($con, $lot_info['id']);

                $page_content = include_template('lotpage.php',
                    [
                        'lot_info' => $lot_info,
                        'is_auth' => $is_auth,
                        'bids' => $bids_info
                    ]);

            };
        };

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    };
};

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'title' => $title,
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
