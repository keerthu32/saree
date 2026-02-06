<?php
require_once __DIR__ . '/../includes/init.php';
require_admin();

$id = $_GET['id'] ?? null;
$product = [
    'name' => '',
    'description' => '',
    'price' => '',
    'stock' => '',
    'category' => '',
    'image_url' => '',
];

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch() ?: $product;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        $_POST['name'],
        $_POST['description'],
        $_POST['price'],
        $_POST['stock'],
        $_POST['category'],
        $_POST['image_url'],
    ];

    if ($id) {
        $data[] = $id;
        $stmt = $pdo->prepare('UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category = ?, image_url = ? WHERE id = ?');
        $stmt->execute($data);
    } else {
        $stmt = $pdo->prepare('INSERT INTO products (name, description, price, stock, category, image_url) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute($data);
    }
    redirect(url('admin/products.php'));
}

include __DIR__ . '/../includes/header.php';
?>
<h3 class="mb-3"><?php echo $id ? 'Edit' : 'Add'; ?> Product</h3>
<form method="post" class="card p-4">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo h($product['name']); ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="<?php echo h($product['category']); ?>" required>
        </div>
        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required><?php echo h($product['description']); ?></textarea>
        </div>
        <div class="col-md-4">
            <label class="form-label">Price (INR)</label>
            <input type="number" name="price" step="0.01" class="form-control" value="<?php echo h($product['price']); ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="<?php echo h($product['stock']); ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Image URL</label>
            <input type="text" name="image_url" class="form-control" value="<?php echo h($product['image_url']); ?>">
        </div>
    </div>
    <div class="mt-3">
        <button class="btn btn-primary">Save</button>
        <a class="btn btn-outline-secondary" href="<?php echo h(url('admin/products.php')); ?>">Cancel</a>
    </div>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
