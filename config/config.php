<?php
$dotEnv = [];
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $val) = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val);
        // remove surrounding quotes
        if ((substr($val, 0, 1) === '"' && substr($val, -1) === '"') || (substr($val, 0, 1) === "'" && substr($val, -1) === "'")) {
            $val = substr($val, 1, -1);
        }
        $dotEnv[$key] = $val;
        // export into process env if not already set
        if (getenv($key) === false) {
            putenv("$key=$val");
            if (!isset($_ENV)) $_ENV = [];
            $_ENV[$key] = $val;
            if (!isset($_SERVER)) $_SERVER = [];
            $_SERVER[$key] = $val;
        }
    }
}

return [
    'db_host' => getenv('DB_HOST') ?: ($dotEnv['DB_HOST'] ?? 'localhost'),
    'db_name' => getenv('DB_NAME') ?: ($dotEnv['DB_NAME'] ?? 'hydroalert_db'),
    'db_user' => getenv('DB_USER') ?: ($dotEnv['DB_USER'] ?? 'root'),
    'db_pass' => getenv('DB_PASS') ?: ($dotEnv['DB_PASS'] ?? ''),
    'db_charset' => 'utf8mb4',
    'uptime_start' => '2025-11-10T16:00:00Z',
    'api_key' => getenv('API_KEY') ?: ($dotEnv['API_KEY'] ?? ''),
];
