<?php
$role_names = [
    'admin'    => 'Администратор',
    'manager'  => 'Руководитель',
    'employee' => 'Сотрудник',
];

$status_names = [
    'new'      => 'Новая',
    'approved' => 'Одобрена',
    'rejected' => 'Отклонена',
];

$request_types = ['Отпуск', 'Отгул', 'Командировка', 'Справка с места работы'];

function e($text)
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function check_login()
{
    global $base;
    if (!is_logged_in()) {
        header('Location: ' . $base . 'login.php');
        exit;
    }
}

function has_role($roles)
{
    return isset($_SESSION['role']) && in_array($_SESSION['role'], $roles);
}

function check_role($roles)
{
    global $base;
    check_login();
    if (!has_role($roles)) {
        header('Location: ' . $base . 'access_denied.php');
        exit;
    }
}

function format_date($date)
{
    return $date ? date('d.m.Y', strtotime($date)) : '—';
}
