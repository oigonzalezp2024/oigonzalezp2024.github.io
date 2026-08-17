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

// Extracción y casteo estricto según la BD
$id_producto        = (int)($input['id_producto'] ?? 0);
$id_cliente         = (int)($input['id_cliente'] ?? 0);
$id_asesor          = (int)($input['id_asesor'] ?? 0);
$id_fabricante      = (int)($input['id_fabricante'] ?? 0);
$id_operario        = !empty($input['id_operario']) ? (int)$input['id_operario'] : null;
$unidades           = max(1, (int)($input['unidades'] ?? 1));
$fecha_pedido       = !empty($input['fecha_pedido']) ? $input['fecha_pedido'] : date('Y-m-d');
$fecha_entrega      = !empty($input['fecha_entrega']) ? $input['fecha_entrega'] : date('Y-m-d');
$porcentaje_utilidad = (float)($input['porcentaje_utilidad'] ?? 25.00);
$materialesLineas   = $input['materiales_lineas'] ?? [];

// Validar que las FK requeridas NOT NULL existan y haya insumos
if ($id_producto <= 0 || $id_cliente <= 0 || $id_asesor <= 0 || $id_fabricante <= 0 || empty($materialesLineas)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Datos incompletos: Cliente, Producto, Asesor, Fabricante y la lista de materiales son obligatorios.'
    ]);
    exit;
}

try {
    $pdo = getPDOConnection();
    
    // Consecutivo correlativo ORD-YYYY-XXXX
    $anioActual = date('Y');
    $prefijo = "ORD-{$anioActual}-";
    $stmtSeq = $pdo->prepare("SELECT numero_orden FROM ordenes_fabricacion WHERE numero_orden LIKE :prefijo ORDER BY id_orden DESC LIMIT 1");
    $stmtSeq->execute([':prefijo' => "{$prefijo}%"]);
    $ultimaOrden = $stmtSeq->fetchColumn();
    $siguiente = ($ultimaOrden && preg_match('/(\d+)$/', $ultimaOrden, $m)) ? ((int)$m[1] + 1) : 1;
    $numero_orden = $prefijo . str_pad((string)$siguiente, 4, '0', STR_PAD_LEFT);

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

    $stmtIns = $pdo->prepare("INSERT INTO ordenes_fabricacion 
        (numero_orden, cliente_id, asesor_id, fabricante_id, operario_id, producto_id, unidades, estado, fecha_pedido, fecha_entrega, costo_subtotal, porcentaje_utilidad) 
        VALUES (:num, :cli, :ase, :fab, :ope, :prod, :uni, 'planeacion', :f_ped, :f_ent, :sub, :util)");
    
    $stmtIns->execute([
        ':num'   => $numero_orden, 
        ':cli'   => $id_cliente, 
        ':ase'   => $id_asesor,
        ':fab'   => $id_fabricante, 
        ':ope'   => $id_operario, 
        ':prod'  => $id_producto,
        ':uni'   => $unidades, 
        ':f_ped' => $fecha_pedido, 
        ':f_ent' => $fecha_entrega,
        ':sub'   => $costo_subtotal, 
        ':util'  => $porcentaje_utilidad
    ]);
    
    $id_orden = (int)$pdo->lastInsertId();

    $stmtDet = $pdo->prepare("INSERT INTO orden_fabricacion_detalles 
        (orden_id, material_id, medidas, cantidad, valor_unitario) 
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
    echo json_encode(['success' => true, 'message' => "Orden {$numero_orden} registrada correctamente.", 'id_orden' => $id_orden]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
