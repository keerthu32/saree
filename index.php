<?php
require_once __DIR__ . '/includes/init.php';

$errors = [];
$activeTab = $_POST['action'] ?? 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'login') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ];
            if ($user['role'] === 'admin') {
                redirect(url('admin/dashboard.php'));
            }
            redirect(url('user/products.php'));
        }
        $errors[] = 'Invalid login credentials.';
    }

    if ($_POST['action'] === 'register') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if ($name === '' || $email === '' || $password === '') {
            $errors[] = 'All fields are required.';
        } else {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'Email already registered.';
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
                $stmt->execute([$name, $email, $hash, 'user']);
                $activeTab = 'login';
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3 text-center">Login / Register</h4>
                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <div><?php echo h($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $activeTab === 'login' ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#login">Login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $activeTab === 'register' ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#register">Register</button>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade <?php echo $activeTab === 'login' ? 'show active' : ''; ?>" id="login">
                        <form method="post">
                            <input type="hidden" name="action" value="login">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                    <div class="tab-pane fade <?php echo $activeTab === 'register' ? 'show active' : ''; ?>" id="register">
                        <form method="post">
                            <input type="hidden" name="action" value="register">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button class="btn btn-success w-100">Create Account</button>
                        </form>
                    </div>
                </div>
                <p class="text-muted mt-3">Admin accounts must be created directly in the database.</p>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
