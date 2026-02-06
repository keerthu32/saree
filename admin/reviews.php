<?php
require_once __DIR__ . '/../includes/init.php';
require_admin();

$reviews = $pdo->query('SELECT reviews.*, users.name AS reviewer, products.name AS product_name FROM reviews JOIN users ON users.id = reviews.user_id JOIN products ON products.id = reviews.product_id ORDER BY reviews.created_at DESC')->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<h3 class="mb-3">Product Reviews</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Product</th>
            <th>Reviewer</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?php echo h($review['product_name']); ?></td>
                <td><?php echo h($review['reviewer']); ?></td>
                <td><?php echo h($review['rating']); ?>/5</td>
                <td><?php echo h($review['comment']); ?></td>
                <td><?php echo h($review['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
