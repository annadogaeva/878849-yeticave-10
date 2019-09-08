<?php
$con = mysqli_connect('localhost', 'root', '', 'yeticave');
if($con == false) {
    print("Ошибка:" . mysqli_connect_error());
}
mysqli_set_charset($con, 'utf8');

//Получить лоты
$sql= 'SELECT l.NAME, l.start_price, l.image, l.end_date, c.name FROM lots l JOIN categories c ON l.category_id = c.id WHERE end_date > NOW() ORDER BY start_date DESC
';
$result = mysqli_query($con, $sql);
$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

//Получить категории
$sql = 'SELECT symbol_code, name FROM categories';
$result = mysqli_query($con, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
