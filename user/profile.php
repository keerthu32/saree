<?php
require_once __DIR__ . '/../includes/init.php';
require_user();

$user = current_user();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if ($name !== '') {
        $stmt = $pdo->prepare('UPDATE users SET name = ? WHERE id = ?');
        $stmt->execute([$name, $user['id']]);
        $_SESSION['user']['name'] = $name;
        $message = 'Profile updated.';
    }
}

include __DIR__ . '/../includes/header.php';
?>
<h3 class="mb-3">Profile</h3>
<?php if ($message): ?>
    <div class="alert alert-success"><?php echo h($message); ?></div>
<?php endif; ?>
<form method="post" class="card p-4" style="max-width: 500px;">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo h($_SESSION['user']['name']); ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="<?php echo h($user['email']); ?>" readonly>
    </div>
    <button class="btn btn-primary">Save</button>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
