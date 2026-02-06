<?php
require_once __DIR__ . '/../includes/init.php';
require_user();

$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([current_user()['id']]);
$orders = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<h3 class="mb-3">My Orders</h3>
<table class="table">
    <thead>
        <tr>
            <th>Order</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?php echo h($order['id']); ?></td>
                <td>₹<?php echo h($order['total_amount']); ?></td>
                <td><?php echo h($order['status']); ?></td>
                <td><?php echo h($order['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
