<?php
require_once __DIR__ . '/../../includes/auth.php';
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';
?>
<div class="app-shell">
  <?php include __DIR__ . '/../../includes/sidebar.php'; ?>
  <main class="app-content">
    <section class="panel p-4">
      <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
        <div>
          <h3 class="h5 mb-1">Update Work Order Status</h3>
          <p class="text-muted mb-0">Change work order status.</p>
        </div>
        <button class="btn btn-primary btn-sm">New</button>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr><th>#</th><th>Name</th><th>Status</th><th>Updated</th><th class="text-end">Action</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>Sample Item A</td><td><span class="badge text-bg-success">Active</span></td><td>Just now</td><td class="text-end"><a href="#" class="btn btn-outline-primary btn-sm">View</a></td></tr>
            <tr><td>2</td><td>Sample Item B</td><td><span class="badge text-bg-warning">Pending</span></td><td>Today</td><td class="text-end"><a href="#" class="btn btn-outline-primary btn-sm">View</a></td></tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
