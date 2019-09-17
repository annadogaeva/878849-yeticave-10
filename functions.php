<?php
/**
 * Форматирует цену с разделителями групп разрядов и добавляет знак валюты ₽
 *
 * @param number $num - изначальная цена
 * @param string $symbol - символ валюты
 * @return string
 */
function format_price($num, $symbol = ' ₽') {
    ceil($num);
    $num = number_format($num, 0, '.', ' ');
    $num = $num . $symbol;

    return $num;
};

/**
 * Считает остаток времени в часах и минутах от текущей до будущей даты.
 * Возвращает массив, где 1 элемент - часы, 2 - минуты.
 *
 * @param string $time - будущая дата
 * @return array
 */
function calculate_remaining_time($time) {
    $current_time = date_create('now');
    $future_time = date_create($time);

    if ($current_time < $future_time) {
        $interval = date_diff($current_time, $future_time);

        $days = date_interval_format($interval, '%a');
        $hours = date_interval_format($interval, '%h');
        $total_hours = sprintf('%02d', $days*24 + $hours);
        $minutes = date_interval_format($interval, '%I');
        $result = [$total_hours, $minutes];
    } else {
        $result = ['00', '00'];
    }

    return $result;
};

/**
 * Возвращает массив с открытыми лотами
 *
 * @param mysqli $con База данных
 * @return array
 */
function get_active_lots($con) {
    $sql= 'SELECT l.NAME, l.start_price, l.image, l.end_date, l.id, c.name FROM lots l JOIN categories c ON l.category_id = c.id WHERE end_date > NOW() ORDER BY start_date DESC
';
    $result = mysqli_query($con, $sql);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $lots;
};

/**
 * Возвращает массив с категориями и симв. кодами
 *
 * @param mysqli $con База данных
 * @return array
 */
function get_categories($con) {
    $sql = 'SELECT id, symbol_code, name FROM categories';
    $result = mysqli_query($con, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $categories;
};

/**
 * Возвращает массив с информацией о текущем лоте, ссгласно GET запросу
 *
 * @param mysqli $con База данных
 * @return array
 */
function get_lot_info($con) {
    $get_lot = $_GET['lot'];
    if (isset($get_lot)) {
        $lot_id = mysqli_real_escape_string($con, $_GET['lot']);
        $sql = 'SELECT l.NAME, l.start_price, l.image, l.end_date, l.bid_step, l.description, c.name FROM lots l JOIN categories c ON l.category_id = c.id WHERE l.id = ' . $lot_id . '';
        $result = mysqli_query($con, $sql);
        $lot_info = mysqli_fetch_assoc($result);
        return $lot_info;
    }
};

/**
 * Производит валидацию категории при отправке формы
 *
 * @param string $name Полученное имя категории
 * @param string $allowed_list Разрешенный список категорий
 * @return string
 */
function validate_category($name, $allowed_list) {
    $id = $_POST[$name];
    if (!in_array($id, $allowed_list)) {
        return 'Указана несуществующая категория';
    }
    return null;
};

function validate_price($name) {
    $price = $_POST[$name];
    if ($price <= 0 || !is_numeric($price)) {
        return "Содержимое поля «начальная цена» должно быть числом больше ноля.";
    }

    return null;
}

function validate_bid_step($name) {
    $bid = $_POST[$name];
    if ($bid <= 0 || filter_var($name, FILTER_VALIDATE_INT) === true)
    {
        return "Содержимое поля «шаг ставки» должно быть целым числом больше ноля";
    }

    return null;
}

function validate_end_date($name) {
    $date = $_POST[$name];

    if(!date_create($date)) {
        return "Содержимое поля «дата завершения» должно быть датой в формате «ГГГГ-ММ-ДД»";
    } elseif (date_create($date) <= date_create('now')) {
        return "Указанная дата должна быть больше текущей хотя бы на один день";
    }

    return null;
}

function getPostVal($name) {
    return $_POST[$name] ?? "";
}
