<?php
require '../includes/config.php';
require '../includes/functions.php';
check_role(['admin']);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';

$user = [
    'login' => '', 'full_name' => '', 'position' => '', 'department' => '',
    'email' => '', 'phone' => '', 'role' => 'employee',
];

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if (!$user) {
        die('Пользователь не найден');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user['login'] = trim($_POST['login']);
    $user['full_name'] = trim($_POST['full_name']);
    $user['position'] = trim($_POST['position']);
    $user['department'] = trim($_POST['department']);
    $user['email'] = trim($_POST['email']);
    $user['phone'] = trim($_POST['phone']);
    $user['role'] = $_POST['role'];
    $new_password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE login = ? AND id != ?');
    $stmt->execute([$user['login'], $id]);
    $login_taken = $stmt->fetchColumn() > 0;

    if ($user['login'] == '' || $user['full_name'] == '') {
        $error = 'Заполните логин и ФИО';
    } elseif (mb_strlen($user['login']) < 6) {
        $error = 'Логин должен быть не короче 6 символов';
    } elseif ($login_taken) {
        $error = 'Такой логин уже занят';
    } elseif (!isset($role_names[$user['role']])) {
        $error = 'Выберите роль';
    } elseif ($id == 0 && strlen($new_password) < 6) {
        $error = 'Пароль должен быть не короче 6 символов';
    } elseif ($id == $_SESSION['user_id'] && $user['role'] != 'admin') {
        $error = 'Нельзя снять роль администратора с самого себя';
    } else {
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE users SET login = ?, full_name = ?, position = ?, department = ?,
                                   email = ?, phone = ?, role = ? WHERE id = ?');
            $stmt->execute([$user['login'], $user['full_name'], $user['position'], $user['department'],
                            $user['email'], $user['phone'], $user['role'], $id]);
            if ($new_password != '') {
                $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
                $stmt->execute([password_hash($new_password, PASSWORD_DEFAULT), $id]);
            }
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (login, password, full_name, position, department, email, phone, role, is_approved)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)');
            $stmt->execute([$user['login'], password_hash($new_password, PASSWORD_DEFAULT), $user['full_name'],
                            $user['position'], $user['department'], $user['email'], $user['phone'], $user['role']]);
        }
        header('Location: users.php');
        exit;
    }
}

$page_title = $id ? 'Редактирование пользователя' : 'Новый пользователь';
include '../includes/header.php';
?>
<h1><?= $page_title ?></h1>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="card form-card">
    <form method="post">
        <label for="full_name">ФИО</label>
        <input type="text" id="full_name" name="full_name" value="<?= e($user['full_name']) ?>" required>

        <div class="row">
            <div>
                <label for="position">Должность</label>
                <input type="text" id="position" name="position" value="<?= e($user['position']) ?>">
            </div>
            <div>
                <label for="department">Отдел</label>
                <input type="text" id="department" name="department" value="<?= e($user['department']) ?>">
            </div>
        </div>

        <div class="row">
            <div>
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?= e($user['email']) ?>">
            </div>
            <div>
                <label for="phone">Телефон</label>
                <input type="text" id="phone" name="phone" value="<?= e($user['phone']) ?>">
            </div>
        </div>

        <div class="row">
            <div>
                <label for="login">Логин</label>
                <input type="text" id="login" name="login" value="<?= e($user['login']) ?>" minlength="6" required>
            </div>
            <div>
                <label for="role">Роль</label>
                <select id="role" name="role">
                    <?php foreach ($role_names as $key => $name): ?>
                        <option value="<?= $key ?>" <?= $user['role'] == $key ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <label for="password">Пароль <?= $id ? '(оставьте пустым, чтобы не менять)' : '' ?></label>
        <input type="password" id="password" name="password" <?= $id ? '' : 'required' ?>>

        <button type="submit" class="btn">Сохранить</button>
        <a href="users.php" class="btn btn-light">Отмена</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
