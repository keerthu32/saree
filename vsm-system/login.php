<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare('SELECT id, name, email, role, password_hash FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid credentials.';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - <?= APP_NAME ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="app-bg">
<div class="login-wrap">
  <div class="card login-card border-0 shadow-lg">
    <div class="row g-0">
      <div class="col-lg-6 login-hero p-4 p-lg-5 d-flex flex-column justify-content-center">
        <h2 class="fw-bold mb-3">Welcome to <?= APP_NAME ?></h2>
        <p class="mb-0 text-white-50">Manage work orders, services, billing, and reports from one clean dashboard.</p>
      </div>
      <div class="col-lg-6 p-4 p-lg-5 bg-white">
        <h4 class="fw-semibold mb-1">Sign In</h4>
        <p class="text-muted mb-4">Access your dashboard securely.</p>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" class="vstack gap-3">
          <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control form-control-lg" required>
          </div>
          <div>
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control form-control-lg" required>
          </div>
          <button class="btn btn-primary btn-lg w-100">Sign In</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
