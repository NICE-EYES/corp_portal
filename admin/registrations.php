<?php
require '../includes/config.php';
require '../includes/functions.php';
check_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];

    if ($_POST['action'] == 'approve') {
        $stmt = $pdo->prepare('UPDATE users SET is_approved = 1 WHERE id = ?');
        $stmt->execute([$id]);
    } elseif ($_POST['action'] == 'reject') {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ? AND is_approved = 0');
        $stmt->execute([$id]);
    }
    header('Location: registrations.php');
    exit;
}

$users = $pdo->query('SELECT * FROM users WHERE is_approved = 0 ORDER BY created_at')->fetchAll();

$page_title = 'Заявки на регистрацию';
include '../includes/header.php';
?>
<h1>Заявки на регистрацию</h1>

<div class="card">
    <?php if (count($users) == 0): ?>
        <p class="muted">Новых заявок на регистрацию нет.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ФИО</th>
                <th>Логин</th>
                <th>Должность и отдел</th>
                <th>Контакты</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><b><?= e($u['full_name']) ?></b></td>
                    <td><?= e($u['login']) ?></td>
                    <td><?= e($u['position']) ?><div class="muted small"><?= e($u['department']) ?></div></td>
                    <td><?= e($u['email']) ?><div class="muted small"><?= e($u['phone']) ?></div></td>
                    <td><?= format_date($u['created_at']) ?></td>
                    <td class="nowrap">
                        <form method="post" class="inline">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <button name="action" value="approve" class="btn btn-small btn-green">Одобрить</button>
                            <button name="action" value="reject" class="btn btn-small btn-red" onclick="return confirm('Отклонить заявку?')">Отклонить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
