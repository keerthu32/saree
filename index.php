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
<div class="row g-4 align-items-center">
    <div class="col-lg-6">
        <div class="hero shadow-lg">
            <h2 class="fw-semibold">Kurinji Handloom Sarees</h2>
            <p class="mb-4">A curated collection of authentic weaves, premium zari, and artisanal craftsmanship.</p>
            <div class="d-flex gap-3">
                <div>
                    <h5 class="mb-0">200+</h5>
                    <small>Handpicked designs</small>
                </div>
                <div>
                    <h5 class="mb-0">24/7</h5>
                    <small>Order tracking</small>
                </div>
                <div>
                    <h5 class="mb-0">100%</h5>
                    <small>Secure checkout</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                <h4 class="mb-3 text-center">Login / Register</h4>
                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <div><?php echo h($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <ul class="nav nav-pills justify-content-center mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $activeTab === 'login' ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#login">Login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $activeTab === 'register' ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#register">Register</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade <?php echo $activeTab === 'login' ? 'show active' : ''; ?>" id="login">
                        <form method="post" class="mt-3">
                            <input type="hidden" name="action" value="login">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                            <button class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                    <div class="tab-pane fade <?php echo $activeTab === 'register' ? 'show active' : ''; ?>" id="register">
                        <form method="post" class="mt-3">
                            <input type="hidden" name="action" value="register">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Full name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                            </div>
                            <button class="btn btn-success w-100">Create Account</button>
                        </form>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-center">Admin accounts must be created directly in the database.</p>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
