<?php
require '../includes/config.php';
require '../includes/functions.php';
check_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    if ($id != $_SESSION['user_id']) {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
    }
    header('Location: users.php');
    exit;
}

$users = $pdo->query('SELECT * FROM users WHERE is_approved = 1 ORDER BY full_name')->fetchAll();

$page_title = 'Пользователи';
include '../includes/header.php';
?>
<div class="page-head">
    <h1>Пользователи</h1>
    <a href="user_form.php" class="btn">+ Добавить пользователя</a>
</div>

<div class="card">
    <table>
        <tr>
            <th>ФИО</th>
            <th>Логин</th>
            <th>Отдел</th>
            <th>Роль</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><b><?= e($u['full_name']) ?></b><div class="muted small"><?= e($u['position']) ?></div></td>
                <td><?= e($u['login']) ?></td>
                <td><?= e($u['department']) ?></td>
                <td><span class="role role-<?= e($u['role']) ?>"><?= e($role_names[$u['role']]) ?></span></td>
                <td class="nowrap">
                    <a href="user_form.php?id=<?= $u['id'] ?>" class="btn btn-small btn-light">Изменить</a>
                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                        <form method="post" class="inline" onsubmit="return confirm('Удалить пользователя?')">
                            <input type="hidden" name="delete_id" value="<?= $u['id'] ?>">
                            <button class="btn btn-small btn-red">Удалить</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
