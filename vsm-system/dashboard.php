<?php
require_once __DIR__ . '/includes/auth.php';
$role = $_SESSION['user']['role'] ?? 'user';
$allowed = ['admin', 'user', 'technician'];
if (!in_array($role, $allowed, true)) {
    $role = 'user';
}
require __DIR__ . '/dashboards/' . $role . '.php';
