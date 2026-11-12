<?php
require '../includes/config.php';
require '../includes/functions.php';
check_role(['admin', 'manager']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];

    if ($status == 'approved' || $status == 'rejected') {
        $stmt = $pdo->prepare('UPDATE requests SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
    }
    header('Location: requests.php');
    exit;
}

$requests = $pdo->query("SELECT requests.*, users.full_name, users.department
                         FROM requests JOIN users ON requests.user_id = users.id
                         ORDER BY requests.status = 'new' DESC, requests.created_at DESC")->fetchAll();

$page_title = 'Обработка заявок';
include '../includes/header.php';
?>
<h1>Обработка заявок сотрудников</h1>

<div class="card">
    <table>
        <tr>
            <th>Сотрудник</th>
            <th>Заявка</th>
            <th>Период</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($requests as $r): ?>
            <tr>
                <td><b><?= e($r['full_name']) ?></b><div class="muted small"><?= e($r['department']) ?></div></td>
                <td><?= e($r['type']) ?><div class="muted small"><?= e($r['comment']) ?></div></td>
                <td><?= format_date($r['date_from']) ?> – <?= format_date($r['date_to']) ?></td>
                <td><span class="status status-<?= e($r['status']) ?>"><?= e($status_names[$r['status']]) ?></span></td>
                <td class="nowrap">
                    <?php if ($r['status'] == 'new'): ?>
                        <form method="post" class="inline">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <button name="status" value="approved" class="btn btn-small btn-green">Одобрить</button>
                            <button name="status" value="rejected" class="btn btn-small btn-red">Отклонить</button>
                        </form>
                    <?php else: ?>
                        <span class="muted small">Обработана</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
