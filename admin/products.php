<?php
require_once __DIR__ . '/../includes/init.php';
require_admin();

if (isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$_POST['delete_id']]);
}

$products = $pdo->query('SELECT * FROM products ORDER BY created_at DESC')->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Products</h3>
        <p class="text-muted mb-0">Manage listings, stock levels, and pricing.</p>
    </div>
    <a class="btn btn-primary" href="<?php echo h(url('admin/product_form.php')); ?>"><i class="bi bi-plus-circle me-1"></i>Add Product</a>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo h($product['name']); ?></td>
                        <td><span class="badge badge-soft"><?php echo h($product['category']); ?></span></td>
                        <td>₹<?php echo h($product['price']); ?></td>
                        <td>
                            <span class="fw-semibold"><?php echo h($product['stock']); ?></span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="<?php echo h(url('admin/product_form.php?id=' . $product['id'])); ?>">Edit</a>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="delete_id" value="<?php echo h($product['id']); ?>">
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
