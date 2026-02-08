<?php
session_start();

$timeout = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
}
$_SESSION['last_activity'] = time();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';
