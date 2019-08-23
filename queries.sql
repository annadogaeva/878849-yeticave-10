USE yeticave;

INSERT INTO categories SET NAME = `Доски и лыжи`, symbol_code = `boards`;
INSERT INTO categories SET NAME = `Крепления`, symbol_code = `attachment`;
INSERT INTO categories SET NAME = `Ботинки`, symbol_code = `boots`;
INSERT INTO categories SET name = `Одежда`, symbol_code = `clothing`;
INSERT INTO categories SET NAME = `Инструменты`, symbol_code = `tools`;
INSERT INTO categories SET name = `Разное`, symbol_code = `other`;

INSERT INTO lots SET name = 2014 Rossignol District Snowboard, descriprion = нет описания,image = img/lot-1.jpg, start_price = 10999, end_date = 2019-08-23, bid_step = 100, author_id = 2, category_id = 1;
INSERT INTO lots SET name = DC Ply Mens 2016/2017 Snowboard, descriprion = нет описания,image = img/lot-2.jpg, start_price = 159999, end_date = 2019-09-01, bid_step = 1000, author_id = 1, category_id = 1;
INSERT INTO lots SET name = Крепления Union Contact Pro 2015 года размер L/XL, descriprion = нет описания,image = img/lot-3.jpg, start_price = 8000, end_date = 2019-08-24, bid_step = 100, author_id = 2, category_id = 2;
INSERT INTO lots SET name = Ботинки для сноуборда DC Mutiny Charocal, descriprion = нет описания,image = img/lot-4.jpg, start_price = 10999, end_date = 2019-09-15, bid_step = 500, author_id = 3, category_id = 3;
INSERT INTO lots SET name = Куртка для сноуборда DC Mutiny Charocal, descriprion = нет описания,image = img/lot-5.jpg, start_price = 7500, end_date = 2019-10-25, bid_step = 100, author_id = 2, category_id = 4;
INSERT INTO lots SET name = Маска Oakley Canopy, descriprion = нет описания,image = img/lot-6.jpg, start_price = 5400, end_date = 2019-08-30, bid_step = 1000, author_id = 3, category_id = 6;
SELECT l.author_id, winner, category_id FROM lots l JOIN users u, category c ON l.author_id = u.id, l.winner = i.id, l.category_id = c.id;

INSERT INTO bids SET sum = 12000, author_id = 2, lot_id = 1;
INSERT INTO bids SET sum = 11500, author_id = 1, lot_id = 4;
SELECT b.author_id, lot_id FROM users u, lots l ON b.author_id = u.id, b.lot_id = l.id;

INSERT INTO users SET email = actorwriter400@yandex.ru, name = Худяков Аким, password = coolerthanyou, contact_info = Санкт-Петербург, пр. Новоизмайловский, 16, корпус 6;
INSERT INTO users SET email = ne_dasha005@mail.ru, name = Суворова Маша, password = 1468ah, contact_info = г.Липецк, ул.Терешковой, 3/4, кв.56;
INSERT INTO users SET email = vladik_vlad@mail.ru, name = Владислав, password = q13dfAs!, contact_info = Ростов-на-Дону, ул.Орбитальная, 23, кв 55;

