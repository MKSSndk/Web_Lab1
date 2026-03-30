<?php
require_once __DIR__ . '/common.php';
require_once __DIR__ . '/db.php';

$tab = $_GET['tab'] ?? 'login';
$authMessage = '';
$regMessage = '';
$authError = '';
$regError = '';
$dbError = '';

try {
    $mysqli = db();
} catch (Throwable $e) {
    $mysqli = null;
    $dbError = $e->getMessage() . ' Сначала импортируйте init_db.sql.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mysqli instanceof mysqli) {
    $mode = $_POST['mode'] ?? '';

    if ($mode === 'register') {
        $tab = 'reg';
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $login = trim((string)($_POST['login'] ?? ''));
        $password = trim((string)($_POST['password'] ?? ''));

        if ($name === '' || $login === '' || $password === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $regError = 'Заполните все поля регистрации корректно.';
        } else {
            $checkStmt = $mysqli->prepare('SELECT id FROM users WHERE login = ? LIMIT 1');
            $checkStmt->bind_param('s', $login);
            $checkStmt->execute();
            $exists = $checkStmt->get_result()->fetch_assoc();
            $checkStmt->close();

            if ($exists) {
                $regError = 'Пользователь с таким логином уже существует.';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $mysqli->prepare('INSERT INTO users (name, email, login, password_hash) VALUES (?, ?, ?, ?)');
                $stmt->bind_param('ssss', $name, $email, $login, $passwordHash);
                $stmt->execute();
                $stmt->close();
                $regMessage = 'Регистрация выполнена. Пользователь сохранён в MySQL.';
            }
        }
    }

    if ($mode === 'login') {
        $tab = 'login';
        $login = trim((string)($_POST['login'] ?? ''));
        $password = trim((string)($_POST['password'] ?? ''));

        $stmt = $mysqli->prepare('SELECT name, password_hash FROM users WHERE login = ? LIMIT 1');
        $stmt->bind_param('s', $login);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password_hash'])) {
            $authMessage = 'Авторизация успешна. Добро пожаловать, ' . $user['name'] . '!';
        } else {
            $authError = 'Неверный логин или пароль.';
        }
    }
}

pageHeader('Добрострой — Личный кабинет', 'auth');
?>
<main class="content">
  <h1>ЛИЧНЫЙ КАБИНЕТ</h1>
  <p>
    <a class="btn btn-link" href="auth.php?tab=login">Вход</a>
    <a class="btn btn-link" href="auth.php?tab=reg">Регистрация</a>
  </p>

  <?php if ($dbError): ?>
    <div class="alert alert-error"><?= h($dbError) ?></div>
  <?php endif; ?>

  <?php if ($tab === 'login'): ?>
    <h2>Авторизация</h2>
    <?php if ($authError): ?><div class="alert alert-error"><?= h($authError) ?></div><?php endif; ?>
    <?php if ($authMessage): ?><div class="alert alert-success"><?= h($authMessage) ?></div><?php endif; ?>
    <form class="form" method="post" action="auth.php?tab=login">
      <input type="hidden" name="mode" value="login">
      <label>Логин</label>
      <input required type="text" name="login" placeholder="Введите логин">
      <label>Пароль</label>
      <input required type="password" name="password" placeholder="Введите пароль">
      <div style="margin-top:10px"><button class="btn" type="submit">Войти</button></div>
    </form>
  <?php else: ?>
    <h2>Регистрация</h2>
    <?php if ($regError): ?><div class="alert alert-error"><?= h($regError) ?></div><?php endif; ?>
    <?php if ($regMessage): ?><div class="alert alert-success"><?= h($regMessage) ?></div><?php endif; ?>
    <form class="form" method="post" action="auth.php?tab=reg">
      <input type="hidden" name="mode" value="register">
      <div class="two">
        <div><label>Имя</label><input required type="text" name="name" placeholder="Иван"></div>
        <div><label>Email</label><input required type="email" name="email" placeholder="name@example.com"></div>
      </div>
      <label>Логин</label>
      <input required type="text" name="login" placeholder="Придумайте логин">
      <label>Пароль</label>
      <input required type="password" name="password" placeholder="Придумайте пароль">
      <div style="margin-top:10px"><button class="btn" type="submit">Зарегистрироваться</button></div>
    </form>
  <?php endif; ?>
</main>
<?php pageRight(); ?>
