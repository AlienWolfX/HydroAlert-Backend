<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard - HydroAlert</title>
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
        <a class="nav-link active p-2 mb-1" href="?url=home/index"><i class="bi bi-speedometer2 me-2"></i>Overview</a>
        <a class="nav-link p-2 mb-1" href="?url=info/index"><i class="bi bi-bell me-2"></i>Information</a>
      </nav>
      <div class="mt-4 small text-white-50">Welcome <strong><?php echo htmlspecialchars($user['username'] ?? ''); ?>!</strong></div>
      <div class="mt-auto pt-3 border-top pt-3 small text-white-50 text-center">&copy; <?php echo date('Y'); ?> HydroAlert</div>
    </aside>

    <main class="flex-grow-1 p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Dashboard</h2>
        <div>
          <a class="btn btn-outline-secondary btn-sm me-2" href="?url=auth/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-12 col-lg-8">
          <div class="card card-soft p-3" style="min-height:320px;">
            <h5>Water Level Trend</h5>
            <div style="position:relative;height:260px;">
              <canvas id="waterChart"></canvas>
            </div>
            <p class="text-muted mt-2">Showing recent water level measurements (meters).</p>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <div class="card card-soft p-3" style="min-height:320px;">
            <div class="d-flex flex-column justify-content-between h-100">
              <div class="text-center">
                <h5>Local Time — Philippines</h5>
                <div class="display-6 fw-bold" id="phTime">--:--:--</div>
                <div class="small text-muted" id="phDate">Loading date...</div>
                <div class="mt-3">
                  <button class="btn btn-outline-secondary btn-sm" id="toggleClock">24h</button>
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="w-100">
                    <?php
                      $cfg = require __DIR__ . '/../../config/config.php';
                      $uptimeStart = $cfg['uptime_start'] ?? '2025-01-01T00:00:00Z';
                    ?>
                    <div class="text-muted text-center">Dashboard Uptime</div>
                    <div class="display-6 fw-bold text-center mb-0" id="uptimeCounter" data-start="<?php echo htmlspecialchars($uptimeStart); ?>">Calculating...</div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Trend & right-column moved up; sensors cards removed -->
    </main>
  </div>
  <footer class="mt-auto text-center small text-muted py-2 border-top">&copy; <?php echo date('Y'); ?> HydroAlert</footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="app/views/assets/js/uptime.js"></script>
  <script src="app/views/assets/js/clock.js"></script>
  <script>
    const sampleLabels = ['-6h','-5h','-4h','-3h','-2h','-1h','Now'];
    const sampleData = [2.10,2.30,2.50,2.70,2.60,2.80,3.00];

    (function() {
      const canvas = document.getElementById('waterChart');
      if (!canvas) return;
      const ctx = canvas.getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: sampleLabels,
          datasets: [{
            label: 'Water Level (m)',
            data: sampleData,
            borderColor: '#0ea5a3',
            backgroundColor: 'rgba(14,165,163,0.12)',
            fill: true,
            tension: 0.35,
            pointRadius: 3
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: { beginAtZero: false, title: { display: true, text: 'Meters' } },
            x: { title: { display: true, text: 'Time' } }
          },
          plugins: {
            legend: { display: true, position: 'top' }
          }
        }
      });
    })();
  </script>
</body>
</html>
