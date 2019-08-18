<?php
require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = 'Анна Догаева'; // укажите здесь ваше имя

$categories = [
    [
        name => 'Доски и лыжи',
        name_eng => 'boards'
    ],[
        name => 'Крепления',
        name_eng => 'attachment'
    ],[
        name => 'Ботинки',
        name_eng => 'boots'
    ],[
        name => 'Одежда',
        name_eng => 'clothing'
    ],[
        name => 'Инструменты',
        name_eng => 'tools'
    ],[
        name => 'Разное',
        name_eng => 'other'
    ]
];

$lots = [
    [
        name => '2014 Rossignol District Snowboard',
        category => $categories[0]['name'],
        price => 10999,
        url =>  'img/lot-1.jpg'
    ],[
        name => 'DC Ply Mens 2016/2017 Snowboard',
        category => $categories[0]['name'],
        price => 159999,
        url =>  'img/lot-2.jpg'
    ],[
        name => 'Крепления Union Contact Pro 2015 года размер L/XL',
        category => $categories[1]['name'],
        price => 8000,
        url =>  'img/lot-3.jpg'
    ],[
        name => 'Ботинки для сноуборда DC Mutiny Charocal',
        category => $categories[2]['name'],
        price => 10999,
        url =>  'img/lot-4.jpg'
    ],[
        name => 'Куртка для сноуборда DC Mutiny Charocal',
        category => $categories[3]['name'],
        price => 7500,
        url =>  'img/lot-5.jpg'
    ],[
        name => 'Маска Oakley Canopy',
        category => $categories[5]['name'],
        price => 5400,
        url =>  'img/lot-6.jpg'
    ]
];


function format_price($num) {
    ceil($num);
    if($num >= 1000) {
        $num = number_format($num, 0, '.', ' ');
    }
    $num = $num . " " . "₽";
    return $num;
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

