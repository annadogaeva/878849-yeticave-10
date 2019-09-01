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


//Если форма отправлена, то...
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot_post = $_POST;

    //ВАЛИДАЦИЯ ФОРМЫ
    $required = ['category_id', 'image', 'start_price', 'bid_step', 'end_date'];
    $errors = [];

    $cats_ids = array_column(get_categories($con), 'id');

//    $rules = [
//        'category_id' => function() use ($cats_ids) {
//            return validateCategory('category_id', $cats_ids);
//        },
//        'start_ptice' => function() {
//            return
//        },
//        'bid_step' => function() {
//            return
//        },
//        'end_date' => function() {
//            return
//        },
//        'image' => function() {
//            return
//        }
//    ];

    //ОТПРАВКА ФОРМЫ
    //работа с файлом
    $filename = uniqid() . '.jpg';
    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $filename);
    $lot_post['image'] = 'uploads/' . $filename;

    //получаем id категории
    $category_id = get_categoryid($con, $lot_post['category_id']);
    $lot_post['category_id'] = $category_id;

    //подготавливаем выражение
    $sql = 'INSERT INTO lots (start_date, NAME, category_id, description, start_price, bid_step, end_date, author_id, image) VALUES 
(NOW(), ?, ?, ?, ?, ?, ?, 1, ?);';
    $stmt = db_get_prepare_stmt($con, $sql, $lot_post);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        $lot_id = mysqli_insert_id($con);
        header("Location: lot.php?lot=" . $lot_id);
    }
    else {
        $page_content = include_template('error.php', ['error' => mysqli_error($con)]);
    }
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