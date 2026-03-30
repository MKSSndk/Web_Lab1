<?php
require_once __DIR__ . '/data.php';

function h($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function pageHeader(string $title, string $active = ''): void {
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= h($title) ?></title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<header class="header">
  <div class="container header__row">
    <div class="brand">
      <img src="img/logo.jpg" alt="Логотип Добрострой">
      <div class="brand__text">
        <b>Добрострой</b>
        <span>Строительно-отделочные материалы</span>
      </div>
    </div>

    <div class="title">ДОБРОСТРОЙ</div>

    <div class="authbox">
      <form action="auth.php" method="get">
        <input type="text" name="login" placeholder="Логин">
        <input type="password" name="pass" placeholder="Пароль">
        <div class="row">
          <button class="btn" type="submit">Войти</button>
          <a href="auth.php">Регистрация</a>
        </div>
      </form>
    </div>
  </div>
</header>

<div class="topbar">
  <div class="container topbar__row">
    <nav class="nav" aria-label="Главное меню сайта">
      <a class="<?= $active === 'index' ? 'is-active' : '' ?>" href="index.php">Главная</a>
      <a class="<?= $active === 'catalog' ? 'is-active' : '' ?>" href="catalog.php">Каталог</a>
      <a class="<?= $active === 'contacts' ? 'is-active' : '' ?>" href="contacts.php">Контакты</a>
      <a class="<?= $active === 'reviews' ? 'is-active' : '' ?>" href="reviews.php">Оставить отзыв</a>
      <a class="<?= $active === 'delivery' ? 'is-active' : '' ?>" href="delivery.php">Доставка</a>
      <a class="<?= $active === 'auth' ? 'is-active' : '' ?>" href="auth.php">Личный кабинет</a>
    </nav>

    <form class="search" action="search.php" method="post">
      <input type="search" name="search_q" maxlength="1" placeholder="Введите первую букву товара">
      <button class="btn" type="submit">Найти</button>
    </form>
  </div>
</div>
<section class="main">
  <div class="container grid">
    <aside class="side">
      <ul class="menu-list">
        <li><a href="index.php#about">О компании</a></li>
        <li><a href="catalog.php">Категории товаров</a></li>
        <li><a href="delivery.php">Доставка и оплата</a></li>
        <li><a href="reviews.php">Отзывы гостей</a></li>
        <li><a href="auth.php">Личный кабинет</a></li>
      </ul>
    </aside>
<?php
}

function pageRight(): void {
?>
    <aside class="right">
      <div class="banner">
        <img src="img/Perc.avif" alt="Акция недели">
        <a href="catalog.php">Акция недели</a>
      </div>
      <div class="banner">
        <img src="img/Shpack.webp" alt="Хит продаж: шпаклёвка">
        <a href="product1.php">Хит продаж: шпаклёвка</a>
      </div>
      <div class="banner">
        <img src="img/clay.webp" alt="Хит продаж: плиточный клей">
        <a href="product2.php">Хит продаж: плиточный клей</a>
      </div>
    </aside>
  </div>
</section>
<footer class="footer">
  <div class="container">© Все права защищены</div>
</footer>
</body>
</html>
<?php
}
