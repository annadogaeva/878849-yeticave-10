INSERT INTO categories SET NAME = 'Доски и лыжи', symbol_code = 'boards';
INSERT INTO categories SET NAME = 'Крепления', symbol_code = 'attachment';
INSERT INTO categories SET NAME = 'Ботинки', symbol_code = 'boots';
INSERT INTO categories SET name = 'Одежда', symbol_code = 'clothing';
INSERT INTO categories SET NAME = 'Инструменты', symbol_code = 'tools';
INSERT INTO categories SET name = 'Разное', symbol_code = 'other';

INSERT INTO lots SET name = '2014 Rossignol District Snowboard', description = 'нет описания',image = 'img/lot-1.jpg', start_price = 10999, end_date = '2019-08-23', bid_step = 100, author_id = 2, category_id = 1;
INSERT INTO lots SET name = 'DC Ply Mens 2016/2017 Snowboard', description = 'нет описания',image = 'img/lot-2.jpg', start_price = 159999, end_date = '2019-09-01', bid_step = 1000, author_id = 1, category_id = 1;
INSERT INTO lots SET name = 'Крепления Union Contact Pro 2015 года размер L/XL', description = 'нет описания',image = 'img/lot-3.jpg', start_price = 8000, end_date = '2019-08-24', bid_step = 100, author_id = 2, category_id = 2;
INSERT INTO lots SET name = 'Ботинки для сноуборда DC Mutiny Charocal', description = 'нет описания',image = 'img/lot-4.jpg', start_price = 10999, end_date = '2019-09-15', bid_step = 500, author_id = 3, category_id = 3;
INSERT INTO lots SET name = 'Куртка для сноуборда DC Mutiny Charocal', description = 'нет описания',image = 'img/lot-5.jpg', start_price = 7500, end_date = '2019-10-25', bid_step = 100, author_id = 2, category_id = 4;
INSERT INTO lots SET name = 'Маска Oakley Canopy', description = 'нет описания',image = 'img/lot-6.jpg', start_price = 5400, end_date = '2019-08-30', bid_step = 1000, author_id = 3, category_id = 6;

INSERT INTO bids SET sum = 12000, author_id = 2, lot_id = 1;
INSERT INTO bids SET sum = 11500, author_id = 1, lot_id = 4;

INSERT INTO users SET email = 'actorwriter400@yandex.ru', name = 'Худяков Дмитрий', password = 'coolerthanyou', contact_info = 'Санкт-Петербург, пр. Новоизмайловский, 16, корпус 6';
INSERT INTO users SET email = 'ne_dasha005@mail.ru', name = 'Суворова Маша', password = '1468ah', contact_info = 'г.Липецк, ул.Терешковой, 3/4, кв.56';

//получить все категории;
SELECT NAME FROM categories;

//получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;
SELECT l.NAME, l.start_price, l.image, c.name FROM lots l JOIN categories c ON l.category_id = c.id WHERE end_date > NOW() ORDER BY start_date DESC;

//показать лот по его id. Получите также название категории, к которой принадлежит лот;
SELECT l.NAME, l.id, c.name FROM lots l JOIN categories c ON l.category_id = c.id WHERE l.id = 4;

//обновить название лота по его идентификатору;
UPDATE lots SET NAME = 'Новое название' WHERE id = 2;

//получить список ставок для лота по его идентификатору с сортировкой по дате.
SELECT b.sum FROM bids b JOIN lots l ON b.lot_id=l.id WHERE l.id = 4 ORDER BY b.DATE DESC;


