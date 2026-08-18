<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);
$id_orden = (int)($input['id_orden'] ?? 0);
$nuevo_estado = $input['nuevo_estado'] ?? '';

$estadosPermitidos = ['simulacion', 'planeacion', 'activa', 'en pasillo', 'en ejecucion', 'suspendida', 'cancelada', 'terminada'];

if ($id_orden <= 0 || !in_array($nuevo_estado, $estadosPermitidos, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Estado o ID de orden no válido']);
    exit;
}

try {
    $pdo = getPDOConnection();
    $pdo->beginTransaction();

    $stmtCheck = $pdo->prepare("SELECT estado FROM ordenes_fabricacion WHERE id_orden = :id");
    $stmtCheck->execute([':id' => $id_orden]);
    $ordActual = $stmtCheck->fetch();

    $stmtUp = $pdo->prepare("UPDATE ordenes_fabricacion SET estado = :estado WHERE id_orden = :id");
    $stmtUp->execute([':estado' => $nuevo_estado, ':id' => $id_orden]);

    // Si cambia a activa y no lo estaba, realizar descuento de stock
    if ($nuevo_estado === 'activa' && $ordActual && $ordActual['estado'] !== 'activa') {
        $stmtDet = $pdo->prepare("SELECT id_material, cantidad FROM orden_fabricacion_detalles WHERE orden_id = :id");
        $stmtDet->execute([':id' => $id_orden]);
        $detalles = $stmtDet->fetchAll();

        $stmtInv = $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual - :cant WHERE id_material = :mat");
        $stmtMov = $pdo->prepare("INSERT INTO movimientos_inventario (tipo_item, id_item, tipo_movimiento, cantidad, orden_id, observacion) VALUES ('material', :mat, 'salida_orden', :cant, :id, 'Descuento por aprobación administrativa')");

        foreach ($detalles as $det) {
            $stmtInv->execute([':cant' => $det['cantidad'], ':mat' => $det['id_material']]);
            $stmtMov->execute([':mat' => $det['id_material'], ':cant' => $det['cantidad'], ':id' => $id_orden]);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => "Estado actualizado a {$nuevo_estado}"]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
