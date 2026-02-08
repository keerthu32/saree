<?php
require_once __DIR__ . '/../includes/init.php';
require_admin();

if (isset($_POST['order_id'], $_POST['status'])) {
    $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
    $stmt->execute([$_POST['status'], $_POST['order_id']]);
}

$orders = $pdo->query('SELECT orders.*, users.name AS customer FROM orders JOIN users ON users.id = orders.user_id ORDER BY orders.created_at DESC')->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Orders</h3>
        <p class="text-muted mb-0">Review customer orders and update delivery status.</p>
    </div>
    <span class="badge badge-soft px-3 py-2">Status Control</span>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Placed At</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?php echo h($order['id']); ?></td>
                <td><?php echo h($order['customer']); ?></td>
                <td>₹<?php echo h($order['total_amount']); ?></td>
                <td>
                    <span class="badge <?php echo $order['status'] === 'DELIVERED' ? 'text-bg-success' : ($order['status'] === 'SHIPPED' ? 'text-bg-info' : 'text-bg-warning'); ?>">
                        <?php echo h($order['status']); ?>
                    </span>
                </td>
                <td><?php echo h($order['created_at']); ?></td>
                <td>
                    <form method="post" class="d-flex gap-2">
                        <input type="hidden" name="order_id" value="<?php echo h($order['id']); ?>">
                        <select name="status" class="form-select form-select-sm">
                            <?php foreach (['PLACED', 'SHIPPED', 'DELIVERED'] as $status): ?>
                                <option value="<?php echo $status; ?>" <?php echo $order['status'] === $status ? 'selected' : ''; ?>>
                                    <?php echo $status; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-sm btn-outline-primary">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
