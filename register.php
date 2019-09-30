<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once('helpers.php');
require_once('init.php');
require_once('functions.php');

$categories = get_categories($con);
$emails = get_emails($con);

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

//если не авторизован, показать форму, если авторизован - показать ошибку
if (!$is_auth) {
    $page_content = include_template('registration.php');
} else {
    $page_content = show_error(403);
};

//если форма отправлена, то...
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sign_up = $_POST;

    //валидация формы
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
        };
    };

    $errors = array_filter($errors);

    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            if ($key === 'name') {
                $errors[$key] = 'Введите имя';
            } elseif ($key === 'email') {
                $errors[$key] = 'Введите e-mail';
            } elseif ($key === 'password') {
                $errors[$key] = 'Введите пароль';
            } elseif ($key === 'message') {
                $errors[$key] = 'Напишите как с вами связаться';
            } else {
                $errors[$key] = 'Заполните это поле';
            };
        };
    };

    //если есть ошибки, показать ошибки
    if (count($errors)) {
        $page_content = include_template('registration.php',
            [
                'sign_up' => $sign_up,
                'errors' => $errors
            ]);
    } else { //если нет ошибок, зарегистрировать пользователя
        $sign_up['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $registered = insert_new_user($con, $sign_up);

        //если пользователен зарегистрирован, перенаправить на страницу логина
        if ($registered) {
            header("Location: login.php");
        };
    };
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'title' => 'Регистрация',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
