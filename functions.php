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
 * Считает остаток времени от текущей до будущей даты
 *
 * @param string $time - изначальное время
 * @return array
 */
function calculate_remaining_time($time) {
    $current_time = date_create('now');
    $future_time = date_create($time);

    $interval = date_diff($current_time, $future_time);

    $days = date_interval_format($interval, '%a');
    $hours = date_interval_format($interval, '%H');
    $minutes = date_interval_format($interval, '%I');

    $result = [$days*24 + $hours, $minutes];
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
    $sql = 'SELECT symbol_code, name FROM categories';
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
    if (isset($_GET['lot'])) {
        $lot_id = $_GET['lot'];
        $sql = 'SELECT l.NAME, l.start_price, l.image, l.end_date, l.bid_step, l.description, c.name FROM lots l JOIN categories c ON l.category_id = c.id WHERE l.id = ' . $lot_id . '';
        $result = mysqli_query($con, $sql);
        $lot_info = mysqli_fetch_assoc($result);
        return $lot_info;
    }
};

/**
 * Возвращает id категории по ее имени
 *
 * @param mysqli $con База данных
 * @param string $name Имя категории
 * @return array
 */
function get_categoryid($con, $name) {
    $sql = 'SELECT id FROM categories WHERE NAME = "' . $name . '"';
    $result = mysqli_query($con, $sql);
    $id = mysqli_fetch_assoc($result);
    return $id['id'];
};