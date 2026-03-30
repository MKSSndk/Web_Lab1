SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

DROP DATABASE IF EXISTS dobrostroy_db;
CREATE DATABASE dobrostroy_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE dobrostroy_db;

CREATE DATABASE IF NOT EXISTS dobrostroy_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dobrostroy_db;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL,
    login VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    topic VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    channels VARCHAR(255) NOT NULL,
    need_delivery TINYINT(1) NOT NULL DEFAULT 0,
    delivery_method VARCHAR(100) NOT NULL,
    review_text TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_key VARCHAR(120) NOT NULL UNIQUE,
    name VARCHAR(190) NOT NULL,
    first_letter VARCHAR(4) NOT NULL,
    price VARCHAR(50) NOT NULL,
    image VARCHAR(255) NOT NULL,
    page VARCHAR(255) NOT NULL,
    short_text TEXT NOT NULL,
    description_text TEXT NOT NULL,
    details_text TEXT NOT NULL
);

INSERT INTO products (product_key, name, first_letter, price, image, page, short_text, description_text, details_text)
VALUES
('shpaklevka-finish-20', 'Шпаклёвка финишная 20 кг', 'ш', '690 ₽', 'img/Shpack.webp', 'product1.php', 'Для подготовки стен и потолков под покраску и обои.', 'Сухая финишная шпаклёвка для выравнивания стен и потолков внутри помещений. Подходит под покраску и обои.', 'Материал легко наносится и шлифуется, позволяет получить гладкую поверхность. Рекомендуется наносить на подготовленное основание с грунтовкой.'),
('klei-plitochny-25', 'Клей плиточный 25 кг', 'к', '540 ₽', 'img/clay.webp', 'product2.php', 'Для укладки керамической плитки и керамогранита.', 'Клей для укладки керамической плитки на стены и пол. Подходит для стандартных оснований.', 'Обеспечивает надёжную фиксацию плитки. Рекомендуется соблюдать пропорции замеса и выдержку раствора перед нанесением.'),
('kraska-interier-10', 'Краска интерьерная белая 10 л', 'к', '1450 ₽', 'img/paint.webp', 'catalog.php#paint', 'Матовая краска для стен и потолков.', 'Подходит для сухих помещений, образует ровное белое покрытие.', 'Используется для финишной отделки стен и потолков после грунтования и шпаклевания.'),
('gruntovka-10', 'Грунтовка глубокого проникновения 10 л', 'г', '780 ₽', 'img/grunt.webp', 'catalog.php#primer', 'Для укрепления основания перед отделкой.', 'Снижает впитываемость поверхности и улучшает сцепление материалов.', 'Используется перед шпаклёвкой, штукатуркой и покраской.'),
('pena-vsesezonnaya', 'Пена монтажная всесезонная', 'п', '430 ₽', 'img/pena.webp', 'catalog.php#foam', 'Для монтажа и герметизации швов.', 'Подходит для установки окон, дверей и заполнения пустот.', 'Обладает хорошим расширением и удобна в бытовом применении.')
ON DUPLICATE KEY UPDATE
name = VALUES(name),
first_letter = VALUES(first_letter),
price = VALUES(price),
image = VALUES(image),
page = VALUES(page),
short_text = VALUES(short_text),
description_text = VALUES(description_text),
details_text = VALUES(details_text);
