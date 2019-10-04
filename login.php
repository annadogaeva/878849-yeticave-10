<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once('helpers.php');
require_once('init.php');
require_once('functions.php');

$categories = get_categories($con);

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

//если не авторизован, показать форму, если авторизован - показать ошибку
if (!$is_auth) {
    $page_content = include_template('loginpage.php');
} else {
    $page_content = show_error(403);
};

//если форма отправлена, то...
if(isset($_SERVER['REQUEST_METHOD'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST;

        //валидация формы
        $required = ['email', 'password'];
        $errors = [];

        foreach ($required as $key) {
            if (empty($_POST[$key])) {
                if ($key === 'email') {
                    $errors[$key] = 'Введите e-mail';
                } elseif ($key === 'password') {
                    $errors[$key] = 'Введите пароль';
                } else {
                    $errors[$key] = 'Это поле должно быть заполнено';
                };
            };
        };

        $email = isset($login['email']) ? mysqli_real_escape_string($con, $login['email']) : ''; //защищен
        $sql = "SELECT * 
                FROM users 
                WHERE email = '$email'";
        $res = mysqli_query($con, $sql);
        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

        if (!count($errors) and $user) {
            if(isset($login['password']) and isset($user['password'])) {
                if (password_verify($login['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                } else {
                    $errors['password'] = 'Вы ввели неверный пароль';
                };
            };
        } elseif (!isset($errors['email'])) {
            $errors['email'] = 'Такой пользователь не найден';
        };

        //если есть ошибки, показать ошибки
        if (count($errors)) {
            $page_content = include_template('loginpage.php',
                [
                    'errors' => $errors
                ]);
        } else { //если нет ошибок, переадресовать на главную
            header("Location: /");
            exit();
        };
    };
};

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Вход',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'navigation' => $navigation
]);

print($layout_content);

