<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login - HydroAlert</title>
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
    body { font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; min-height:100vh; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg,#0f172a 0%, #0ea5a3 100%); }
    .card { border-radius: 12px; }
    .brand { display:flex; align-items:center; gap:.6rem; }
    .logo-img { width:36px; height:36px; border-radius:8px; object-fit:cover; display:inline-block }
    .muted-small { color: rgba(255,255,255,0.8); font-size:.85rem }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-5">
        <div class="card shadow-lg p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="brand">
              <img src="app/views/assets/img/app_icon.png" class="logo-img" alt="HydroAlert">
              <div>
                <div class="h5 mb-0">HydroAlert</div>
                <small class="text-muted">Monitoring & Management</small>
              </div>
            </div>
            <div class="text-end muted-small">v0.1</div>
          </div>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <?php if (!empty($_GET['timeout'])): ?>
            <div class="alert alert-warning" role="alert">
              Your session timed out due to inactivity. Please sign in again.
            </div>
          <?php endif; ?>

          <form method="post" action="?url=auth/login">
            <div class="mb-3">
              <label class="form-label">Username</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input class="form-control" type="text" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required autofocus>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input class="form-control" type="password" name="password" required>
              </div>
            </div>

            <div class="d-grid mb-2">
              <button class="btn btn-primary btn-lg" type="submit"><i class="bi bi-box-arrow-in-right me-2"></i>Sign in</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
