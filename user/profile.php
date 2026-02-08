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
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Profile</h3>
    <span class="badge badge-soft px-3 py-2">Account Settings</span>
</div>
<?php if ($message): ?>
    <div class="alert alert-success"><?php echo h($message); ?></div>
<?php endif; ?>
<form method="post" class="card p-4 shadow-sm" style="max-width: 550px;">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo h($_SESSION['user']['name']); ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="<?php echo h($user['email']); ?>" readonly>
    </div>
    <button class="btn btn-primary">Save Changes</button>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
