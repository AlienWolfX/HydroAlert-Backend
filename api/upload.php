<?php

header('Content-Type: application/json');
// Allow CORS for devices
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../app/core/Database.php';
// load config for API key
$config = [];
if (file_exists(__DIR__ . '/../config/config.php')) {
    $config = require __DIR__ . '/../config/config.php';
}

$pdo = Database::getInstance()->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `readings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `imei` VARCHAR(128) DEFAULT NULL,
    `distance` DECIMAL(8,3) DEFAULT NULL,
    `water_level` INT DEFAULT NULL,
    `status` VARCHAR(32) DEFAULT NULL,
    `device_timestamp` BIGINT DEFAULT NULL,
    `max_depth` DECIMAL(8,3) DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
try {
    $pdo->exec($sql);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'DB create failed']);
    exit;
}

$raw = file_get_contents('php://input');
$data = null;
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') !== false) {
    $data = json_decode($raw, true);
} else {
    // fallback to $_POST
    $data = $_POST;
    // also attempt to parse JSON if body present
    if (empty($data) && $raw) {
        $tmp = json_decode($raw, true);
        if (is_array($tmp)) $data = $tmp;
    }
}

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid payload']);
    exit;
}

// --- API key enforcement ---
// Server must have an API key configured to accept uploads
$expectedKey = '';
if (isset($config['api_key']) && $config['api_key']) {
    $expectedKey = $config['api_key'];
} elseif (getenv('API_KEY')) {
    $expectedKey = getenv('API_KEY');
}

if (empty($expectedKey)) {
    // Server not configured with API key — fail closed to avoid accepting unauthenticated data
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server API key not configured']);
    exit;
}

// Accept API key via header `X-API-Key`, `Authorization: Bearer <key>`, or payload field `api_key`
$providedKey = '';
$headers = [];
if (function_exists('getallheaders')) {
    $headers = getallheaders();
} else {
    // fallback for non-Apache env
    foreach ($_SERVER as $k => $v) {
        if (strpos($k, 'HTTP_') === 0) {
            $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($k, 5)))));
            $headers[$name] = $v;
        }
    }
}

// make header lookup case-insensitive
$lcHeaders = array_change_key_case($headers, CASE_LOWER);
if (!empty($lcHeaders['x-api-key'])) {
    $providedKey = trim($lcHeaders['x-api-key']);
} elseif (!empty($lcHeaders['authorization'])) {
    if (stripos($lcHeaders['authorization'], 'Bearer ') === 0) {
        $providedKey = trim(substr($lcHeaders['authorization'], 7));
    } else {
        $providedKey = trim($lcHeaders['authorization']);
    }
} elseif (isset($data['api_key'])) {
    $providedKey = trim($data['api_key']);
}

if (empty($providedKey) || !hash_equals((string)$expectedKey, (string)$providedKey)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Invalid or missing API key']);
    exit;
}


// Required fields: imei, distance, waterLevel (or water_level), timestamp (or device_timestamp)
$imei = isset($data['imei']) ? trim($data['imei']) : null;
// If IMEI looks base64, try decode
if ($imei && base64_decode($imei, true) !== false && preg_match('/^[A-Za-z0-9+\/]+=*$/', $imei)) {
    $decoded = base64_decode($imei);
    if ($decoded !== false && preg_match('/^\d+$/', $decoded)) {
        $imei = $decoded;
    }
}

$distance = isset($data['distance']) ? $data['distance'] : null;
$waterLevel = isset($data['waterLevel']) ? $data['waterLevel'] : (isset($data['water_level']) ? $data['water_level'] : null);
$status = isset($data['status']) ? $data['status'] : null;
$deviceTs = isset($data['timestamp']) ? $data['timestamp'] : (isset($data['device_timestamp']) ? $data['device_timestamp'] : null);
$maxDepth = isset($data['maxDepth']) ? $data['maxDepth'] : (isset($data['max_depth']) ? $data['max_depth'] : null);

// Validate required fields
$missing = [];
if (empty($imei)) $missing[] = 'imei';
if ($distance === null || $distance === '') $missing[] = 'distance';
if ($waterLevel === null || $waterLevel === '') $missing[] = 'waterLevel';
if ($deviceTs === null || $deviceTs === '') $missing[] = 'timestamp';

if (!empty($missing)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing or empty fields', 'missing' => $missing]);
    exit;
}

$distance = floatval($distance);
$waterLevel = intval($waterLevel);
$deviceTs = intval($deviceTs);
$maxDepth = $maxDepth !== null && $maxDepth !== '' ? floatval($maxDepth) : null;

// If IMEI looks base64, try decode (after validation so empty IMEI rejected above)
if ($imei && base64_decode($imei, true) !== false && preg_match('/^[A-Za-z0-9+\/]+=*$/', $imei)) {
    $decoded = base64_decode($imei);
    if ($decoded !== false && preg_match('/^\d+$/', $decoded)) {
        $imei = $decoded;
    }
}

try {
    $stmt = $pdo->prepare('INSERT INTO readings (imei, distance, water_level, status, device_timestamp, max_depth) VALUES (:imei, :distance, :water_level, :status, :device_timestamp, :max_depth)');
    $stmt->execute([
        'imei' => $imei,
        'distance' => $distance,
        'water_level' => $waterLevel,
        'status' => $status,
        'device_timestamp' => $deviceTs,
        'max_depth' => $maxDepth,
    ]);
    $id = $pdo->lastInsertId();
    echo json_encode(['success' => true, 'id' => $id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
