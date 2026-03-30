<?php
require_once __DIR__ . '/common.php';
require_once __DIR__ . '/db.php';

$errors = [];
$success = '';
$dbError = '';
$reviews = [];
$old = [
    'name' => '',
    'email' => '',
    'rating' => '5',
    'agree' => '',
    'review' => '',
];

try {
    $mysqli = db();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($old as $key => $default) {
            $old[$key] = $_POST[$key] ?? $default;
        }

        $name = trim((string)$old['name']);
        $email = trim((string)$old['email']);
        $reviewText = trim((string)$old['review']);
        $rating = (int)($old['rating'] ?? 5);

        if ($name === '') {
            $errors[] = 'Введите имя.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email.';
        }
        if ($rating < 1 || $rating > 5) {
            $errors[] = 'Оценка должна быть от 1 до 5.';
        }
        if (mb_strlen($reviewText) < 10) {
            $errors[] = 'Отзыв должен содержать не менее 10 символов.';
        }
        if (empty($_POST['agree'])) {
            $errors[] = 'Нужно согласиться на обработку данных.';
        }

        if (!$errors) {
            $topic = 'Отзыв';
            $city = 'Не указан';
            $channels = 'email';
            $needDelivery = 0;
            $deliveryMethod = 'Самовывоз';

            $stmt = $mysqli->prepare('INSERT INTO reviews (name, email, rating, topic, city, channels, need_delivery, delivery_method, review_text) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('ssisssiss', $name, $email, $rating, $topic, $city, $channels, $needDelivery, $deliveryMethod, $reviewText);
            $stmt->execute();
            $stmt->close();

            $success = 'Спасибо! Отзыв сохранён в MySQL.';
            $old['review'] = '';
        }
    }

    $result = $mysqli->query('SELECT name, rating, topic, review_text, DATE_FORMAT(created_at, "%d.%m.%Y %H:%i") AS created_at FROM reviews ORDER BY id DESC');
    if ($result instanceof mysqli_result) {
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        $result->free();
    }
} catch (Throwable $e) {
    $dbError = $e->getMessage() . ' Сначала импортируйте init_db.sql.';
}

pageHeader('Добрострой — Оставить отзыв', 'reviews');
?>
<main class="content">
  <h1>ОСТАВИТЬ ОТЗЫВ</h1>
  <h2>Форма отзыва</h2>
  <?php if ($dbError): ?><div class="alert alert-error"><?= h($dbError) ?></div><?php endif; ?>
  <?php if ($errors): ?><div class="alert alert-error"><b>Исправьте ошибки:</b><ul><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
  <?php if ($success): ?><div class="alert alert-success"><?= h($success) ?></div><?php endif; ?>

  <form class="form" action="reviews.php" method="post">
    <div class="two">
      <div>
        <label>Имя</label>
        <input name="name" required type="text" value="<?= h($old['name']) ?>" placeholder="Введите имя">
      </div>
      <div>
        <label>Email</label>
        <input name="email" required type="email" value="<?= h($old['email']) ?>" placeholder="name@example.com">
      </div>
    </div>

    <label>Оценка магазина (radio)</label>
    <div class="option-row">
      <?php foreach ([5, 4, 3, 2, 1] as $rate): ?>
        <label><input type="radio" name="rating" value="<?= $rate ?>" <?= (string)$old['rating'] === (string)$rate ? 'checked' : '' ?>> <?= $rate ?></label>
      <?php endforeach; ?>
    </div>

    <label>Ваш отзыв (многострочное поле)</label>
    <textarea name="review" required placeholder="Напишите отзыв не короче 10 символов"><?= h($old['review']) ?></textarea>

    <label class="checkbox-line"><input type="checkbox" name="agree" value="1" <?= !empty($old['agree']) ? 'checked' : '' ?>> Согласен(на) на обработку данных</label>

    <div style="margin-top:10px"><button class="btn" type="submit">Отправить отзыв</button></div>
  </form>

  <hr class="hr">
  <h2>Отзывы пользователей</h2>
  <?php if (!$reviews): ?>
    <p class="note">Пока отзывов нет. Станьте первым!</p>
  <?php else: ?>
    <div class="reviews">
      <?php foreach ($reviews as $review): ?>
        <article class="review-card">
          <h3><?= h($review['name']) ?> <span><?= h($review['created_at']) ?></span></h3>
          <p><b>Тема:</b> <?= h($review['topic']) ?> · <b>Оценка:</b> <?= h($review['rating']) ?>/5</p>
          <p><?= nl2br(h($review['review_text'])) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>
<?php pageRight(); ?>
