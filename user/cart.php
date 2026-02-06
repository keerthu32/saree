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
<h3 class="mb-3">Shopping Cart</h3>
<?php if (!$cart): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <form method="post">
        <table class="table">
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
        <div class="d-flex justify-content-between">
            <a class="btn btn-outline-secondary" href="<?php echo h(url('user/products.php')); ?>">Continue Shopping</a>
            <div>
                <strong class="me-3">Total: ₹<?php echo h($total); ?></strong>
                <button class="btn btn-outline-dark">Update Cart</button>
                <a class="btn btn-primary" href="<?php echo h(url('user/checkout.php')); ?>">Checkout</a>
            </div>
        </div>
    </form>
<?php endif; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
