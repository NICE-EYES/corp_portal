<?php
require 'includes/config.php';
require 'includes/functions.php';
check_login();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'] ?? '';
    $date_from = $_POST['date_from'] ?? '';
    $date_to = $_POST['date_to'] ?? '';
    $comment = trim($_POST['comment'] ?? '');

    if (!in_array($type, $request_types)) {
        $error = 'Выберите тип заявки';
    } elseif ($date_from != '' && $date_to != '' && $date_to < $date_from) {
        $error = 'Дата окончания не может быть раньше даты начала';
    } else {
        $stmt = $pdo->prepare('INSERT INTO requests (user_id, type, date_from, date_to, comment)
                               VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $_SESSION['user_id'],
            $type,
            $date_from ?: null,
            $date_to ?: null,
            $comment,
        ]);
        $message = 'Заявка отправлена на рассмотрение';
    }
}

$stmt = $pdo->prepare('SELECT * FROM requests WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$requests = $stmt->fetchAll();

$page_title = 'Мои заявки';
include 'includes/header.php';
?>
<h1>Мои заявки</h1>

<?php if ($message): ?><div class="alert alert-success"><?= e($message) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="columns">
    <div class="card">
        <h2>Новая заявка</h2>
        <form method="post">
            <label for="type">Тип заявки</label>
            <select id="type" name="type" required>
                <?php foreach ($request_types as $t): ?>
                    <option><?= e($t) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="date_from">Дата начала</label>
            <input type="date" id="date_from" name="date_from">

            <label for="date_to">Дата окончания</label>
            <input type="date" id="date_to" name="date_to">

            <label for="comment">Комментарий</label>
            <textarea id="comment" name="comment" rows="3"></textarea>

            <button type="submit" class="btn">Отправить</button>
        </form>
    </div>

    <div class="card">
        <h2>История заявок</h2>
        <table>
            <tr>
                <th>Тип</th>
                <th>Период</th>
                <th>Статус</th>
                <th>Подана</th>
            </tr>
            <?php foreach ($requests as $r): ?>
                <tr>
                    <td><?= e($r['type']) ?><div class="muted small"><?= e($r['comment']) ?></div></td>
                    <td><?= format_date($r['date_from']) ?> – <?= format_date($r['date_to']) ?></td>
                    <td><span class="status status-<?= e($r['status']) ?>"><?= e($status_names[$r['status']]) ?></span></td>
                    <td><?= format_date($r['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if (count($requests) == 0): ?>
            <p class="muted">Вы ещё не подавали заявок.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
