<?php
require_once('helpers.php');
require_once('dbinit.php');
require_once('functions.php');

$lots_to_close = get_lots_to_close($con);

foreach ($lots_to_close as $lot) {
    $last_bid = get_last_bid($con, $lot['id']);
    $lot_winner = $last_bid['author_id'];

    $sql = 'UPDATE lots SET winner_id = ' . $lot_winner . ' WHERE id = ' . $lot['id'];
    $result = mysqli_query($con, $sql);
    if($result) {
        print_r('успех');
    }
}

