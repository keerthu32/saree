<nav class="navbar navbar-expand-lg app-navbar shadow-sm sticky-top">
  <div class="container-fluid px-3 px-lg-4">
    <a class="navbar-brand fw-semibold" href="<?= BASE_URL ?>/dashboard.php"><?= APP_NAME ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav" aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="topNav">
      <div class="ms-auto d-flex align-items-center gap-3 mt-3 mt-lg-0">
        <span class="badge text-bg-light px-3 py-2"><?= htmlspecialchars(ucfirst($_SESSION['user']['role'] ?? 'guest')) ?></span>
        <span class="text-white-50 small">Signed in as <strong class="text-white"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Guest') ?></strong></span>
        <a href="<?= BASE_URL ?>/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
      </div>
    </div>
  </div>
</nav>
