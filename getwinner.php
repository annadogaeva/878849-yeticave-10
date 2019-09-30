<?php

require_once('helpers.php');
require_once('init.php');
require_once('functions.php');
require_once('vendor/autoload.php');

$lots_to_close = get_lots_to_close($con);

if ($lots_to_close) {
    foreach ($lots_to_close as $lot) {
        $winner_info = get_lot_winner($con, $lot['id']);
        $lot_id = mysqli_real_escape_string($con, $lot['id']); //защищен
        $sql = 'UPDATE lots SET winner_id = ' . $winner_info['author_id'] . ' WHERE id = ' . $lot['id'];
        $result = mysqli_query($con, $sql);
        if ($result) {
            send_email($winner_info);
        }
    }
}

