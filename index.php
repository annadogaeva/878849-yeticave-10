<?php
require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = 'Анна Догаева'; // укажите здесь ваше имя

$categories = [
    'boards' => 'Доски и лыжи',
    'attachment' => 'Крепления',
    'boots' => 'Ботинки',
    'clothing' => 'Одежда',
    'tools' => 'Инструменты',
    'other' => 'Разное'
];

$lots = [
    [
        name => '2014 Rossignol District Snowboard',
        category => $categories['boards'],
        price => 10999,
        url =>  'img/lot-1.jpg',
        time => '2019-10-11'
    ],[
        name => 'DC Ply Mens 2016/2017 Snowboard',
        category => $categories['boards'],
        price => 159999,
        url =>  'img/lot-2.jpg',
        time => '2019-09-01'
    ],[
        name => 'Крепления Union Contact Pro 2015 года размер L/XL',
        category => $categories['attachment'],
        price => 8000,
        url =>  'img/lot-3.jpg',
        time => '2019-08-21'
    ],[
        name => 'Ботинки для сноуборда DC Mutiny Charocal',
        category => $categories['boots'],
        price => 10999,
        url =>  'img/lot-4.jpg',
        time => '2019-09-15'
    ],[
        name => 'Куртка для сноуборда DC Mutiny Charocal',
        category => $categories['clothing'],
        price => 7500,
        url =>  'img/lot-5.jpg',
        time => '2019-10-25'
    ],[
        name => 'Маска Oakley Canopy',
        category => $categories['other'],
        price => 5400,
        url =>  'img/lot-6.jpg',
        time => '2019-08-20'
    ]
];


function format_price($num) {
    ceil($num);
    $num = number_format($num, 0, '.', ' ');
    $num = $num . ' ₽';

    return $num;
};

function calculate_time($time) {
    $current_time = time();
    $future_time = htmlspecialchars(strtotime($time));

    if($future_time > $current_time) {
        $diff_hours = ($future_time - $current_time)/3600;
        $result_hours = str_pad(floor($diff_hours), 2, '0', STR_PAD_LEFT);
        $result_minutes = str_pad(floor(($diff_hours-$result_hours)*60), 2, '0', STR_PAD_LEFT);
    }

    $result = [$result_hours, $result_minutes];

    return $result;
};


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

