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

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$status = isset($_GET['status']) ? trim($_GET['status']) : null; 
$q = isset($_GET['q']) ? trim($_GET['q']) : null;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;
if ($limit <= 0 || $limit > 1000) $limit = 100;

$where = [];
$params = [];

if ($id) {
    $where[] = 'id = :id';
    $params[':id'] = $id;
}

if ($status && in_array($status, ['active','inactive'])) {
    $where[] = 'status = :status';
    $params[':status'] = $status;
}

if ($q !== null && $q !== '') {
    $where[] = '(name LIKE :q OR address LIKE :q)';
    $params[':q'] = '%' . $q . '%';
}

$sql = 'SELECT id, name, address, status FROM evacuation_centers';
if (!empty($where)) $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY id ASC LIMIT :limit';

try {
    $stmt = $pdo->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'count' => count($rows), 'data' => $rows], JSON_UNESCAPED_SLASHES);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
