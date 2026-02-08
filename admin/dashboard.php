<?php
require_once __DIR__ . '/../includes/init.php';
require_admin();

$productCount = $pdo->query('SELECT COUNT(*) AS total FROM products')->fetch()['total'];
$orderCount = $pdo->query('SELECT COUNT(*) AS total FROM orders')->fetch()['total'];
$reviewCount = $pdo->query('SELECT COUNT(*) AS total FROM reviews')->fetch()['total'];
$stockTotal = $pdo->query('SELECT SUM(stock) AS total FROM products')->fetch()['total'] ?? 0;

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Admin Dashboard</h3>
        <p class="text-muted mb-0">Track products, orders, stock, and reviews in one place.</p>
    </div>
    <span class="badge badge-soft px-3 py-2">Single Vendor Control</span>
</div>
<div class="row g-3">
    <div class="col-md-3">
        <div class="card text-bg-primary card-hover">
            <div class="card-body">
                <h6>Total Products</h6>
                <h3><?php echo h($productCount); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success card-hover">
            <div class="card-body">
                <h6>Total Orders</h6>
                <h3><?php echo h($orderCount); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning card-hover">
            <div class="card-body">
                <h6>Inventory Stock</h6>
                <h3><?php echo h($stockTotal); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-info card-hover">
            <div class="card-body">
                <h6>Reviews</h6>
                <h3><?php echo h($reviewCount); ?></h3>
            </div>
        </div>
    </div>
</div>
<div class="mt-4 d-flex flex-wrap gap-2">
    <a class="btn btn-primary" href="<?php echo h(url('admin/products.php')); ?>"><i class="bi bi-boxes me-1"></i>Manage Products</a>
    <a class="btn btn-outline-secondary" href="<?php echo h(url('admin/orders.php')); ?>"><i class="bi bi-truck me-1"></i>Manage Orders</a>
    <a class="btn btn-outline-dark" href="<?php echo h(url('admin/reviews.php')); ?>"><i class="bi bi-star me-1"></i>View Reviews</a>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
