<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

require_login();
require_admin();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $image = $_FILES['image'] ?? null;

    if ($name === '' || $description === '' || $price === '') {
        $errors[] = 'All fields are required.';
    } else {
        $imageFilename = null;
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $allowed = ['png', 'jpg', 'jpeg', 'gif'];
            $extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, $allowed, true)) {
                $errors[] = 'Please upload a valid product image.';
            } else {
                $imageFilename = 'product_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $image['name']);
                $target = __DIR__ . '/uploads/' . $imageFilename;
                if (!move_uploaded_file($image['tmp_name'], $target)) {
                    $errors[] = 'Unable to upload product image.';
                }
            }
        }

        if (count($errors) === 0) {
            $conn = db();
            $stmt = $conn->prepare(
                'INSERT INTO products (name, description, price, image_filename, created_at)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $createdAt = gmdate('Y-m-d H:i:s');
            $priceValue = (float) $price;
            $stmt->bind_param('ssdss', $name, $description, $priceValue, $imageFilename, $createdAt);
            $stmt->execute();
            $stmt->close();
            $success = 'Product created successfully.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card shadow-sm">
      <div class="card-body">
        <h3 class="card-title">Add a new saree</h3>
        <p class="text-muted">Upload product details and images for the storefront.</p>
        <?php if ($success): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php foreach ($errors as $error): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endforeach; ?>
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Product name</label>
            <input class="form-control" type="text" name="name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Price (INR)</label>
            <input class="form-control" type="number" step="0.01" name="price" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Product image</label>
            <input class="form-control" type="file" name="image" accept="image/*">
          </div>
          <button class="btn btn-primary" type="submit">Save product</button>
          <a class="btn btn-outline-secondary" href="admin.php">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
