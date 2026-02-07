<?php
$user = current_user();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kurinji Handloom Sarees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo h(url('customer/products.php')); ?>">Kurinji Sarees</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <?php if ($user): ?>
                    <li class="nav-item text-white me-3">Hi, <?php echo h($user['name']); ?></li>
                    <?php if ($user['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo h(url('admin/dashboard.php')); ?>">Admin</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo h(url('customer/cart.php')); ?>">Cart</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo h(url('customer/orders.php')); ?>">Orders</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo h(url('customer/profile.php')); ?>">Profile</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo h(url('logout.php')); ?>">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo h(url('login.php')); ?>">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container py-4">
