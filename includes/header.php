<?php
$folder = basename(dirname($_SERVER['PHP_SELF']));
$current = ($folder == 'admin' ? 'admin/' : '') . basename($_SERVER['PHP_SELF']);

function menu_link($file, $title)
{
    global $base, $current;
    $active = ($file == $current) ? 'active' : '';
    echo '<a href="' . $base . $file . '" class="' . $active . '">' . $title . '</a>';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?> — Портал сотрудников</title>
    <link rel="stylesheet" href="<?= $base ?>style/style.css">
</head>
<body>
<header class="header">
    <div class="container header-top">
        <a href="<?= $base ?>index.php" class="logo"><span class="logo-icon">НТ</span>Портал сотрудников ООО «НоваТех»</a>
        <?php if (is_logged_in()): ?>
            <div class="user-box">
                <span class="user-name"><?= e($_SESSION['full_name']) ?></span>
                <span class="role role-<?= e($_SESSION['role']) ?>"><?= e($role_names[$_SESSION['role']]) ?></span>
                <a href="<?= $base ?>logout.php" class="logout">Выйти</a>
            </div>
        <?php else: ?>
            <div class="user-box">
                <a href="<?= $base ?>login.php" class="logout">Вход</a>
                <a href="<?= $base ?>register.php" class="btn btn-white">Регистрация</a>
            </div>
        <?php endif; ?>
    </div>
    <?php if (is_logged_in()): ?>
    <nav class="menu-bar">
        <div class="container menu">
            <?php
            menu_link('index.php', 'Главная');
            menu_link('employees.php', 'Сотрудники');
            menu_link('requests.php', 'Мои заявки');
            if (has_role(['admin', 'manager'])) {
                menu_link('admin/requests.php', 'Обработка заявок');
                menu_link('admin/news.php', 'Новости');
            }
            if (has_role(['admin'])) {
                menu_link('admin/registrations.php', 'Регистрации');
                menu_link('admin/users.php', 'Пользователи');
            }
            ?>
        </div>
    </nav>
    <?php endif; ?>
</header>
<main class="container content">
