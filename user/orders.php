<?php
require_once __DIR__ . '/../includes/init.php';
require_user();

$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([current_user()['id']]);
$orders = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">My Orders</h3>
    <span class="badge badge-soft px-3 py-2"><?php echo count($orders); ?> orders</span>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table mb-0">
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
                        <td>
                            <span class="badge <?php echo $order['status'] === 'DELIVERED' ? 'text-bg-success' : ($order['status'] === 'SHIPPED' ? 'text-bg-info' : 'text-bg-warning'); ?>">
                                <?php echo h($order['status']); ?>
                            </span>
                        </td>
                        <td><?php echo h($order['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
