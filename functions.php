<?php
/**
 * Форматирует цену с разделителями групп разрядов и добавляет знак валюты ₽
 *
 * @param number $string|$num - изначальная цена
 * @param string $symbol - символ валюты
 * @return string
 */
function format_price($num, $symbol = ' ₽')
{
    ceil($num);
    $num = number_format($num, 0, '.', ' ');
    $num = $num . $symbol;

    return $num;
}

;

/**
 * Считает остаток времени в часах и минутах от текущей до будущей даты.
 * Возвращает массив, где 1 элемент - часы, 2 - минуты.
 *
 * @param string $time - будущая дата
 * @return array
 */
function calculate_remaining_time($time)
{
    $current_time = date_create('now');
    $future_time = date_create($time);

    if ($current_time < $future_time) {
        $interval = date_diff($current_time, $future_time);

        $days = date_interval_format($interval, '%a');
        $hours = date_interval_format($interval, '%h');
        $total_hours = sprintf('%02d', $days * 24 + $hours);
        $minutes = date_interval_format($interval, '%I');
        $seconds = date_interval_format($interval, '%S');
        $result = [
            'hours' => $total_hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'status' => 'active'
        ];
    } else {
        $result = [
            'hours' => '00',
            'minutes' => '00',
            'seconds' => '00',
            'status' => 'end'
        ];
    }

    return $result;
}

;

/**
 * Возвращает массив с открытыми лотами
 *
 * @param mysqli $con База данных
 * @return array
 */
function get_active_lots($con)
{
    $sql = 'SELECT l.NAME, l.start_price, l.image, l.end_date, l.id, c.name FROM lots l JOIN categories c ON l.category_id = c.id WHERE end_date > NOW() ORDER BY start_date DESC
';
    $result = mysqli_query($con, $sql);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $lots;
}

;

/**
 * Возвращает массив с категориями и симв. кодами
 *
 * @param mysqli $con База данных
 * @return array
 */
function get_categories($con)
{
    $sql = 'SELECT id, symbol_code, name FROM categories';
    $result = mysqli_query($con, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $categories;
}

;

/**
 * Возвращает массив с информацией о текущем лоте, ссгласно GET запросу
 *
 * @param mysqli $con База данных
 * @return array
 */
function get_lot_info($con) {
    if(!empty($_GET['lot'])) {
        $lot_id = mysqli_real_escape_string($con, $_GET['lot']);
        $sql = 'SELECT l.id, l.NAME, l.start_price, l.image, l.end_date, l.bid_step, l.description, c.name FROM lots l JOIN categories c ON l.category_id = c.id WHERE l.id = ' . $lot_id . '';
        $result = mysqli_query($con, $sql);
        $lot_info = mysqli_fetch_assoc($result);
        return $lot_info;
    }
        return null;
}

;

function get_category_info($con) {
    if(!empty($_GET['category'])) {
        $category_id = mysqli_real_escape_string($con, $_GET['category']);
        $sql = 'SELECT id, name FROM categories WHERE id = ' . $category_id;
        $result = mysqli_query($con, $sql);
        $category_info = mysqli_fetch_assoc($result);
        return $category_info;
    }
    return null;
}

/**
 * Производит валидацию категории при отправке формы
 *
 * @param string $name Полученное имя категории
 * @param array $allowed_list Разрешенный список категорий
 * @return string|null
 */
function validate_category($name, $allowed_list)
{
    $id = $_POST[$name];
    if (!in_array($id, $allowed_list)) {
        return 'Указана несуществующая категория';
    }
    return null;
}

;

/**
 * Производит валидацию цены при отправке формы
 *
 * @param string $name Полученное имя поля
 * @return string|null
 */
function validate_price($name)
{
    $price = $_POST[$name];
    if ($price <= 0 || !is_numeric($price)) {
        return "Содержимое поля «начальная цена» должно быть числом больше ноля.";
    }

    return null;
}

/**
 * Производит валидацию шага ставки
 *
 * @param string $name Полученное имя поля
 * @return string|null
 */
function validate_bid_step($name)
{
    $bid = $_POST[$name];
    if ($bid <= 0 || filter_var($name, FILTER_VALIDATE_INT) === true) {
        return "Содержимое поля «шаг ставки» должно быть целым числом больше ноля";
    }

    return null;
}

/**
 * Производит валидацию даты окончания торгов при отправке формы
 *
 * @param string $name Полученное имя поля
 * @return string|null
 */
function validate_end_date($name)
{
    $date = $_POST[$name];

    if (!date_create($date)) {
        return "Содержимое поля «дата завершения» должно быть датой в формате «ГГГГ-ММ-ДД»";
    } elseif (date_create($date) <= date_create('now')) {
        return "Указанная дата должна быть больше текущей хотя бы на один день";
    }

    return null;
}

/**
 * Сохраняет введенные ранее значения при валидации поля
 *
 * @param string $name Полученное имя поля
 * @return string
 */
function getPostVal($name)
{
    return $_POST[$name] ?? "";
}

/**
 * Возвращает массив с использованными email
 *
 * @param mysqli $con База данных
 * @return array
 */
function get_emails($con)
{
    $sql = 'SELECT email FROM users';
    $result = mysqli_query($con, $sql);
    $emails = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $emails = array_column($emails, 'email');

    return $emails;
}

;

/**
 * Производит проверку уникальности email адреса и соответствие формату email при регистрации нового пользователя
 *
 * @param string $name Полученное имя поля
 * @param array список email-адресов
 * @return string|null
 */
function validate_email($name, $list)
{
    $email = $_POST[$name];
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if (in_array($email, $list)) {
            return "Указанный email уже используется другим пользователем";
        }
    } else {
        return "Введите корректный e-mail адрес";
    }

    return null;
}

