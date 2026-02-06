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
    <h3>Products</h3>
    <a class="btn btn-primary" href="<?php echo h(url('admin/product_form.php')); ?>">Add Product</a>
</div>
<table class="table table-striped">
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
                <td><?php echo h($product['category']); ?></td>
                <td>₹<?php echo h($product['price']); ?></td>
                <td><?php echo h($product['stock']); ?></td>
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
<?php include __DIR__ . '/../includes/footer.php'; ?>
