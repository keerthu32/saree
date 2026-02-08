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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?php echo h(url('assets/styles.css')); ?>" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="<?php echo h(url('user/products.php')); ?>">
            Kurinji <span>Handloom</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($user): ?>
                    <li class="nav-item text-white-50 me-3 align-self-center">Hi, <?php echo h($user['name']); ?></li>
                    <?php if ($user['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo h(url('admin/dashboard.php')); ?>"><i class="bi bi-speedometer2 me-1"></i>Admin</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo h(url('user/cart.php')); ?>"><i class="bi bi-bag-check me-1"></i>Cart</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo h(url('user/orders.php')); ?>"><i class="bi bi-truck me-1"></i>Orders</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo h(url('user/profile.php')); ?>"><i class="bi bi-person-circle me-1"></i>Profile</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo h(url('logout.php')); ?>"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo h(url('index.php')); ?>"><i class="bi bi-person-lock me-1"></i>Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="app-content">
    <div class="container py-4">
