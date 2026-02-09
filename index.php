<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

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

<section id="shop" class="mb-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2 class="fw-bold">Latest Sarees</h2>
      <p class="text-muted mb-0">Shop hand-picked collections curated by our admin team.</p>
    </div>
    <?php if (is_admin()): ?>
      <a class="btn btn-primary" href="admin_product.php">Add Product</a>
    <?php endif; ?>
  </div>

  <div class="row g-4">
    <?php if (count($products) > 0): ?>
      <?php foreach ($products as $product): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($product['image_filename'])): ?>
              <img
                src="uploads/<?php echo htmlspecialchars($product['image_filename']); ?>"
                class="card-img-top product-image"
                alt="<?php echo htmlspecialchars($product['name']); ?>"
              >
            <?php else: ?>
              <div class="placeholder-image">No Image</div>
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
              <p class="card-text text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="price-tag">₹<?php echo number_format((float) $product['price'], 2); ?></span>
                <button class="btn btn-outline-dark btn-sm" type="button">Add to Cart</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info">No products yet. Admins can add the first saree!</div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
