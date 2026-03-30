<?php require_once __DIR__ . '/common.php'; pageHeader('Добрострой — Каталог', 'catalog'); global $products; ?>
<main class="content">
  <h1>КАТАЛОГ</h1>
  <p>Ниже показаны товары каталога. Данные хранятся в многомерном массиве PHP.</p>
  <div class="cards">
    <?php foreach ($products as $key => $product): ?>
      <div class="card" id="<?= h($key) ?>">
        <img src="<?= h($product['image']) ?>" alt="<?= h($product['name']) ?>">
        <div>
          <h3><?= h($product['name']) ?></h3>
          <p><b>Цена:</b> <?= h($product['price']) ?></p>
          <p><?= h($product['short']) ?></p>
          <a href="<?= h($product['page']) ?>">Открыть товар</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <hr class="hr">
  <p class="note">Поиск из задания 2 работает на странице <a href="search.php">search.php</a> и ищет товары по первой букве.</p>
</main>
<?php pageRight(); ?>
