<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

require_login();

$conn = db();
$userId = (int) ($_SESSION['user']['id'] ?? 0);
$user = null;
$errors = [];
$success = '';

$userStmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$userStmt->bind_param('i', $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult ? $userResult->fetch_assoc() : null;
$userStmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photo = $_FILES['photo'] ?? null;
    if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
        $allowed = ['png', 'jpg', 'jpeg', 'gif'];
        $extension = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowed, true)) {
            $errors[] = 'Please upload a valid image file.';
        } else {
            $filename = 'user_' . $userId . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $photo['name']);
            $target = __DIR__ . '/uploads/' . $filename;
            if (!move_uploaded_file($photo['tmp_name'], $target)) {
                $errors[] = 'Unable to upload file.';
            } else {
                $update = $conn->prepare('UPDATE users SET photo_filename = ? WHERE id = ?');
                $update->bind_param('si', $filename, $userId);
                $update->execute();
                $update->close();
                $success = 'Profile photo updated.';
                $user['photo_filename'] = $filename;
            }
        }
    } else {
        $errors[] = 'Please choose a photo to upload.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="row">
  <div class="col-lg-4">
    <div class="card shadow-sm">
      <div class="card-body text-center">
        <?php if (!empty($user['photo_filename'])): ?>
          <img
            src="uploads/<?php echo htmlspecialchars($user['photo_filename']); ?>"
            class="rounded-circle profile-photo mb-3"
            alt="Profile photo"
          >
        <?php else: ?>
          <div class="placeholder-profile mb-3">No Photo</div>
        <?php endif; ?>
        <h5 class="mb-0"><?php echo htmlspecialchars($user['username'] ?? ''); ?></h5>
        <p class="text-muted"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
        <span class="badge bg-secondary text-uppercase"><?php echo htmlspecialchars($user['role'] ?? 'user'); ?></span>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="card-title">Update profile photo</h4>
        <p class="text-muted">Upload your favorite photo to personalize your profile.</p>
        <?php if ($success): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php foreach ($errors as $error): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endforeach; ?>
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <input class="form-control" type="file" name="photo" accept="image/*" required>
          </div>
          <button class="btn btn-primary" type="submit">Upload</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
