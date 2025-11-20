<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>HydroAlert</title>
  <link rel="icon" type="image/png" sizes="32x32" href="app/views/assets/img/app_icon.png">
  <link rel="apple-touch-icon" href="app/views/assets/img/app_icon.png">
  <meta name="theme-color" content="#0ea5a3">
</head>
<body>
  <h1>HydroAlert</h1>
  <p>Connected to database: <?php echo htmlspecialchars($dbName ?? 'not connected'); ?></p>

  <?php if (!empty($_SESSION['user'])): ?>
    <p>Signed in as <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong> — <a href="?url=auth/logout">Logout</a></p>
  <?php else: ?>
    <p><a href="?url=auth/login">Login</a></p>
  <?php endif; ?>

  <p>To test other routes use <code>?url=controller/action</code>.</p>
</body>
</html>
