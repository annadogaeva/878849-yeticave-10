<?php
require_once('helpers.php');
require_once('dbinit.php');

$is_auth = rand(0, 1);

$user_name = 'Анна Догаева'; // укажите здесь ваше имя


/**
 * Форматирует цену с разделителями групп разрядов и добавляет знак валюты ₽
 *
 * @param number $num - изначальная цена
 * @return string
 */
function format_price($num) {
    ceil($num);
    $num = number_format($num, 0, '.', ' ');
    $num = $num . ' ₽';

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
}

$page_content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Главная',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);


?>

