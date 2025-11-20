<?php
// Database configuration. You can override with environment variables (DB_HOST, DB_NAME, DB_USER, DB_PASS).
return [
    'db_host' => getenv('DB_HOST') ?: 'localhost',
    'db_name' => getenv('DB_NAME') ?: 'hydroalert_db',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_pass' => getenv('DB_PASS') ?: '',
    'db_charset' => 'utf8mb4',
    'uptime_start' => '2025-11-10T16:00:00Z',
];
