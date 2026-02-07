<?php
require_once __DIR__ . '/includes/init.php';

if (current_user()) {
    if (current_user()['role'] === 'admin') {
        redirect(url('admin/dashboard.php'));
    }
    redirect(url('customer/products.php'));
}

$featured = $pdo->query('SELECT id, name, price, image_url FROM products ORDER BY created_at DESC LIMIT 4')->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="row align-items-center mb-5">
    <div class="col-lg-6">
        <h1 class="display-5 fw-bold">Kurinji Handloom Sarees</h1>
        <p class="lead text-muted">Discover authentic handloom collections crafted by artisans. Create an account to shop, track orders, and share reviews.</p>
        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="<?php echo h(url('login.php')); ?>">Login</a>
            <a class="btn btn-outline-secondary" href="<?php echo h(url('register.php')); ?>">Register</a>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Why shop with us?</h5>
                <ul class="mb-0 text-muted">
                    <li>Handpicked artisan-made sarees.</li>
                    <li>Secure orders with dummy checkout flow.</li>
                    <li>Track your purchases and leave reviews.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<h4 class="mb-3">Latest Arrivals</h4>
<div class="row g-3">
    <?php if (!$featured): ?>
        <p class="text-muted">No products added yet. Please check back soon.</p>
    <?php else: ?>
        <?php foreach ($featured as $product): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <?php if ($product['image_url']): ?>
                        <img src="<?php echo h($product['image_url']); ?>" class="card-img-top" alt="<?php echo h($product['name']); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h6 class="card-title"><?php echo h($product['name']); ?></h6>
                        <p class="mb-0">₹<?php echo h($product['price']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
