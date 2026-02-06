<?php
require_once __DIR__ . '/../includes/init.php';
require_user();

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect(url('user/products.php'));
}

$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    redirect(url('user/products.php'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_cart'])) {
        add_to_cart((int) $id, (int) $_POST['quantity']);
        redirect(url('user/cart.php'));
    }

    if (isset($_POST['review'])) {
        $rating = (int) $_POST['rating'];
        $comment = trim($_POST['comment']);
        if ($rating >= 1 && $rating <= 5 && $comment !== '') {
            $stmt = $pdo->prepare('INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)');
            $stmt->execute([$id, current_user()['id'], $rating, $comment]);
        }
    }
}

$reviewsStmt = $pdo->prepare('SELECT reviews.*, users.name FROM reviews JOIN users ON users.id = reviews.user_id WHERE product_id = ? ORDER BY reviews.created_at DESC');
$reviewsStmt->execute([$id]);
$reviews = $reviewsStmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <div class="col-md-5">
        <img src="<?php echo h($product['image_url'] ?: 'https://via.placeholder.com/500x350'); ?>" class="img-fluid rounded" alt="<?php echo h($product['name']); ?>">
    </div>
    <div class="col-md-7">
        <h3><?php echo h($product['name']); ?></h3>
        <p class="text-muted"><?php echo h($product['category']); ?></p>
        <p><?php echo h($product['description']); ?></p>
        <h4 class="text-success">₹<?php echo h($product['price']); ?></h4>
        <form method="post" class="mt-3">
            <input type="hidden" name="add_cart" value="1">
            <div class="input-group mb-3" style="max-width: 200px;">
                <input type="number" name="quantity" value="1" min="1" class="form-control">
                <button class="btn btn-primary">Add to Cart</button>
            </div>
        </form>
        <hr>
        <h5>Leave a Review</h5>
        <form method="post">
            <input type="hidden" name="review" value="1">
            <div class="mb-2">
                <label class="form-label">Rating</label>
                <select name="rating" class="form-select" required>
                    <option value="5">5</option>
                    <option value="4">4</option>
                    <option value="3">3</option>
                    <option value="2">2</option>
                    <option value="1">1</option>
                </select>
            </div>
            <div class="mb-2">
                <label class="form-label">Comment</label>
                <textarea name="comment" class="form-control" required></textarea>
            </div>
            <button class="btn btn-outline-dark">Submit Review</button>
        </form>
    </div>
</div>
<hr>
<h5>Customer Reviews</h5>
<?php if (!$reviews): ?>
    <p class="text-muted">No reviews yet.</p>
<?php endif; ?>
<?php foreach ($reviews as $review): ?>
    <div class="card mb-2">
        <div class="card-body">
            <strong><?php echo h($review['name']); ?></strong>
            <span class="ms-2 text-warning"><?php echo h($review['rating']); ?>/5</span>
            <p class="mb-0"><?php echo h($review['comment']); ?></p>
        </div>
    </div>
<?php endforeach; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
