<?php
require 'includes/config.php';
require 'includes/functions.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login'] ?? '');
    $pass = $_POST['password'] ?? '';

    if ($login == '' || $pass == '') {
        $error = 'Введите логин и пароль';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE login = ?');
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($pass, $user['password'])) {
            $error = 'Неверный логин или пароль';
        } elseif ($user['is_approved'] == 0) {
            $error = 'Ваша регистрация ещё не одобрена администратором';
        } else {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
            exit;
        }
    }
}

$page_title = 'Вход';
include 'includes/header.php';
?>
<div class="card form-box">
    <h1>Вход в портал</h1>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="login">Логин</label>
        <input type="text" id="login" name="login" value="<?= e($_POST['login'] ?? '') ?>" required>

        <label for="password">Пароль</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="btn btn-wide">Войти</button>
    </form>

    <p class="center">Нет учётной записи? <a href="register.php">Зарегистрироваться</a></p>
</div>
<?php include 'includes/footer.php'; ?>
