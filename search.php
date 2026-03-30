<?php
require_once __DIR__ . '/common.php';
require_once __DIR__ . '/db.php';

$letter = '';
$error = '';
$matches = [];
$dbError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $letter = mb_strtolower(trim((string)($_POST['search_q'] ?? '')));

    if ($letter === '' || mb_strlen($letter) !== 1 || !preg_match('/^[а-яёa-z]$/iu', $letter)) {
        $error = 'Введите ровно одну букву.';
    } else {
        try {
            $mysqli = db();
            $stmt = $mysqli->prepare('SELECT name, price, image, page, description_text, details_text FROM products WHERE first_letter = ? ORDER BY name');
            $stmt->bind_param('s', $letter);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $matches[] = [
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'image' => $row['image'],
                    'page' => $row['page'],
                    'description' => $row['description_text'],
                    'details' => $row['details_text'],
                ];
            }

            $stmt->close();

            if (!$matches) {
                $error = 'По указанной букве товаров не найдено.';
            }
        } catch (Throwable $e) {
            $dbError = $e->getMessage() . ' Сначала импортируйте init_db.sql.';
        }
    }
}

pageHeader('Добрострой — Поиск товара', 'catalog');
?>
<main class="content">
  <h1>ПОИСК ПО ПЕРВОЙ БУКВЕ</h1>
  <form class="form search-form" method="post" action="search.php">
    <label>Введите первую букву названия товара или услуги</label>
    <input type="search" name="search_q" maxlength="1" value="<?= h($letter) ?>" placeholder="Например: ш">
    <div style="margin-top:10px"><button class="btn" type="submit">Поиск</button></div>
  </form>

  <?php if ($dbError): ?><div class="alert alert-error"><?= h($dbError) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert alert-error"><?= h($error) ?></div><?php endif; ?>

  <?php if ($matches): ?>
    <h2>Найденные товары</h2>
    <div class="cards">
      <?php foreach ($matches as $product): ?>
        <div class="card">
          <img src="<?= h($product['image']) ?>" alt="<?= h($product['name']) ?>">
          <div>
            <h3><?= h($product['name']) ?></h3>
            <p><b>Цена:</b> <?= h($product['price']) ?></p>
            <p><?= h($product['description']) ?></p>
            <p><?= h($product['details']) ?></p>
            <a href="<?= h($product['page']) ?>">Перейти к карточке</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>
<?php pageRight(); ?>
