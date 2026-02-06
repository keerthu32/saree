<?php
require_once __DIR__ . '/includes/init.php';
session_unset();
session_destroy();
redirect(url('index.php'));
