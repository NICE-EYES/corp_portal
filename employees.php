<?php
require 'includes/config.php';
require 'includes/functions.php';
check_login();

$search = trim($_GET['search'] ?? '');

if ($search != '') {
    $stmt = $pdo->prepare('SELECT * FROM users
                           WHERE is_approved = 1 AND (full_name LIKE ? OR position LIKE ? OR department LIKE ?)
                           ORDER BY department, full_name');
    $stmt->execute(["%$search%", "%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query('SELECT * FROM users WHERE is_approved = 1 ORDER BY department, full_name');
}
$employees = $stmt->fetchAll();

$page_title = 'Сотрудники';
include 'includes/header.php';
?>
<h1>Справочник сотрудников</h1>

<form method="get" class="search">
    <input type="text" name="search" placeholder="ФИО, должность или отдел" value="<?= e($search) ?>">
    <button type="submit" class="btn">Найти</button>
    <?php if ($search != ''): ?><a href="employees.php" class="btn btn-light">Сбросить</a><?php endif; ?>
</form>

<div class="card">
    <table>
        <tr>
            <th>ФИО</th>
            <th>Должность</th>
            <th>Отдел</th>
            <th>Телефон</th>
            <th>E-mail</th>
        </tr>
        <?php foreach ($employees as $emp): ?>
            <tr>
                <td><b><?= e($emp['full_name']) ?></b></td>
                <td><?= e($emp['position']) ?></td>
                <td><?= e($emp['department']) ?></td>
                <td><?= e($emp['phone']) ?></td>
                <td><?= e($emp['email']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if (count($employees) == 0): ?>
        <p class="muted">Сотрудники не найдены.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
