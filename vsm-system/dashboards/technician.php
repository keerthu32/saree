<?php require_once __DIR__ . '/../includes/auth.php'; ?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/navbar.php'; ?>
<div class="app-shell">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="app-content">
    <div class="panel p-4">
      <h2 class="h4 mb-1">Technician Dashboard</h2>
      <p class="text-muted mb-4">Track assigned jobs and update job progress quickly.</p>
      <div class="row g-3">
        <div class="col-md-4"><div class="stat-card"><div class="text-muted small">Assigned Today</div><div class="h3 mb-0">6</div></div></div>
        <div class="col-md-4"><div class="stat-card"><div class="text-muted small">In Progress</div><div class="h3 mb-0">3</div></div></div>
        <div class="col-md-4"><div class="stat-card"><div class="text-muted small">Completed</div><div class="h3 mb-0">9</div></div></div>
      </div>
    </div>
  </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
