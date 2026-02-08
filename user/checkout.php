<?php
require_once __DIR__ . '/../includes/init.php';
require_user();

$cart = cart_items();
if (!$cart) {
    redirect(url('user/cart.php'));
}

$ids = implode(',', array_map('intval', array_keys($cart)));
$products = $pdo->query("SELECT * FROM products WHERE id IN ({$ids})")->fetchAll();
$total = 0;
foreach ($products as $product) {
    $total += $product['price'] * $cart[$product['id']];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, ?)');
    $stmt->execute([current_user()['id'], $total, 'PLACED']);
    $orderId = $pdo->lastInsertId();

    $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    $stockStmt = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');

    foreach ($products as $product) {
        $qty = $cart[$product['id']];
        $itemStmt->execute([$orderId, $product['id'], $qty, $product['price']]);
        $stockStmt->execute([$qty, $product['id']]);
    }

    $pdo->commit();
    $_SESSION['cart'] = [];
    redirect(url('user/orders.php'));
}

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Checkout</h3>
    <span class="badge badge-soft px-3 py-2">Secure Payment</span>
</div>
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5>Delivery Details</h5>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" value="<?php echo h(current_user()['name']); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="<?php echo h(current_user()['email']); ?>" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Shipping Address</label>
                        <input type="text" class="form-control" value="No. 21, Handloom Street, Madurai" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Payment Method</h5>
                <p class="text-muted mb-0">Dummy Payment (always success)</p>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="summary-card shadow-sm">
            <h5>Order Summary</h5>
            <ul class="list-group mb-3">
                <?php foreach ($products as $product): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><?php echo h($product['name']); ?> x <?php echo h($cart[$product['id']]); ?></span>
                        <span>₹<?php echo h($product['price'] * $cart[$product['id']]); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="d-flex justify-content-between mb-3">
                <strong>Total</strong>
                <strong>₹<?php echo h($total); ?></strong>
            </div>
            <form method="post">
                <button class="btn btn-success w-100">Place Order</button>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
