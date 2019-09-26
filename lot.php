<?php
require_once('helpers.php');
require_once('dbinit.php');
require_once('functions.php');

$categories = get_categories($con);
$lot_info = get_lot_info($con);

if ($lot_info) {
    $bids_info = get_bid_info($con, $lot_info['id']);

    $page_content = include_template('lotpage.php', [
        'categories' => $categories,
        'lot_info' => $lot_info,
        'is_auth' => $is_auth,
        'bids' => $bids_info
    ]);
    $title = $lot_info['NAME'];
} else {
    http_response_code(404);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' and $is_auth) {
    $bid_post = $_POST;
    $bid_post['author_id'] = $_SESSION['user']['id'];
    $bid_post['lot_id'] = $lot_info['id'];

    $errors = [];

    $errors['cost'] = validate_bid('cost', $lot_info['start_price'], $lot_info['bid_step']);

    if (empty($_POST['cost'])) {
        $errors['cost'] = 'Введите сумму';
    }

    $errors = array_filter($errors);

    if (count($errors)) {
        $page_content = include_template('lotpage.php',
            [
                'categories' => $categories,
                'lot_info' => $lot_info,
                'is_auth' => $is_auth,
                'errors' => $errors,
                'bids' => $bids_info
            ]);
    } else {
        //подготавливаем выражение
        $sql = 'INSERT INTO bids (date, sum, author_id, lot_id) VALUES (NOW(), ?, ?, ?);';
        $stmt = db_get_prepare_stmt($con, $sql, $bid_post);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $sql = 'UPDATE lots SET start_price = ? WHERE id = ' . $lot_info['id'];
            $stmt = db_get_prepare_stmt($con, $sql, $_POST);
            $res = mysqli_stmt_execute($stmt);

            $lot_info = get_lot_info($con);
            $bids_info = get_bid_info($con, $lot_info['id']);

            if ($res) {
                $page_content = include_template('lotpage.php',
                    [
                        'categories' => $categories,
                        'lot_info' => $lot_info,
                        'is_auth' => $is_auth,
                        'bids' => $bids_info
                    ]);

                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            }
        }
    };
}

if (http_response_code() == 404) {
    $page_content = include_template('404.php', [
        'categories' => $categories
    ]);
    $title = 'Ошибка 404';
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
