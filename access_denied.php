<?php
require 'includes/config.php';
require 'includes/functions.php';

http_response_code(403);
$page_title = 'Доступ запрещён';
include 'includes/header.php';
?>
<div class="card center">
    <h1>Доступ запрещён</h1>
    <p>У вашей роли нет прав для просмотра этой страницы.</p>
    <a href="index.php" class="btn">На главную</a>
</div>
<?php include 'includes/footer.php'; ?>