;

/**
 * Производит валидацию суммы ставки
 *
 * @param string $name Полученное имя поля
 * @param string $startprice Стартовая цена
 * @param string $minbid Минимальная ставка
 * @return string|null
 */
function validate_bid($name, $startprice, $minbid)
{
    $bid = $_POST[$name];
    if ($bid <= 0 || filter_var($name, FILTER_VALIDATE_INT) === true) {
        return 'Ставка должна быть целым числом больше ноля';
    } elseif ($bid < ($startprice + $minbid)) {
        return 'Ставка должна быть больше стартовой цены на сумму минимальной ставки';
    };
    return null;
}

/**
 * Получает список ставок для конкретного лота
 *
 * @param mysqli $con База данных
 * @param string $lot ID Лота
 * @return array
 */
function get_bid_info($con, $lot)
{
    $sql = 'SELECT b.id, b.DATE, b.SUM, u.name FROM bids b JOIN users u ON b.author_id = u.id WHERE lot_id =' . $lot . ' ORDER BY b.DATE DESC';
    $result = mysqli_query($con, $sql);
    $bids = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $bids;
}

;

/**
 * Преобразует дату в словесную форму
 *
 * @param string $time Дата
 * @return string
 */
function date_to_words($time)
{
    $current_time = date_create('now');
    $past_time = date_create($time);

    $interval = date_diff($current_time, $past_time);

    $days = date_interval_format($interval, '%a');
    $hours = date_interval_format($interval, '%h');
    $minutes = date_interval_format($interval, '%i');

    $hours_words = get_noun_plural_form($hours, 'час', 'часа', 'часов');
    $minutes_words = get_noun_plural_form($minutes, 'минуту', 'минуты', 'минут');

    if ($days) {
        $time_days = date_format($past_time, 'd.m.y');
        $time_hours = date_format($past_time, 'H:i');
        print_r($time_days . ' в ' . $time_hours);
    } elseif ($hours) {
        if ($hours === '1') {
            $time_string = 'Час назад';
        } else {
            $time_string = $hours . ' ' . $hours_words . ' назад';
        };
    } elseif (isset($minutes)) {
        if ($minutes === '0') {
            $time_string = 'Только что';
        } else {
            $time_string = $minutes . ' ' . $minutes_words . ' назад';
        };
    };

    return $time_string;
}

