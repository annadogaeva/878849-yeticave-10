<?php
require_once('helpers.php');
require_once('dbinit.php');
require_once('functions.php');


$categories = get_categories($con);
$cats_ids = array_column($categories, 'id');

if($is_auth) {
    $page_content = include_template('add-lot.php', [
        'categories' => $categories,
        'lots' => get_active_lots($con)
    ]);
} else {
    http_response_code(403);
}

if (http_response_code()== 403) {
    $page_content = include_template('error.php', [
        'categories' => $categories
    ]);
}

//Если форма отправлена, то...
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lot_post = $_POST;
    $lot_post['author_id'] = $_SESSION['user']['id'];

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


    //работа с файлом
    if (!empty($_FILES['image']['name'])) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $file_ext;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);


        if (($file_type !== 'image/jpeg') && ($file_type !== 'image/png')) {
            $errors['file'] = 'Загрузите картинку в формате JPG или PNG';

        } else {
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $lot_post['image'] = 'uploads/' . $filename;
        }

    } else {
        $errors['file'] = 'Вы не загрузили файл';
    }

    if (count($errors)) {
        $page_content = include_template('add-lot.php',
            [
            'lot' => $lot_post,
            'errors' => $errors,
            'categories' => $categories
            ]);
    } else {
        //подготавливаем выражение
        $sql = 'INSERT INTO lots (start_date, NAME, category_id, description, start_price, bid_step, end_date, author_id, image) VALUES
(NOW(), ?, ?, ?, ?, ?, ?, ?, ?);';
        $stmt = db_get_prepare_stmt($con, $sql, $lot_post);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            $lot_id = mysqli_insert_id($con);
            header("Location: lot.php?lot=" . $lot_id);
        }
    }
}


$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Добавление лота',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
?>
