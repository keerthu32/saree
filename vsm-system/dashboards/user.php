<?php require_once __DIR__ . '/../includes/auth.php'; ?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/navbar.php'; ?>
<div class="app-shell">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="app-content">
    <div class="panel p-4">
      <h2 class="h4 mb-1">User Dashboard</h2>
      <p class="text-muted mb-4">View your vehicle profiles, service history, and work order updates.</p>
      <div class="row g-3">
        <div class="col-md-6"><div class="stat-card"><div class="text-muted small">My Vehicles</div><div class="h3 mb-0">4</div></div></div>
        <div class="col-md-6"><div class="stat-card"><div class="text-muted small">Open Work Orders</div><div class="h3 mb-0">2</div></div></div>
      </div>
    </div>
  </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
