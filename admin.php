<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

require_login();
require_admin();

$conn = db();
$productsResult = $conn->query('SELECT * FROM products ORDER BY created_at DESC');
$products = [];
if ($productsResult) {
    while ($row = $productsResult->fetch_assoc()) {
        $products[] = $row;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="fw-bold">Admin Dashboard</h2>
    <p class="text-muted mb-0">Manage your saree catalog and keep products updated.</p>
  </div>
  <a class="btn btn-primary" href="admin_product.php">Add New Product</a>
</div>

<div class="table-responsive">
  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>Product</th>
        <th>Description</th>
        <th>Price</th>
        <th>Image</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
          <tr>
            <td class="fw-semibold"><?php echo htmlspecialchars($product['name']); ?></td>
            <td class="text-muted"><?php echo htmlspecialchars($product['description']); ?></td>
            <td>₹<?php echo number_format((float) $product['price'], 2); ?></td>
            <td>
              <?php if (!empty($product['image_filename'])): ?>
                <img
                  src="uploads/<?php echo htmlspecialchars($product['image_filename']); ?>"
                  class="admin-thumb"
                  alt="<?php echo htmlspecialchars($product['name']); ?>"
                >
              <?php else: ?>
                <span class="text-muted">No image</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" class="text-center">No products available yet.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
