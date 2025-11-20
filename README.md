HydroAlert Minimal MVC
======================

This project demonstrates a minimal PHP MVC scaffold and a PDO-based MySQL connection.

Setup
-----
- Place the project in your XAMPP `htdocs` (it's already at `c:/xampp/htdocs/HydroAlert`).
- Edit `config/config.php` or set environment variables to match your database credentials:

  - `DB_HOST` (default `127.0.0.1`)
  - `DB_NAME` (default `hydroalert_db`)
  - `DB_USER` (default `root`)
  - `DB_PASS` (default empty)

Quick test
----------
- Start Apache + MySQL (e.g., via XAMPP Control Panel).
- Open in browser: http://localhost/HydroAlert/
- You should see the page and a line `Connected to database: hydroalert_db` if credentials are correct.

Routing
-------
- Basic router uses `?url=controller/action`. Default: `home/index`.

Files
-----
- `config/config.php` — DB configuration
- `app/core/Database.php` — PDO singleton
- `app/core/Model.php` — Base model (provides `$this->db`)
- `app/core/Controller.php` — Base controller (render helper)
- `app/controllers/HomeController.php` — Demo controller
- `app/models/ExampleModel.php` — Demo model
- `app/views/home.php` — Demo view

Next steps
----------
- Add more controllers and models.
- Add input sanitization, CSRF, and proper error handling for production.
