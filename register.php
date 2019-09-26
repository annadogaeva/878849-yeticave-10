<?php
require_once('helpers.php');
require_once('dbinit.php');
require_once('functions.php');

$categories = get_categories($con);
$emails = get_emails($con);

if (!$is_auth) {
    $page_content = include_template('registration.php', [
        'categories' => $categories
    ]);
} else {
    http_response_code(403);
}

if (http_response_code() == 403) {
    $page_content = include_template('error.php', [
        'categories' => $categories
    ]);
}


//Если форма отправлена, то...
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sign_up = $_POST;
    $sign_up['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

    //ВАЛИДАЦИЯ ФОРМЫ
    $required = ['email', 'password', 'name', 'message'];
    $errors = [];

    $rules = [
        'email' => function () use ($emails) {
            return validate_email('email', $emails);
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
            if ($key == 'name') {
                $errors[$key] = 'Введите имя';
            } elseif ($key == 'email') {
                $errors[$key] = 'Введите e-mail';
            } elseif ($key == 'password') {
                $errors[$key] = 'Введите пароль';
            } elseif ($key == 'message') {
                $errors[$key] = 'Напишите как с вами связаться';
            } else {
                $errors[$key] = 'Заполните это поле';
            }
        }
    }

    if (count($errors)) {
        $page_content = include_template('registration.php',
            [
                'sign_up' => $sign_up,
                'errors' => $errors,
                'categories' => $categories
            ]);
    } else {

        //подготавливаем выражение
        $sql = 'INSERT INTO users (register_date, email, password, name, contact_info) VALUES
(NOW(), ?, ?, ?, ?);';
        $stmt = db_get_prepare_stmt($con, $sql, $sign_up);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            header("Location: login.php");
        }
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Регистрация',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
