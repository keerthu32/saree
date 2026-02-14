<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= BASE_URL ?>/dashboard.php"><?= APP_NAME ?></a>
    <div class="ms-auto text-white"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Guest') ?></div>
    <a href="<?= BASE_URL ?>/logout.php" class="btn btn-outline-light btn-sm ms-3">Logout</a>
  </div>
</nav>
