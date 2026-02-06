<?php
require_once __DIR__ . '/../includes/init.php';
require_admin();

$productCount = $pdo->query('SELECT COUNT(*) AS total FROM products')->fetch()['total'];
$orderCount = $pdo->query('SELECT COUNT(*) AS total FROM orders')->fetch()['total'];
$reviewCount = $pdo->query('SELECT COUNT(*) AS total FROM reviews')->fetch()['total'];
$stockTotal = $pdo->query('SELECT SUM(stock) AS total FROM products')->fetch()['total'] ?? 0;

include __DIR__ . '/../includes/header.php';
?>
<h3 class="mb-4">Admin Dashboard</h3>
<div class="row g-3">
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h6>Total Products</h6>
                <h3><?php echo h($productCount); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body">
                <h6>Total Orders</h6>
                <h3><?php echo h($orderCount); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning">
            <div class="card-body">
                <h6>Inventory Stock</h6>
                <h3><?php echo h($stockTotal); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-info">
            <div class="card-body">
                <h6>Reviews</h6>
                <h3><?php echo h($reviewCount); ?></h3>
            </div>
        </div>
    </div>
</div>
<div class="mt-4">
    <a class="btn btn-outline-primary" href="<?php echo h(url('admin/products.php')); ?>">Manage Products</a>
    <a class="btn btn-outline-secondary" href="<?php echo h(url('admin/orders.php')); ?>">Manage Orders</a>
    <a class="btn btn-outline-dark" href="<?php echo h(url('admin/reviews.php')); ?>">View Reviews</a>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
