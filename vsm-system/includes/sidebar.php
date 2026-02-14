<?php $current = $_SERVER['REQUEST_URI'] ?? ''; ?>
<aside class="app-sidebar border-end">
  <div class="p-3">
    <h6 class="text-uppercase text-muted small mb-3">Main Navigation</h6>
    <ul class="nav flex-column gap-1">
      <?php
      $items = [
          'Users' => '/modules/users/list.php',
          'Vehicles' => '/modules/vehicles/list.php',
          'Services' => '/modules/services/list.php',
          'Work Orders' => '/modules/workorders/list.php',
          'Inventory' => '/modules/inventory/list.php',
          'Billing' => '/modules/billing/list.php',
          'Payments' => '/modules/payments/payment.php',
          'Reports' => '/modules/reports/summary.php',
      ];
      foreach ($items as $label => $path):
          $href = BASE_URL . $path;
          $active = str_contains($current, $path) ? 'active' : '';
      ?>
        <li class="nav-item"><a class="nav-link rounded-3 px-3 py-2 <?= $active ?>" href="<?= $href ?>"><?= $label ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</aside>
