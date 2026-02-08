<?php
require_once __DIR__ . '/../includes/init.php';
require_user();

$category = $_GET['category'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';

$query = 'SELECT * FROM products WHERE 1=1';
$params = [];
if ($category) {
    $query .= ' AND category = ?';
    $params[] = $category;
}
if ($minPrice !== '') {
    $query .= ' AND price >= ?';
    $params[] = $minPrice;
}
if ($maxPrice !== '') {
    $query .= ' AND price <= ?';
    $params[] = $maxPrice;
}
$query .= ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query('SELECT DISTINCT category FROM products')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    add_to_cart((int) $_POST['product_id'], (int) $_POST['quantity']);
    redirect(url('user/cart.php'));
}

include __DIR__ . '/../includes/header.php';
?>
<div class="hero mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-1">Shop the Kurinji Collection</h2>
            <p class="mb-0">Premium sarees curated with artisan craftsmanship and contemporary elegance.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <span class="badge bg-light text-dark">Free Shipping over ₹2000</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="card p-3 mb-3 shadow-sm">
            <h6 class="section-title">Filters</h6>
            <form method="get">
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo h($cat['category']); ?>" <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                                <?php echo h($cat['category']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Min Price</label>
                    <input type="number" name="min_price" class="form-control" value="<?php echo h($minPrice); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Max Price</label>
                    <input type="number" name="max_price" class="form-control" value="<?php echo h($maxPrice); ?>">
                </div>
                <button class="btn btn-outline-dark w-100">Apply</button>
            </form>
        </div>
        <div class="summary-card">
            <h6 class="section-title">Why Kurinji?</h6>
            <ul class="list-unstyled mb-0 small">
                <li class="mb-2"><i class="bi bi-patch-check me-1 text-success"></i>Verified handloom weavers</li>
                <li class="mb-2"><i class="bi bi-shield-check me-1 text-success"></i>Secure checkout</li>
                <li><i class="bi bi-arrow-repeat me-1 text-success"></i>Easy order tracking</li>
            </ul>
        </div>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Latest Sarees</h3>
            <span class="text-muted small"><?php echo count($products); ?> items</span>
        </div>
        <div class="row g-3">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm card-hover">
                        <img src="<?php echo h($product['image_url'] ?: 'https://via.placeholder.com/300x200'); ?>" class="card-img-top" alt="<?php echo h($product['name']); ?>">
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title"><?php echo h($product['name']); ?></h6>
                            <p class="small text-muted mb-1"><?php echo h($product['category']); ?></p>
                            <p class="fw-bold">₹<?php echo h($product['price']); ?></p>
                            <a class="btn btn-sm btn-outline-secondary mb-2" href="<?php echo h(url('user/product.php?id=' . $product['id'])); ?>">View Details</a>
                            <form method="post" class="mt-auto">
                                <input type="hidden" name="product_id" value="<?php echo h($product['id']); ?>">
                                <input type="number" name="quantity" value="1" min="1" class="form-control mb-2">
                                <button class="btn btn-primary w-100">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
