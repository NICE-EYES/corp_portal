<?php
require 'includes/config.php';
require 'includes/functions.php';

$news = $pdo->query('SELECT news.*, users.full_name AS author
                     FROM news LEFT JOIN users ON news.author_id = users.id
                     ORDER BY news.created_at DESC')->fetchAll();

$page_title = 'Главная';
include 'includes/header.php';
?>
<?php if (is_logged_in()): ?>
    <div class="welcome">
        <h1>Добро пожаловать, <?= e($_SESSION['full_name']) ?>!</h1>
        <p>Ваша роль: <b><?= e($role_names[$_SESSION['role']]) ?></b>.</p>
    </div>
<?php else: ?>
    <div class="welcome">
        <h1>Корпоративный портал сотрудников</h1>
        <p>Новости компании, справочник сотрудников и электронные заявки в одном месте.</p>
        <div class="welcome-buttons">
            <a href="login.php" class="btn btn-white">Вход</a>
            <a href="register.php" class="btn btn-outline">Регистрация</a>
        </div>
    </div>
<?php endif; ?>

<h2>Новости компании</h2>
<?php if (count($news) == 0): ?>
    <p class="muted">Новостей пока нет.</p>
<?php endif; ?>

<?php foreach ($news as $item): ?>
    <div class="card news">
        <h3><?= e($item['title']) ?></h3>
        <p><?= nl2br(e($item['content'])) ?></p>
        <div class="muted small"><?= e($item['author']) ?> · <?= format_date($item['created_at']) ?></div>
    </div>
<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>
