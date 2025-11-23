<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Information - HydroAlert</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="32x32" href="app/views/assets/img/app_icon.png">
  <link rel="icon" type="image/png" sizes="16x16" href="app/views/assets/img/app_icon.png">
  <link rel="apple-touch-icon" href="app/views/assets/img/app_icon.png">
  <link rel="mask-icon" href="app/views/assets/img/app_icon.png" color="#0ea5a3">
  <meta name="theme-color" content="#0ea5a3">
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
    .sidebar { min-height: 100vh; background: #0f172a; color: #fff; }
    .sidebar a { color: rgba(255,255,255,0.9); text-decoration: none }
    .sidebar .nav-link.active { background: rgba(255,255,255,0.06); border-radius:8px }
    .card-soft { border-radius: 12px; box-shadow: 0 6px 18px rgba(2,6,23,0.08); }
    .logo-img { width:36px; height:36px; border-radius:8px; object-fit:cover; display:inline-block }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">
  <div class="d-flex">
    <aside class="sidebar p-3 d-none d-md-block d-flex flex-column" style="width:240px">
      <div class="mb-4">
        <div class="d-flex align-items-center gap-2">
          <img src="app/views/assets/img/app_icon.png" class="logo-img" alt="HydroAlert">
          <div>
            <div class="fw-bold">HydroAlert</div>
              <small class="text-white-50">Monitoring</small>
          </div>
        </div>
      </div>
      <nav class="nav flex-column">
        <a class="nav-link p-2 mb-1" href="?url=home/index"><i class="bi bi-speedometer2 me-2"></i>Overview</a>
        <a class="nav-link active p-2 mb-1" href="?url=info/index"><i class="bi bi-bell me-2"></i>Information</a>
      </nav>
      <div class="mt-4 small text-white-50">Welcome <strong><?php echo htmlspecialchars($user['username'] ?? ''); ?>!</strong></div>
      <div class="mt-auto pt-3 border-top pt-3 small text-white-50 text-center">&copy; <?php echo date('Y'); ?> HydroAlert</div>
    </aside>

    <main class="flex-grow-1 p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Information</h2>
        <div>
          <a class="btn btn-outline-secondary btn-sm me-2" href="?url=auth/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <div>
                <h5 class="mb-0">Evacuation Centers</h5>
                <small class="text-muted">Add, edit, delete centers and manage status</small>
              </div>
              <button class="btn btn-sm btn-primary" id="addCenterBtn">Add Evacuation Center</button>
            </div>
            <div class="card-body">
              <div style="max-height:200px; overflow:auto;">
                <table class="table table-sm table-hover" id="evacTable">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Address</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach (($centers ?? []) as $c): ?>
                    <tr data-id="<?php echo htmlspecialchars($c['id']); ?>">
                      <td><?php echo htmlspecialchars($c['name']); ?></td>
                      <td><?php echo htmlspecialchars($c['address']); ?></td>
                      <td>
                        <div class="form-check form-switch">
                          <input class="form-check-input status-toggle" type="checkbox" role="switch" <?php echo ($c['status'] ?? 'active') === 'active' ? 'checked' : ''; ?> data-id="<?php echo htmlspecialchars($c['id']); ?>">
                          <label class="form-check-label small"><?php echo htmlspecialchars($c['status'] ?? 'active'); ?></label>
                        </div>
                      </td>
                      <td>
                        <a class="btn btn-sm btn-outline-secondary editBtn" href="#" data-id="<?php echo htmlspecialchars($c['id']); ?>">Edit</a>
                        <a class="btn btn-sm btn-outline-danger deleteBtn" href="#" data-href="?url=info/delete&id=<?php echo htmlspecialchars($c['id']); ?>">Delete</a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 mt-3">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-0">Emergency Measures</h5>
              <small class="text-muted">Local emergency measures and instructions</small>
            </div>
            <button id="addMeasureBtn" class="btn btn-sm btn-primary">Add Measure</button>
          </div>
          <div class="card-body">
            <div style="max-height:200px; overflow:auto;">
              <table class="table table-sm table-hover">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($measures)) : ?>
                    <?php foreach ($measures as $m) : ?>
                      <tr>
                        <td><?php echo htmlspecialchars($m['title']); ?></td>
                        <td><?php echo htmlspecialchars(strlen($m['description']) > 100 ? substr($m['description'], 0, 100) . '...' : $m['description']); ?></td>
                        <td>
                          <a href="#" class="btn btn-sm btn-outline-secondary edit-measure" data-id="<?php echo $m['id']; ?>">Edit</a>
                          <a href="#" class="btn btn-sm btn-outline-danger delete-measure" data-href="?url=measure/delete&id=<?php echo $m['id']; ?>">Delete</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <tr>
                      <td colspan="3">No measures defined.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3 mt-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <div>
                <h5 class="mb-0">Contacts</h5>
                <small class="text-muted">Manage emergency contacts for SMS/alerts</small>
              </div>
              <button class="btn btn-sm btn-primary" id="addContactBtn">Add Contact</button>
            </div>
            <div class="card-body">
              <div style="max-height:200px; overflow:auto;">
                <table class="table table-sm table-hover" id="contactsTable">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Phone</th>
                      <th>Email</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach (($contacts ?? []) as $ct): ?>
                    <tr data-id="<?php echo htmlspecialchars($ct['id']); ?>">
                      <td><?php echo htmlspecialchars($ct['name']); ?></td>
                      <td><?php echo htmlspecialchars($ct['phone']); ?></td>
                      <td><?php echo !empty($ct['email']) ? htmlspecialchars($ct['email']) : 'None'; ?></td>
                      <td>
                        <a class="btn btn-sm btn-outline-secondary contact-editBtn" href="#" data-id="<?php echo htmlspecialchars($ct['id']); ?>">Edit</a>
                        <a class="btn btn-sm btn-outline-danger contact-deleteBtn" href="#" data-href="?url=contact/delete&id=<?php echo htmlspecialchars($ct['id']); ?>">Delete</a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="evacModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <form class="modal-content" id="evacForm" method="post" action="?url=info/store">
            <div class="modal-header">
              <h5 class="modal-title">Add Evacuation Center</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="centerId">
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input class="form-control" name="name" id="centerName" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Address</label>
                <input class="form-control" name="address" id="centerAddress">
              </div>
              <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status" id="centerStatus">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="app/views/assets/js/evacuation.js"></script>
  <script src="app/views/assets/js/contacts.js"></script>
    <script src="app/views/assets/js/measures.js"></script>
  <script src="app/views/assets/js/information.js"></script>
</body>
</html>
