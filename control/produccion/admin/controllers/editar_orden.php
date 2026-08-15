<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$id_orden            = (int)($input['id_orden'] ?? 0);
$numero_orden        = trim($input['numero_orden'] ?? '');
$id_operario         = !empty($input['id_operario']) ? (int)$input['id_operario'] : null;
$unidades            = max(1, (int)($input['unidades'] ?? 1));
$fecha_entrega       = $input['fecha_entrega'] ?? date('Y-m-d');
$porcentaje_utilidad = (float)($input['porcentaje_utilidad'] ?? 0.00);
$materialesLineas    = $input['materiales_lineas'] ?? [];

if ($id_orden <= 0 || empty($numero_orden) || empty($materialesLineas)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos inválidos para la actualización.']);
    exit;
}

try {
    $pdo = getPDOConnection();
    $pdo->beginTransaction();

    $costo_subtotal = 0.00;
    $detalles = [];
    $stmtMat = $pdo->prepare("SELECT precio_unitario_defecto FROM materiales WHERE id_material = :id");

    foreach ($materialesLineas as $linea) {
        $idMat = (int)($linea['id_material'] ?? 0);
        $cantUnitaria = (float)($linea['cantidad'] ?? 0);
        $medidas = !empty($linea['medidas']) ? trim((string)$linea['medidas']) : null;

        if ($idMat > 0 && $cantUnitaria > 0) {
            $stmtMat->execute([':id' => $idMat]);
            $costoUnit = (float)$stmtMat->fetchColumn();

            $cantidadTotal = $cantUnitaria * $unidades;
            $costo_subtotal += ($cantidadTotal * $costoUnit);

            $detalles[] = [
                'id_material'    => $idMat,
                'cantidad'       => $cantidadTotal,
                'valor_unitario' => $costoUnit,
                'medidas'        => $medidas
            ];
        }
    }

    $costo_subtotal = round($costo_subtotal, 2);

    $stmtUp = $pdo->prepare("UPDATE ordenes_fabricacion SET 
        numero_orden = :num, id_operario = :ope, unidades = :uni, 
        fecha_entrega = :f_ent, costo_subtotal = :sub, porcentaje_utilidad = :util 
        WHERE id_orden = :id");
    
    $stmtUp->execute([
        ':num'   => $numero_orden, 
        ':ope'   => $id_operario, 
        ':uni'   => $unidades,
        ':f_ent' => $fecha_entrega, 
        ':sub'   => $costo_subtotal, 
        ':util'  => $porcentaje_utilidad, 
        ':id'    => $id_orden
    ]);

    $pdo->prepare("DELETE FROM orden_fabricacion_detalles WHERE id_orden = :id")->execute([':id' => $id_orden]);

    $stmtDet = $pdo->prepare("INSERT INTO orden_fabricacion_detalles 
        (id_orden, id_material, medidas, cantidad, valor_unitario) 
        VALUES (:id_orden, :id_mat, :medidas, :cant, :val)");

    foreach ($detalles as $det) {
        $stmtDet->execute([
            ':id_orden' => $id_orden,
            ':id_mat'   => $det['id_material'],
            ':medidas'  => $det['medidas'],
            ':cant'     => $det['cantidad'],
            ':val'      => $det['valor_unitario']
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Orden de fabricación actualizada correctamente.']);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()]);
}
