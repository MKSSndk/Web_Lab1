<?php require_once __DIR__ . '/common.php'; global $products; $product = $products['shpaklevka-finish-20']; pageHeader('Добрострой — Шпаклёвка финишная', 'catalog'); ?>
<main class="content product-page">
  <h1><?= h($product['name']) ?></h1>
  <h2>Краткое описание товара</h2>
  <p class="product-short"><?= h($product['description']) ?></p>
  <p><a href="<?= h($product['image']) ?>" target="_blank"><img class="product-image" src="<?= h($product['image']) ?>" alt="<?= h($product['name']) ?>"></a></p>
  <h2>Характеристики</h2>
  <ul class="feature-list"><?php foreach ($product['features'] as $item): ?><li><?= h($item) ?></li><?php endforeach; ?></ul>
  <h2>Подробное описание</h2>
  <p class="product-full"><?= h($product['details']) ?></p>
</main>
<?php pageRight(); ?>
