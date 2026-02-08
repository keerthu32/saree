<?php
require_once __DIR__ . '/../includes/init.php';
require_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['qty'] as $productId => $qty) {
        update_cart((int) $productId, (int) $qty);
    }
    redirect(url('user/cart.php'));
}

$cart = cart_items();
$products = [];
$total = 0;

if ($cart) {
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ({$ids})");
    $products = $stmt->fetchAll();
}

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Shopping Cart</h3>
    <span class="badge badge-soft px-3 py-2"><?php echo count($cart); ?> items</span>
</div>
<?php if (!$cart): ?>
    <div class="card p-4 text-center shadow-sm">
        <p class="mb-2">Your cart is empty.</p>
        <a class="btn btn-outline-secondary" href="<?php echo h(url('user/products.php')); ?>">Start Shopping</a>
    </div>
<?php else: ?>
    <form method="post">
        <div class="card shadow-sm mb-3">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <?php $qty = $cart[$product['id']]; ?>
                            <?php $subtotal = $product['price'] * $qty; ?>
                            <?php $total += $subtotal; ?>
                            <tr>
                                <td><?php echo h($product['name']); ?></td>
                                <td>₹<?php echo h($product['price']); ?></td>
                                <td style="max-width: 120px;">
                                    <input type="number" name="qty[<?php echo h($product['id']); ?>]" value="<?php echo h($qty); ?>" min="0" class="form-control">
                                </td>
                                <td>₹<?php echo h($subtotal); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex flex-wrap justify-content-between gap-2">
            <a class="btn btn-outline-secondary" href="<?php echo h(url('user/products.php')); ?>">Continue Shopping</a>
            <div class="summary-card shadow-sm">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total</span>
                    <strong>₹<?php echo h($total); ?></strong>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-dark flex-fill">Update Cart</button>
                    <a class="btn btn-primary flex-fill" href="<?php echo h(url('user/checkout.php')); ?>">Checkout</a>
                </div>
            </div>
        </div>
    </form>
<?php endif; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
