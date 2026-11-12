<?php
require 'includes/config.php';
require 'includes/functions.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = false;
$data = ['full_name' => '', 'position' => '', 'department' => '', 'email' => '', 'phone' => '', 'login' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($data as $key => $value) {
        $data[$key] = trim($_POST[$key] ?? '');
    }
    $pass = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE login = ?');
    $stmt->execute([$data['login']]);
    $login_taken = $stmt->fetchColumn() > 0;

    if ($data['full_name'] == '' || $data['login'] == '' || $pass == '') {
        $error = 'Заполните ФИО, логин и пароль';
    } elseif (mb_strlen($data['login']) < 6) {
        $error = 'Логин должен быть не короче 6 символов';
    } elseif ($login_taken) {
        $error = 'Такой логин уже занят';
    } elseif (strlen($pass) < 6) {
        $error = 'Пароль должен быть не короче 6 символов';
    } elseif ($pass != $pass2) {
        $error = 'Пароли не совпадают';
    } else {
        $stmt = $pdo->prepare('INSERT INTO users (login, password, full_name, position, department, email, phone, role, is_approved)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)');
        $stmt->execute([$data['login'], password_hash($pass, PASSWORD_DEFAULT), $data['full_name'],
                        $data['position'], $data['department'], $data['email'], $data['phone'], 'employee']);
        $success = true;
    }
}

$page_title = 'Регистрация';
include 'includes/header.php';
?>
<div class="card form-box form-box-wide">
    <h1>Регистрация</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">Заявка на регистрацию отправлена. Вы сможете войти после одобрения администратором.</div>
        <a href="index.php" class="btn">На главную</a>
    <?php else: ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="full_name">ФИО</label>
            <input type="text" id="full_name" name="full_name" value="<?= e($data['full_name']) ?>" required>

            <div class="row">
                <div>
                    <label for="position">Должность</label>
                    <input type="text" id="position" name="position" value="<?= e($data['position']) ?>">
                </div>
                <div>
                    <label for="department">Отдел</label>
                    <input type="text" id="department" name="department" value="<?= e($data['department']) ?>">
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?= e($data['email']) ?>">
                </div>
                <div>
                    <label for="phone">Телефон</label>
                    <input type="text" id="phone" name="phone" value="<?= e($data['phone']) ?>">
                </div>
            </div>

            <label for="login">Логин (не менее 6 символов)</label>
            <input type="text" id="login" name="login" value="<?= e($data['login']) ?>" minlength="6" required>

            <div class="row">
                <div>
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" minlength="6" required>
                </div>
                <div>
                    <label for="password2">Повторите пароль</label>
                    <input type="password" id="password2" name="password2" minlength="6" required>
                </div>
            </div>

            <button type="submit" class="btn btn-wide">Зарегистрироваться</button>
        </form>
        <p class="center">Уже есть учётная запись? <a href="login.php">Войти</a></p>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
