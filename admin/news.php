<?php
require '../includes/config.php';
require '../includes/functions.php';
check_role(['admin', 'manager']);

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_id'])) {
        $stmt = $pdo->prepare('DELETE FROM news WHERE id = ?');
        $stmt->execute([(int)$_POST['delete_id']]);
        header('Location: news.php');
        exit;
    }

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title == '' || $content == '') {
        $error = 'Заполните заголовок и текст новости';
    } else {
        $stmt = $pdo->prepare('INSERT INTO news (title, content, author_id) VALUES (?, ?, ?)');
        $stmt->execute([$title, $content, $_SESSION['user_id']]);
        header('Location: news.php');
        exit;
    }
}

$news = $pdo->query('SELECT news.*, users.full_name AS author
                     FROM news LEFT JOIN users ON news.author_id = users.id
                     ORDER BY news.created_at DESC')->fetchAll();

$page_title = 'Управление новостями';
include '../includes/header.php';
?>
<h1>Управление новостями</h1>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="columns">
    <div class="card">
        <h2>Добавить новость</h2>
        <form method="post">
            <label for="title">Заголовок</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Текст новости</label>
            <textarea id="content" name="content" rows="6" required></textarea>

            <button type="submit" class="btn">Опубликовать</button>
        </form>
    </div>

    <div class="card">
        <h2>Опубликованные новости</h2>
        <table>
            <tr>
                <th>Заголовок</th>
                <th>Автор</th>
                <th>Дата</th>
                <th></th>
            </tr>
            <?php foreach ($news as $item): ?>
                <tr>
                    <td><?= e($item['title']) ?></td>
                    <td><?= e($item['author']) ?></td>
                    <td><?= format_date($item['created_at']) ?></td>
                    <td>
                        <form method="post" class="inline" onsubmit="return confirm('Удалить новость?')">
                            <input type="hidden" name="delete_id" value="<?= $item['id'] ?>">
                            <button class="btn btn-small btn-red">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
