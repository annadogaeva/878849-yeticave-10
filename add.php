<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once('helpers.php');
require_once('init.php');
require_once('functions.php');

$categories = get_categories($con);
$cats_ids = array_column($categories, 'id');

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

//если пользователь авторизован, то показать форму
if ($is_auth) {
    $page_content = include_template('add-lot.php', [
        'categories' => $categories,
        'lots' => get_active_lots($con)
    ]);
} else { //если не авторизован, покаать ошибку
    $page_content = show_error(403);
};

//если форма отправлена, то...
if(isset($_SERVER['REQUEST_METHOD'])){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lot_post = $_POST;
        $lot_post['author_id'] = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '';

        //валидация формы
        $required = ['name', 'category_id', 'description', 'start_price', 'bid_step', 'end_date'];
        $errors = [];
        $rules = [
            'category_id' => function () use ($cats_ids) {
                return validate_category('category_id', $cats_ids);
            },
            'start_price' => function () {
                return validate_price('start_price');
            },
            'bid_step' => function () {
                return validate_bid_step('bid_step');
            },
            'end_date' => function () {
                return validate_end_date('end_date');
            }
        ];

        foreach ($_POST as $key => $value) {
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule();
            };
        };

        $errors = array_filter($errors);

        foreach ($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = 'Заполните это поле';
            };
        };

        //валидация файла
        if (!empty($_FILES['image']['name'])) {
            $tmp_name = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';

            $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $file_ext;
            $file_type = mime_content_type($tmp_name);

            if (($file_type !== 'image/jpeg') && ($file_type !== 'image/png')) {
                $errors['file'] = 'Загрузите картинку в формате JPG или PNG';

            } else {
                move_uploaded_file($tmp_name, 'uploads/' . $filename);
                $lot_post['image'] = 'uploads/' . $filename;
            };
        } else {
            $errors['file'] = 'Вы не загрузили файл';
        };

        //если при валидации возникли ошибки, показать их
        if (count($errors)) {
            $page_content = include_template('add-lot.php',
                [
                    'lot' => $lot_post,
                    'categories' => $categories,
                    'errors' => $errors
                ]);
        } else {         //если ошибок нет, добавить новый лот
            $res = insert_new_lot($con, $lot_post);
            if ($res) {
                $lot_id = mysqli_insert_id($con);
                header("Location: lot.php?lot=" . $lot_id);
            };
        };
    };
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'title' => 'Добавить лот',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
?>