;

/**
 * Получает список ставок для текущего пользователя
 *
 * @param mysqli $con База данных
 * @return array
 */
function get_my_bids($con)
{
    $sql = 'SELECT l.image, l.name, c.NAME, l.end_date, b.SUM, b.DATE, b.author_id, l.id, l.start_price, l.author_id AS lot_author, u.contact_info AS lot_author_contact,l.winner_id FROM bids b JOIN lots l ON b.lot_id = l.id JOIN categories c ON l.category_id = c.id JOIN users u ON l.author_id = u.id WHERE b.author_id = ' . $_SESSION['user']['id'] . '  ORDER BY b.DATE DESC';
    $result = mysqli_query($con, $sql);
    $my_bids = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $my_bids;
}

;

/**
 * Получает список лотов, для которых необходимо определить победителя
 *
 * @param mysqli $con База данных
 * @return array
 */
function get_lots_to_close($con)
{
    $sql = 'SELECT id FROM lots WHERE end_date <= NOW() AND winner_id is NULL';
    $result = mysqli_query($con, $sql);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $lots;
}

/**
 * Получает победителя для лота
 *
 * @param mysqli $con База данных
 * @param string $lot ID Лота
 * @return array
 */
function get_lot_winner($con, $lot)
{
    $sql = 'SELECT b.author_id, u.email, u.name AS username, l.name, b.lot_id FROM bids b JOIN users u ON b.author_id = u.id JOIN lots l ON b.lot_id = l.id WHERE b.lot_id = ' . $lot . '  ORDER BY b.date DESC LIMIT 1';
    $result = mysqli_query($con, $sql);
    $winner = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $winner[0];
}

/**
 * Отправляет поздравительное письмо победителю
 *
 * @param array $user
 */
function send_email($user) {
    $username = $user['username'];
    $email = $user['email'];
    $title = $user['name'];
    $lot = $user['lot_id'];
    $my_bids = 'http://localhost/bids.php';

// Create the Transport
    $transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
        ->setUsername('keks@phpdemo.ru')
        ->setPassword('htmlacademy')
    ;

// Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);

    $email_template = include_template('email.php', [
            'username' => $username,
            'lot' => $lot,
            'title' => $title,
            'my_bids' => $my_bids
        ]);

// Create a message
    $message = (new Swift_Message('Ваша ставка победила'))
        ->setFrom(['keks@phpdemo.ru'])
        ->setTo([$email])
        ->setBody($email_template, 'text/html')
    ;

// Send the message
    $result = $mailer->send($message);
}

/**
 * Показывает ошибки в зависимости от номера
 *
 * @param int $error
 * @return array
 */
function show_error($error) {
    http_response_code($error);
    if($error === 404) {
        $page_content = include_template('404.php', [
            $title = 'Ошибка 404'
        ]);
    } else {
        $page_content = include_template('error.php', [
            $title = 'Ошибка ' . $error
        ]);
    }
    return $page_content;
};

/**
 * Получает количество лотов, соответствующих поисковому запросу
 *
 * @param mysqli $con База данных
 * @param string $search Поисковый запрос
 * @return array
 */
