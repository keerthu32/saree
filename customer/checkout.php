<?php
require_once __DIR__ . '/../includes/init.php';
require_customer();

$cart = cart_items();
if (!$cart) {
    redirect(url('customer/cart.php'));
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
    $stmt->execute([current_user()['id'], $total, 'pending']);
    $orderId = $pdo->lastInsertId();

    $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    $stockStmt = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');

    foreach ($products as $product) {
        $qty = $cart[$product['id']];
        $itemStmt->execute([$orderId, $product['id'], $qty, $product['price']]);
        $stockStmt->execute([$qty, $product['id']]);
    }

    $paymentStatus = 'success';
    if ($paymentStatus === 'success') {
        $statusStmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $statusStmt->execute(['processed', $orderId]);
    }

    $pdo->commit();
    $_SESSION['cart'] = [];
    redirect(url('customer/orders.php'));
}

include __DIR__ . '/../includes/header.php';
?>
<h3 class="mb-3">Checkout</h3>
<div class="card p-4">
    <h5>Order Summary</h5>
    <ul class="list-group mb-3">
        <?php foreach ($products as $product): ?>
            <li class="list-group-item d-flex justify-content-between">
                <span><?php echo h($product['name']); ?> x <?php echo h($cart[$product['id']]); ?></span>
                <span>₹<?php echo h($product['price'] * $cart[$product['id']]); ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
    <h4>Total: ₹<?php echo h($total); ?></h4>
    <p class="text-muted">Payment method: Dummy Payment (always success)</p>
    <form method="post">
        <button class="btn btn-success">Place Order</button>
    </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
