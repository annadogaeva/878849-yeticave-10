<?php
require_once('helpers.php');
require_once('dbinit.php');
require_once('functions.php');

$is_auth = rand(0, 1);

$user_name = 'Анна Догаева'; // укажите здесь ваше имя

$page_content = include_template('add-lot.php', [
    'categories' => get_categories($con),
    'lots' => get_active_lots($con)
]);


$categories = get_categories($con);
$cats_ids = array_column($categories, 'id');

//Если форма отправлена, то...
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lot_post = $_POST;

    //ВАЛИДАЦИЯ ФОРМЫ
    $required = ['name', 'category_id', 'description', 'start_price', 'bid_step', 'end_date'];
    $errors = [];


    $rules = [
        'category_id' => function() use ($cats_ids) {
            return validate_category('category_id', $cats_ids);
        },
        'start_price' => function() {
            return validate_price('start_price');
        },
        'bid_step' => function() {
            return validate_bid_step('bid_step');
        },
        'end_date' => function() {
            return validate_end_date('end_date');
        }
    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Заполните это поле';
        }
    }

    if (count($errors)) {
        $page_content = include_template('add-lot.php',
            [
            'lot' => $lot_post,
            'errors' => $errors,
            'categories' => $categories
            ]);
    } else {
        //работа с файлом
        $filename = uniqid() . '.jpg';
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $filename);
        $lot_post['image'] = 'uploads/' . $filename;

        //подготавливаем выражение
        $sql = 'INSERT INTO lots (start_date, NAME, category_id, description, start_price, bid_step, end_date, author_id, image) VALUES 
(NOW(), ?, ?, ?, ?, ?, ?, 1, ?);';
        $stmt = db_get_prepare_stmt($con, $sql, $lot_post);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            $lot_id = mysqli_insert_id($con);
            header("Location: lot.php?lot=" . $lot_id);
        }
    }


//    else {
//        $page_content = include_template('error.php', ['error' => mysqli_error($con)]);
//    }
}


$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Добавление лота',
    'categories' => get_categories($con),
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
?>