function get_lots_count_by_search($con, $search) {
//получаем кол-во лотов, соответствующих поисковому запросу
    $sql = 'SELECT COUNT(*) as cnt FROM lots WHERE MATCH(NAME, description) AGAINST(?)';
    $stmt = db_get_prepare_stmt($con, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items_count = mysqli_fetch_assoc($result)['cnt'];

    return $items_count;
}

/**
 * Получает количество лотов, соответствующих категории
 *
 * @param mysqli $con База данных
 * @param string $category id категории
 * @return array
 */
function get_lots_count_by_cat($con, $category) {
//получаем кол-во лотов, соответствующих поисковому запросу
    $sql = 'SELECT COUNT(*) as cnt FROM lots WHERE category_id LIKE ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$category]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items_count = mysqli_fetch_assoc($result)['cnt'];

    return $items_count;
}

/**
 * Получает количество лотов, соответствующих одной странице пагинации (запрос)
 *
 * @param mysqli $con База данных
 * @param string $search Поисковый запрос
 * @return array
 */
function get_lots_for_one_search_page($con, $page_items, $offset, $search) {
    $sql = 'SELECT l.NAME, l.start_price, l.image, l.end_date, l.id, c.name FROM lots l JOIN categories c ON l.category_id = c.id WHERE MATCH(l.NAME, l.description) AGAINST(?) LIMIT ' . $page_items . ' OFFSET ' . $offset;
    $stmt = db_get_prepare_stmt($con, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $lots;
}

/**
 * Получает количество лотов, соответствующих одной странице пагинации (по категории)
 *
 * @param mysqli $con База данных
 * @param string $category Поисковый запрос
 * @return array
 */
function get_lots_for_one_cat_page($con, $page_items, $offset, $category) {
    $sql = 'SELECT l.NAME, l.start_price, l.image, l.end_date, l.id, c.name FROM lots l JOIN categories c ON l.category_id = c.id WHERE l.category_id LIKE ? LIMIT ' . $page_items . ' OFFSET ' . $offset;
    $stmt = db_get_prepare_stmt($con, $sql, [$category]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $lots;
}

/**
 * Получает название категории по id
 *
 * @param mysqli $con База данных
 * @param string $id id категории
 * @return mysqli_result
 */
function get_category_by_id($con, $id) {
    $sql = 'SELECT name FROM categories WHERE id = ' . $id;
    $stmt = db_get_prepare_stmt($con, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $category_name = mysqli_fetch_assoc($result)['name'];

    return $category_name;
}

/**
 * Добавляет новую ставку
 *
 * @param mysqli $con База данных
 * @param array $bid_post Отправленная форма
 * @return mysqli_result
 */
function insert_new_bid($con, $bid_post) {
    $sql = 'INSERT INTO bids (date, sum, author_id, lot_id) VALUES (NOW(), ?, ?, ?);';
    $stmt = db_get_prepare_stmt($con, $sql, $bid_post);
    $res = mysqli_stmt_execute($stmt);

    return $res;

};

/**
 * Обновляет цену лота
 *
 * @param mysqli $con База данных
 * @param string $id id лота
 * @return mysqli_result
 */
function update_start_price($con, $id) {
    $sql = 'UPDATE lots SET start_price = ? WHERE id = ' . $id;
    $stmt = db_get_prepare_stmt($con, $sql, $_POST);
    $res = mysqli_stmt_execute($stmt);

    return $res;
}

/**
 * Добавляет новый лот
 *
 * @param mysqli $con База данных
 * @param array $bid_post Отправленная форма
 * @return mysqli_result
 */
function insert_new_lot($con, $lot_post) {
    $sql = 'INSERT INTO lots (start_date, NAME, category_id, description, start_price, bid_step, end_date, author_id, image) VALUES
(NOW(), ?, ?, ?, ?, ?, ?, ?, ?);';
    $stmt = db_get_prepare_stmt($con, $sql, $lot_post);
    $res = mysqli_stmt_execute($stmt);

    return $res;
};

/**
 * Добавляет нового пользователя
 *
 * @param mysqli $con База данных
 * @param array $sign_up Отправленная форма
 * @return mysqli_result
 */
function insert_new_user($con, $sign_up) {
    $sql = 'INSERT INTO users (register_date, email, password, name, contact_info) VALUES
(NOW(), ?, ?, ?, ?);';
    $stmt = db_get_prepare_stmt($con, $sql, $sign_up);
    $res = mysqli_stmt_execute($stmt);

    return $res;
}
