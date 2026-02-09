<?php
require_once __DIR__ . '/auth.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Saree Store</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    >
    <link rel="stylesheet" href="assets/css/styles.css">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="index.php">Saree Store</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <?php if (is_logged_in()): ?>
              <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
              <?php if (is_admin()): ?>
                <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
              <?php endif; ?>
              <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
              <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>

    <header class="hero-section text-white">
      <div class="container py-5">
        <h1 class="display-5 fw-bold">Elegant Sarees for Every Occasion</h1>
        <p class="lead">Discover premium collections curated by our boutique admins.</p>
        <a href="#shop" class="btn btn-light btn-lg">Shop Now</a>
      </div>
    </header>

    <main class="container my-4">
