<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);
$id_orden = (int)($input['id_orden'] ?? 0);

if ($id_orden <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

try {
    $pdo = getPDOConnection();
    $pdo->beginTransaction();
    $pdo->prepare("DELETE FROM orden_fabricacion_detalles WHERE orden_id = :id")->execute([':id' => $id_orden]);
    $pdo->prepare("DELETE FROM ordenes_fabricacion WHERE id_orden = :id")->execute([':id' => $id_orden]);
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Orden eliminada correctamente.']);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la orden por dependencias activas.']);
}
