<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../app/core/Database.php';

$pdo = Database::getInstance()->getConnection();

// Query parameters
$imei = isset($_GET['imei']) ? trim($_GET['imei']) : null;
$since = isset($_GET['since']) ? intval($_GET['since']) : null; 
$until = isset($_GET['until']) ? intval($_GET['until']) : null;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;
if ($limit <= 0 || $limit > 1000) $limit = 100;

$params = [];
$where = [];

if ($imei !== null && $imei !== '') {
    $where[] = 'imei = :imei';
    $params[':imei'] = $imei;
}

if ($since) {
    $where[] = 'device_timestamp >= :since';
    $params[':since'] = $since;
}
if ($until) {
    $where[] = 'device_timestamp <= :until';
    $params[':until'] = $until;
}

$sql = 'SELECT id, imei, distance, water_level, status, device_timestamp, max_depth, created_at FROM readings';
if (!empty($where)) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY created_at DESC, id DESC LIMIT :limit';

try {
    $stmt = $pdo->prepare($sql);
    // bind params
    foreach ($params as $k => $v) {
        if (is_int($v)) $stmt->bindValue($k, $v, PDO::PARAM_INT);
        else $stmt->bindValue($k, $v, PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // normalize timestamps
    foreach ($rows as &$r) {
        $dt = intval($r['device_timestamp']);
        if ($dt > 9999999999) {
            // milliseconds
            $dt = intval($dt / 1000);
        }
        $r['device_timestamp'] = $dt;

        $r['distance'] = $r['distance'] !== null ? floatval($r['distance']) : null;
        $r['water_level'] = $r['water_level'] !== null ? intval($r['water_level']) : null;
        $r['max_depth'] = $r['max_depth'] !== null ? floatval($r['max_depth']) : null;
    }

    echo json_encode(['success' => true, 'count' => count($rows), 'data' => $rows], JSON_UNESCAPED_SLASHES);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
