<?php
require_once __DIR__ . '/includes/init.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            redirect(url('index.php'));
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                <h4 class="mb-3 text-center">Create Your Account</h4>
                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <div><?php echo h($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form method="post" class="mt-3">
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
                        <input type="password" name="password" class="form-control" placeholder="Create a password" required data-password-field>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="toggleRegisterPassword">
                            <label class="form-check-label" for="toggleRegisterPassword">Show password</label>
                        </div>
                    </div>
                    <button class="btn btn-success w-100">Create Account</button>
                </form>
                <div class="text-center mt-3">
                    <a class="small" href="<?php echo h(url('index.php')); ?>">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleRegister = document.getElementById('toggleRegisterPassword');
        if (toggleRegister) {
            toggleRegister.addEventListener('change', function () {
                const field = document.querySelector('[data-password-field]');
                if (field) {
                    field.type = this.checked ? 'text' : 'password';
                }
            });
        }
    });
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
