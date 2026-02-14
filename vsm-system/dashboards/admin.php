<?php require_once __DIR__ . '/../includes/auth.php'; ?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/navbar.php'; ?>
<div class="app-shell">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="app-content">
    <div class="panel p-4 mb-4">
      <div class="d-flex justify-content-between align-items-start gap-3">
        <div>
          <h2 class="h4 mb-1">Admin Dashboard</h2>
          <p class="text-muted mb-0">Overview of operations and quick access to all modules.</p>
        </div>
        <span class="text-muted small" id="todayDate"></span>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="text-muted small">Active Work Orders</div><div class="h3 mb-0">24</div></div></div>
      <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="text-muted small">Pending Billing</div><div class="h3 mb-0">8</div></div></div>
      <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="text-muted small">Vehicles Registered</div><div class="h3 mb-0">132</div></div></div>
      <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="text-muted small">Inventory Alerts</div><div class="h3 mb-0">3</div></div></div>
    </div>
  </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
