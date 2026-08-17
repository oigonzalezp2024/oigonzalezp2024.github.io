<?php
declare(strict_types=1);

require_once(__DIR__ . '/PDFOrdenTaller.php');

$idOrden = filter_input(INPUT_GET, 'id_orden', FILTER_VALIDATE_INT);

if (!$idOrden) {
    http_response_code(400);
    echo "ID de orden no válido o ausente.";
    exit;
}

$dbHost = '127.0.0.1';
$dbName = 'control_fabricacion';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $stmtEnc = $pdo->prepare("
        SELECT 
            o.numero_orden, o.unidades, o.estado, o.fecha_pedido, o.fecha_entrega,
            p.codigo_referencia AS cod_producto, p.nombre_producto,
            t_ope.nombre AS nombre_operario
        FROM ordenes_fabricacion o
        JOIN productos_catalogo p ON o.id_producto = p.id_producto
        LEFT JOIN personas t_ope ON o.id_operario = t_ope.id_persona
        WHERE o.id_orden = :id_orden
    ");
    $stmtEnc->execute([':id_orden' => $idOrden]);
    $encabezado = $stmtEnc->fetch();

    if (!$encabezado) {
        http_response_code(404);
        echo "Orden de fabricación no encontrada.";
        exit;
    }

    $stmtDet = $pdo->prepare("
        SELECT 
            d.id_detalle, d.medidas, d.cantidad AS cantidad_estimada, d.cantidad_consumida, d.es_destacado,
            m.codigo_material, m.descripcion_material, m.unidad_medida
        FROM orden_fabricacion_detalles d
        JOIN materiales m ON d.id_material = m.id_material
        WHERE d.id_orden = :id_orden
        ORDER BY d.id_detalle ASC
    ");
    $stmtDet->execute([':id_orden' => $idOrden]);
    $detalles = $stmtDet->fetchAll();

    // Construcción de la URL del formulario dinámicamente
    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $dir = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $formUrl = "{$scheme}://{$host}{$dir}/registrar_consumo.php?id_orden={$idOrden}";

    $pdf = new PDFOrdenTaller();
    $pdf->render(['encabezado' => $encabezado, 'detalles' => $detalles], $formUrl, 'I');

} catch (Exception $e) {
    http_response_code(500);
    echo "Error interno al generar PDF: " . $e->getMessage();
}
