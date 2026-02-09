<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $errors[] = 'All fields are required.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    } else {
        $conn = db();
        $stmt = $conn->prepare(
            'INSERT INTO users (username, email, password_hash, role, created_at)
             VALUES (?, ?, ?, ?, ?)'
        );
        $role = 'user';
        $createdAt = gmdate('Y-m-d H:i:s');
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param('sssss', $username, $email, $passwordHash, $role, $createdAt);

        try {
            $stmt->execute();
            $success = 'Registration successful. Please log in.';
        } catch (Exception $exception) {
            $errors[] = 'Username or email already exists.';
        } finally {
            $stmt->close();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-body">
        <h3 class="card-title mb-3">Create your account</h3>
        <?php if ($success): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php foreach ($errors as $error): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endforeach; ?>
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input class="form-control" type="text" name="username" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input class="form-control" type="password" name="password" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Confirm password</label>
            <input class="form-control" type="password" name="confirm" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">Register</button>
        </form>
        <p class="mt-3 text-center">Already have an account? <a href="login.php">Log in</a></p>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
