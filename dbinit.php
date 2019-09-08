<?php
$con = mysqli_connect('localhost', 'root', '', 'yeticave');
if($con == false) {
    print("Ошибка:" . mysqli_connect_error());
}
mysqli_set_charset($con, 'utf8');



