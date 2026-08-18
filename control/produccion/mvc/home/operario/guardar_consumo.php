<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die("Método HTTP no permitido.");
}

$idOrden = filter_input(INPUT_POST, 'id_orden', FILTER_VALIDATE_INT);
$cantidades = $_POST['cant_consumida'] ?? [];

if (!$idOrden || !is_array($cantidades)) {
    die("Error: Parámetros malformados o incompletos.");
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

    $pdo->beginTransaction();

    $stmtUpdate = $pdo->prepare("
        UPDATE orden_fabricacion_detalles 
        SET cantidad_consumida = :cant
        WHERE id_detalle = :id_detalle AND orden_id = :id_orden
    ");

    foreach ($cantidades as $idDetalle => $cantRaw) {
        $idDetalleClean = filter_var($idDetalle, FILTER_VALIDATE_INT);
        $cantClean = filter_var($cantRaw, FILTER_VALIDATE_FLOAT);

        if ($idDetalleClean !== false && $cantClean !== false) {
            $stmtUpdate->execute([
                ':cant'       => $cantClean,
                ':id_detalle' => $idDetalleClean,
                ':id_orden'   => $idOrden
            ]);
        }
    }

    $pdo->commit();

    // Redirección directa e inmediata al PDF de la orden
    header("Location: ver_pdf_taller.php?id_orden=" . $idOrden);
    exit;

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo "Error interno al actualizar datos: " . $e->getMessage();
}
