<?php
require_once('helpers.php');
require_once('dbinit.php');
require_once('functions.php');

$categories = get_categories($con);


$page_content = include_template('loginpage.php', [
    'categories' => $categories
]);


//Если форма отправлена, то...
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST;

    //ВАЛИДАЦИЯ ФОРМЫ
    $required = ['email', 'password'];
    $errors = [];

    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            if($key == 'email') {
                $errors[$key] = 'Введите e-mail';
            } elseif ($key == 'password') {
                $errors[$key] = 'Введите пароль';
            } else {
                $errors[$key] = 'Это поле должно быть заполнено';
            }
        }
    }

    $email = mysqli_real_escape_string($con, $login['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) and $user) {
        if (password_verify($login['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Вы ввели неверный пароль';
        }
    } elseif (!$errors['email']) {
        $errors['email'] = 'Такой пользователь не найден';
    }


    if(count($errors)) {
        $page_content = include_template('loginpage.php',
            [
                'sign_up' => $sign_up,
                'errors' => $errors,
                'categories' => $categories
            ]);
    }

    if (!count($errors)) {
        header("Location: /");
        exit();
    }
} else {
    $page_content = include_template('loginpage.php', [
        'sign_up' => $sign_up,
        'errors' => $errors,
        'categories' => $categories
    ]);

    if (isset($_SESSION['user'])) {
        header("Location: /");
        exit();
    }
}


$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Вход',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
