<?php
declare(strict_types=1);

$dbHost = '127.0.0.1';
$dbName = 'control_fabricacion';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

/**
 * Genera un código de orden consecutivo y automático desde el código fuente.
 * Patrón estándar: ORD-[AÑO]-[SECUENCIA_PADDED] (Ej: ORD-2026-0001)
 */
function generarConsecutivoOrden(PDO $pdo): string {
    $anioActual = date('Y');
    $prefijo = "ORD-{$anioActual}-";

    try {
        $stmt = $pdo->prepare("SELECT numero_orden FROM ordenes_fabricacion WHERE numero_orden LIKE :prefijo ORDER BY id_orden DESC LIMIT 1");
        $stmt->execute([':prefijo' => "{$prefijo}%"]);
        $ultimaOrden = $stmt->fetchColumn();

        if ($ultimaOrden && preg_match('/(\d+)$/', $ultimaOrden, $matches)) {
            $siguienteNumero = (int)$matches[1] + 1;
        } else {
            $siguienteNumero = 1;
        }

        return $prefijo . str_pad((string)$siguienteNumero, 4, '0', STR_PAD_LEFT);
    } catch (Exception $e) {
        return $prefijo . str_pad((string)random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    }
}

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'crear') {
        $numero_orden = generarConsecutivoOrden($pdo);
        $id_producto = (int)($_POST['id_producto'] ?? 0);
        $id_cliente = (int)($_POST['id_cliente'] ?? 0);
        $id_asesor = (int)($_POST['id_asesor'] ?? 0);
        $id_fabricante = (int)($_POST['id_fabricante'] ?? 0);
        $id_operario = !empty($_POST['id_operario']) ? (int)$_POST['id_operario'] : null;
        $unidades = (int)($_POST['unidades'] ?? 1);
        $fecha_pedido = $_POST['fecha_pedido'] ?? date('Y-m-d');
        $fecha_entrega = $_POST['fecha_entrega'] ?? date('Y-m-d');
        $porcentaje_utilidad = (float)($_POST['porcentaje_utilidad'] ?? 25.00);

        // Materiales dinámicos enviados en formato array de filas [index => ['id_material' => x, 'cantidad' => y]]
        $materialesLineas = $_POST['materiales_lineas'] ?? [];

        if ($id_producto > 0 && $id_cliente > 0) {
            $pdo->beginTransaction();
            try {
                $costo_subtotal = 0.00;
                $detallesParaInsertar = [];

                foreach ($materialesLineas as $linea) {
                    $idMat = (int)($linea['id_material'] ?? 0);
                    $cantUnitaria = (float)($linea['cantidad'] ?? 0);

                    if ($idMat > 0 && $cantUnitaria > 0) {
                        $stmtMat = $pdo->prepare("SELECT precio_unitario_defecto FROM materiales WHERE id_material = :id");
                        $stmtMat->execute([':id' => $idMat]);
                        $costoUnit = (float)$stmtMat->fetchColumn();

                        $cantidadTotalMaterial = $cantUnitaria * max(1, $unidades);
                        $costo_subtotal += ($cantidadTotalMaterial * $costoUnit);

                        $detallesParaInsertar[] = [
                            'id_material' => $idMat,
                            'cantidad' => $cantidadTotalMaterial,
                            'valor_unitario' => $costoUnit
                        ];
                    }
                }

                $costo_subtotal = round($costo_subtotal, 2);

                $stmt = $pdo->prepare("INSERT INTO ordenes_fabricacion (numero_orden, id_cliente, id_asesor, id_fabricante, id_operario, id_producto, unidades, estado, fecha_pedido, fecha_entrega, costo_subtotal, porcentaje_utilidad) VALUES (:num, :cli, :ase, :fab, :ope, :prod, :uni, 'planeacion', :f_ped, :f_ent, :sub, :util)");
                $stmt->execute([
                    ':num' => $numero_orden, ':cli' => $id_cliente, ':ase' => $id_asesor,
                    ':fab' => $id_fabricante, ':ope' => $id_operario, ':prod' => $id_producto,
                    ':uni' => $unidades, ':f_ped' => $fecha_pedido, ':f_ent' => $fecha_entrega,
                    ':sub' => $costo_subtotal, ':util' => $porcentaje_utilidad
                ]);
                $id_orden_creada = (int)$pdo->lastInsertId();

                if (!empty($detallesParaInsertar)) {
                    $stmtDet = $pdo->prepare("INSERT INTO orden_fabricacion_detalles (id_orden, id_material, cantidad, valor_unitario) VALUES (:id_orden, :id_mat, :cant, :val)");
                    foreach ($detallesParaInsertar as $det) {
                        $stmtDet->execute([
                            ':id_orden' => $id_orden_creada,
                            ':id_mat' => $det['id_material'],
                            ':cant' => $det['cantidad'],
                            ':val' => $det['valor_unitario']
                        ]);
                    }
                }

                $pdo->commit();
                $mensaje = "Orden <strong>{$numero_orden}</strong> registrada exitosamente con asignación dinámica de materiales y costo subtotal de <strong>$" . number_format($costo_subtotal, 2) . "</strong>.";
            } catch (Exception $ex) {
                $pdo->rollBack();
                $error = "Error al registrar la orden y sus insumos: " . $ex->getMessage();
            }
        } else {
            $error = "Complete los campos obligatorios para registrar la orden.";
        }
    }

    if ($accion === 'editar') {
        $id_orden = (int)($_POST['id_orden'] ?? 0);
        $numero_orden = trim($_POST['numero_orden'] ?? '');
        $id_operario = !empty($_POST['id_operario']) ? (int)$_POST['id_operario'] : null;
        $unidades = (int)($_POST['unidades'] ?? 1);
        $fecha_entrega = $_POST['fecha_entrega'] ?? date('Y-m-d');
        $porcentaje_utilidad = (float)($_POST['porcentaje_utilidad'] ?? 0.00);
        $materialesLineas = $_POST['materiales_lineas'] ?? [];

        if ($id_orden > 0) {
            $pdo->beginTransaction();
            try {
                $costo_subtotal = 0.00;
                $detallesParaActualizar = [];

                foreach ($materialesLineas as $linea) {
                    $idMat = (int)($linea['id_material'] ?? 0);
                    $cantUnitaria = (float)($linea['cantidad'] ?? 0);

                    if ($idMat > 0 && $cantUnitaria > 0) {
                        $stmtMat = $pdo->prepare("SELECT precio_unitario_defecto FROM materiales WHERE id_material = :id");
                        $stmtMat->execute([':id' => $idMat]);
                        $costoUnit = (float)$stmtMat->fetchColumn();

                        $cantidadTotalMaterial = $cantUnitaria * max(1, $unidades);
                        $costo_subtotal += ($cantidadTotalMaterial * $costoUnit);

                        $detallesParaActualizar[] = [
                            'id_material' => $idMat,
                            'cantidad' => $cantidadTotalMaterial,
                            'valor_unitario' => $costoUnit
                        ];
                    }
                }

                // Blindaje crítico: Si no vienen materiales válidos, evitamos vaciar la tabla hija por error
                if (empty($detallesParaActualizar)) {
                    throw new Exception("Debe asignar al menos un material válido para actualizar la orden.");
                }

                $costo_subtotal = round($costo_subtotal, 2);

                $stmt = $pdo->prepare("UPDATE ordenes_fabricacion SET numero_orden = :num, id_operario = :ope, unidades = :uni, fecha_entrega = :f_ent, costo_subtotal = :sub, porcentaje_utilidad = :util WHERE id_orden = :id");
                $stmt->execute([
                    ':num' => $numero_orden, ':ope' => $id_operario, ':uni' => $unidades,
                    ':f_ent' => $fecha_entrega, ':sub' => $costo_subtotal, ':util' => $porcentaje_utilidad, ':id' => $id_orden
                ]);

                // Limpiamos y recreamos solo si tenemos la certeza de que hay datos nuevos correctos
                $pdo->prepare("DELETE FROM orden_fabricacion_detalles WHERE id_orden = :id")->execute([':id' => $id_orden]);
                
                $stmtDet = $pdo->prepare("INSERT INTO orden_fabricacion_detalles (id_orden, id_material, cantidad, valor_unitario) VALUES (:id_orden, :id_mat, :cant, :val)");
                foreach ($detallesParaActualizar as $det) {
                    $stmtDet->execute([
                        ':id_orden' => $id_orden,
                        ':id_mat' => $det['id_material'],
                        ':cant' => $det['cantidad'],
                        ':val' => $det['valor_unitario']
                    ]);
                }

                $pdo->commit();
                $mensaje = "Orden actualizada exitosamente con sus nuevos materiales y costos.";
            } catch (Exception $ex) {
                $pdo->rollBack();
                $error = "Error al actualizar: " . $ex->getMessage();
            }
        }
    }

    if ($accion === 'cambiar_estado') {
        $id_orden = (int)($_POST['id_orden'] ?? 0);
        $nuevo_estado = $_POST['nuevo_estado'] ?? '';
        $estadosPermitidos = ['simulacion', 'planeacion', 'activa', 'en pasillo', 'en ejecucion', 'suspendida', 'cancelada', 'terminada'];
        
        if ($id_orden > 0 && in_array($nuevo_estado, $estadosPermitidos, true)) {
            $pdo->beginTransaction();
            try {
                $stmtCheck = $pdo->prepare("SELECT estado FROM ordenes_fabricacion WHERE id_orden = :id");
                $stmtCheck->execute([':id' => $id_orden]);
                $ordActual = $stmtCheck->fetch();

                $stmtUp = $pdo->prepare("UPDATE ordenes_fabricacion SET estado = :estado WHERE id_orden = :id");
                $stmtUp->execute([':estado' => $nuevo_estado, ':id' => $id_orden]);

                if ($nuevo_estado === 'activa' && $ordActual && $ordActual['estado'] !== 'activa') {
                    $stmtDet = $pdo->prepare("SELECT id_material, cantidad FROM orden_fabricacion_detalles WHERE id_orden = :id");
                    $stmtDet->execute([':id' => $id_orden]);
                    $detalles = $stmtDet->fetchAll();

                    foreach ($detalles as $det) {
                        $stmtInv = $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual - :cant WHERE id_material = :mat");
                        $stmtInv->execute([':cant' => $det['cantidad'], ':mat' => $det['id_material']]);

                        $stmtMov = $pdo->prepare("INSERT INTO movimientos_inventario (tipo_item, id_item, tipo_movimiento, cantidad, id_orden, observacion) VALUES ('material', :mat, 'salida_orden', :cant, :id, 'Descuento por aprobación administrativa')");
                        $stmtMov->execute([':mat' => $det['id_material'], ':cant' => $det['cantidad'], ':id' => $id_orden]);
                    }
                }

                $pdo->commit();
                $mensaje = "Estado actualizado correctamente a: <strong>{$nuevo_estado}</strong>";
            } catch (Exception $ex) {
                $pdo->rollBack();
                $error = "Error al cambiar estado: " . $ex->getMessage();
            }
        }
    }

    if ($accion === 'eliminar') {
        $id_orden = (int)($_POST['id_orden'] ?? 0);
        if ($id_orden > 0) {
            $pdo->beginTransaction();
            try {
                $pdo->prepare("DELETE FROM orden_fabricacion_detalles WHERE id_orden = :id")->execute([':id' => $id_orden]);
                $pdo->prepare("DELETE FROM ordenes_fabricacion WHERE id_orden = :id")->execute([':id' => $id_orden]);
                $pdo->commit();
                $mensaje = "Orden eliminada correctamente del sistema.";
            } catch (Exception $ex) {
                $pdo->rollBack();
                $error = "No se puede eliminar la orden porque posee dependencias activas.";
            }
        }
    }
}

$paginaActual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$porPagina = 6;
$offset = ($paginaActual - 1) * $porPagina;

$totalOrdenes = (int)$pdo->query("SELECT COUNT(*) FROM ordenes_fabricacion")->fetchColumn();
$totalPaginas = ceil($totalOrdenes / $porPagina);

$sqlOrdenes = "
    SELECT 
        o.*,
        p.nombre_producto,
        c.nombre AS cliente_nombre,
        op.nombre AS operario_nombre,
        asesor.nombre AS asesor_nombre,
        fab.nombre AS fabricante_nombre
    FROM ordenes_fabricacion o
    INNER JOIN productos_catalogo p ON o.id_producto = p.id_producto
    INNER JOIN terceros c ON o.id_cliente = c.id_tercero
    INNER JOIN terceros asesor ON o.id_asesor = asesor.id_tercero
    INNER JOIN terceros fab ON o.id_fabricante = fab.id_tercero
    LEFT JOIN terceros op ON o.id_operario = op.id_tercero
    ORDER BY o.id_orden DESC
    LIMIT :limit OFFSET :offset
";
$stmtOrdenes = $pdo->prepare($sqlOrdenes);
$stmtOrdenes->bindValue(':limit', $porPagina, PDO::PARAM_INT);
$stmtOrdenes->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtOrdenes->execute();
$ordenes = $stmtOrdenes->fetchAll();

$productos = $pdo->query("SELECT id_producto, nombre_producto FROM productos_catalogo")->fetchAll();
$clientes = $pdo->query("SELECT id_tercero, nombre FROM terceros WHERE rol = 'CLIENTE'")->fetchAll();
$asesores = $pdo->query("SELECT id_tercero, nombre FROM terceros WHERE rol = 'ASESOR'")->fetchAll();
$fabricantes = $pdo->query("SELECT id_tercero, nombre FROM terceros WHERE rol = 'FABRICANTE'")->fetchAll();
$operarios = $pdo->query("SELECT id_tercero, nombre FROM terceros WHERE rol = 'OPERARIO'")->fetchAll();
$materialesDisponibles = $pdo->query("SELECT id_material, descripcion_material AS nombre, precio_unitario_defecto AS costo, unidad_medida FROM materiales ORDER BY descripcion_material ASC")->fetchAll();

$sugerenciaConsecutivo = generarConsecutivoOrden($pdo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Producción | Control Industrial</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0f172a;
            --primary-accent: #2563eb;
            --success: #059669;
            --warning: #d97706;
            --danger: #dc2626;
            --bg-body: #f1f5f9;
            --surface: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #cbd5e1;
            --border-light: #e2e8f0;
            --radius: 12px;
            --shadow-card: 0 10px 15px -3px rgba(15, 23, 42, 0.05), 0 4px 6px -4px rgba(15, 23, 42, 0.05);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-main); min-height: 100vh; padding: 2.5rem 1.5rem; display: flex; justify-content: center; align-items: flex-start; }

        .app-container { width: 100%; max-width: 1440px; margin: 0 auto; }

        .dashboard-card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border-light);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .dashboard-header {
            padding: 2rem 2.5rem;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        @media(min-width: 768px) {
            .dashboard-header { flex-direction: row; justify-content: space-between; align-items: center; }
        }
        .header-title h1 { font-size: 1.35rem; font-weight: 700; letter-spacing: -0.01em; display: flex; align-items: center; gap: 0.75rem; }
        .header-title p { font-size: 0.85rem; color: #94a3b8; margin-top: 0.35rem; font-weight: 400; }

        .alert-wrapper { padding: 1.5rem 2.5rem 0 2.5rem; }
        .notification { padding: 1rem 1.25rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 0.75rem; border: 1px solid transparent; }
        .notification-success { background-color: #f0fdf4; color: #166534; border-color: #bbf7d0; }
        .notification-error { background-color: #fef2f2; color: #991b1b; border-color: #fecaca; }

        .dashboard-body { padding: 2.5rem; }
        .table-wrapper { width: 100%; overflow-x: auto; border: 1px solid var(--border-light); border-radius: 10px; background: white; }
        
        table { width: 100%; border-collapse: collapse; min-width: 950px; text-align: left; }
        th { background-color: #f8fafc; color: var(--text-muted); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--border-light); white-space: nowrap; }
        td { padding: 1.25rem; border-bottom: 1px solid var(--border-light); font-size: 0.875rem; color: var(--text-main); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #f8fafc; transition: background-color 0.1s ease; }

        .text-center { text-align: center; }
        .font-bold { font-weight: 600; }
        .sub-text { color: var(--text-muted); font-size: 0.75rem; display: block; margin-top: 0.2rem; }

        .status-badge { padding: 0.3rem 0.75rem; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 0.35rem; }
        .badge-planeacion { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-activa { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-en-ejecucion { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        .badge-terminada { background: #ccfbf1; color: #115e59; border: 1px solid #99f6e4; }
        .badge-cancelada, .badge-suspendida { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        .btn { padding: 0.6rem 1.25rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.15s ease; text-decoration: none; }
        .btn-primary { background-color: var(--primary-accent); color: white; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
        .btn-primary:hover { background-color: #1d4ed8; }
        .btn-success { background-color: var(--success); color: white; }
        .btn-success:hover { background-color: #047857; }
        .btn-action-warning { background-color: #f59e0b; color: white; padding: 0.45rem 0.65rem; border-radius: 6px; font-size: 0.75rem; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
        .btn-action-info { background-color: #0284c7; color: white; padding: 0.45rem 0.65rem; border-radius: 6px; font-size: 0.75rem; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
        .btn-action-danger { background-color: var(--danger); color: white; padding: 0.45rem 0.65rem; border-radius: 6px; font-size: 0.75rem; border: none; cursor: pointer; }
        .btn-action-warning:hover, .btn-action-info:hover, .btn-action-danger:hover { opacity: 0.9; }
        .btn-secondary { background-color: #e2e8f0; color: #334155; }
        .btn-secondary:hover { background-color: #cbd5e1; }

        .row-actions { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }

        .pagination-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--border-light); flex-wrap: wrap; gap: 1rem; }
        .pagination-meta { font-size: 0.85rem; color: var(--text-muted); }
        .pagination-links { display: flex; gap: 0.35rem; }
        .pagination-links a, .pagination-links span { padding: 0.45rem 0.85rem; border: 1px solid var(--border-light); border-radius: 6px; font-size: 0.85rem; text-decoration: none; color: #334155; background: white; font-weight: 600; transition: all 0.15s; }
        .pagination-links a:hover { background: #f8fafc; border-color: var(--border); }
        .pagination-links .active { background: var(--primary); color: white; border-color: var(--primary); }

        .modal-overlay {
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px);
            display: none; justify-content: center; align-items: center; z-index: 1000; padding: 1.5rem;
            animation: fadeIn 0.15s ease-out forwards;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .modal-box {
            background: var(--surface); width: 100%; max-width: 900px; max-height: 90vh;
            border-radius: var(--radius); box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
            border: 1px solid var(--border-light); display: flex; flex-direction: column; overflow: hidden;
            animation: slideUp 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes slideUp { from { transform: translateY(15px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .modal-top { padding: 1.25rem 1.75rem; background: #f8fafc; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; }
        .modal-top h3 { font-size: 1.05rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 0.5rem; }
        .modal-dismiss { background: transparent; border: none; font-size: 1.25rem; color: var(--text-muted); cursor: pointer; padding: 0.25rem; border-radius: 4px; display: flex; align-items: center; justify-content: center; }
        .modal-dismiss:hover { background: #e2e8f0; color: var(--text-main); }
        
        /* Contenido con Scroll fluido vertical */
        .modal-content { 
            padding: 2rem 1.75rem; 
            overflow-y: auto; 
            flex-grow: 1; 
            max-height: calc(90vh - 130px); 
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
        }
        .modal-content::-webkit-scrollbar { width: 6px; }
        .modal-content::-webkit-scrollbar-track { background: transparent; }
        .modal-content::-webkit-scrollbar-thumb { background-color: var(--border); border-radius: 3px; }

        .modal-bottom { padding: 1rem 1.75rem; background: #f8fafc; border-top: 1px solid var(--border-light); display: flex; justify-content: flex-end; gap: 0.75rem; flex-shrink: 0; }

        .form-grid-layout { display: grid; grid-template-columns: 1fr; gap: 1.25rem; }
        @media(min-width: 640px) { .form-grid-layout { grid-template-columns: repeat(2, 1fr); } }

        .form-field-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .form-field-group.full-width { grid-column: 1 / -1; }
        .form-field-group label { font-size: 0.7rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; }
        .input-control { padding: 0.65rem 0.85rem; border: 1px solid var(--border); border-radius: 8px; font-size: 0.875rem; background: white; color: var(--text-main); transition: all 0.15s ease; width: 100%; }
        .input-control:focus { outline: none; border-color: var(--primary-accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
        .input-control:disabled { background-color: #f8fafc; color: var(--text-muted); cursor: not-allowed; }

        /* Constructor Dinámico de Materiales ilimitados */
        .materials-container { display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem; }
        .material-item-row { display: flex; gap: 0.75rem; align-items: center; background: #f8fafc; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-light); }
        .material-item-row select { flex: 2; }
        .material-item-row input { flex: 1; }
        .btn-remove-material { background-color: var(--danger); color: white; border: none; padding: 0.6rem 0.75rem; border-radius: 6px; cursor: pointer; font-weight: bold; flex-shrink: 0; }
        .btn-remove-material:hover { opacity: 0.85; }
    </style>
</head>
<body>

<div class="app-container">
    <div class="dashboard-card">
        <div class="dashboard-header">
            <div class="header-title">
                <h1>🛠️ Módulo de Producción y Control Industrial</h1>
                <p>Gestión centralizada de órdenes de fabricación, asignación dinámica de materiales y trazabilidad de insumos</p>
            </div>
            <button type="button" class="btn btn-primary" onclick="abrirModal('modalCrear')">
                ➕ Sugerir / Registrar Orden
            </button>
        </div>

        <?php if (!empty($mensaje) || !empty($error)): ?>
            <div class="alert-wrapper">
                <?php if (!empty($mensaje)): ?>
                    <div class="notification notification-success">✔️ <?= $mensaje ?></div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="notification notification-error">⚠️ <?= $error ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="dashboard-body">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nº Orden</th>
                            <th>Producto / Cliente</th>
                            <th>Responsable Material</th>
                            <th class="text-center">Estado Operativo</th>
                            <th class="text-center">Cronograma</th>
                            <th>Acciones y Flujo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ordenes)): ?>
                            <tr>
                                <td colspan="6" class="text-center" style="padding: 3rem; color: var(--text-muted);">
                                    No se registran órdenes de fabricación activas en este período.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ordenes as $ord): ?>
                                <?php
                                    $stmtDetOrd = $pdo->prepare("
                                        SELECT d.cantidad, m.id_material, m.descripcion_material AS nombre, m.unidad_medida 
                                        FROM orden_fabricacion_detalles d 
                                        INNER JOIN materiales m ON d.id_material = m.id_material 
                                        WHERE d.id_orden = :id
                                    ");
                                    $stmtDetOrd->execute([':id' => $ord['id_orden']]);
                                    $materialesAsignadosOrden = $stmtDetOrd->fetchAll();
                                    
                                    $unidadesOrden = max(1, (int)$ord['unidades']);
                                ?>
                                <tr>
                                    <td><span class="font-bold"><?= htmlspecialchars($ord['numero_orden']) ?></span></td>
                                    <td>
                                        <?= htmlspecialchars($ord['nombre_producto']) ?>
                                        <span class="sub-text">Cliente: <?= htmlspecialchars($ord['cliente_nombre']) ?></span>
                                    </td>
                                    <td>👤 <?= htmlspecialchars($ord['operario_nombre'] ?? 'Sin asignar') ?></td>
                                    <td class="text-center">
                                        <?php 
                                            $badgeClass = 'badge-planeacion';
                                            $est = $ord['estado'];
                                            if ($est === 'activa') $badgeClass = 'badge-activa';
                                            elseif ($est === 'en ejecucion') $badgeClass = 'badge-en-ejecucion';
                                            elseif ($est === 'terminada') $badgeClass = 'badge-terminada';
                                            elseif ($est === 'cancelada' || $est === 'suspendida') $badgeClass = 'badge-cancelada';
                                        ?>
                                        <span class="status-badge <?= $badgeClass ?>"><?= htmlspecialchars($est) ?></span>
                                    </td>
                                    <td class="text-center" style="font-size: 0.8rem;">
                                        <?= htmlspecialchars($ord['fecha_pedido']) ?>
                                        <span class="sub-text">Entrega: <?= htmlspecialchars($ord['fecha_entrega']) ?></span>
                                    </td>
                                    <td>
                                        <div class="row-actions">
                                            <form method="POST" style="display:inline-flex; align-items:center;">
                                                <input type="hidden" name="id_orden" value="<?= $ord['id_orden'] ?>">
                                                <input type="hidden" name="accion" value="cambiar_estado">
                                                <select name="nuevo_estado" class="input-control" style="padding: 0.4rem 0.5rem; font-size: 0.75rem; width: auto;" onchange="this.form.submit()" title="Modificar estado del flujo">
                                                    <option value="planeacion" <?= $est==='planeacion'?'selected':'' ?>>Planeación</option>
                                                    <option value="activa" <?= $est==='activa'?'selected':'' ?>>Aprobar / Activa</option>
                                                    <option value="en ejecucion" <?= $est==='en ejecucion'?'selected':'' ?>>En Ejecución</option>
                                                    <option value="suspendida" <?= $est==='suspendida'?'selected':'' ?>>Suspendida</option>
                                                    <option value="cancelada" <?= $est==='cancelada'?'selected':'' ?>>Cancelada</option>
                                                    <option value="terminada" <?= $est==='terminada'?'selected':'' ?>>Terminada</option>
                                                </select>
                                            </form>

                                            <a href="../control_costos/ver_costos_orden.php?id_orden=<?= $ord['id_orden'] ?>" class="btn-action-info" title="Análisis de Costos">📊</a>

                                            <button type="button" class="btn-action-warning" onclick="abrirModal('modalEditar-<?= $ord['id_orden'] ?>')" title="Editar detalles e insumos">✏️</button>

                                            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Confirma la eliminación permanente de esta orden de fabricación?');">
                                                <input type="hidden" name="id_orden" value="<?= $ord['id_orden'] ?>">
                                                <input type="hidden" name="accion" value="eliminar">
                                                <button type="submit" class="btn-action-danger" title="Eliminar registro">🗑️</button>
                                            </form>
                                        </div>

                                        <!-- Modal de Edición Individual -->
                                        <div id="modalEditar-<?= $ord['id_orden'] ?>" class="modal-overlay">
                                            <div class="modal-box">
                                                <div class="modal-top">
                                                    <h3>✏️ Modificar Orden e Insumos: <?= htmlspecialchars($ord['numero_orden']) ?></h3>
                                                    <button type="button" class="modal-dismiss" onclick="cerrarModal('modalEditar-<?= $ord['id_orden'] ?>')">&times;</button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-content">
                                                        <input type="hidden" name="accion" value="editar">
                                                        <input type="hidden" name="id_orden" value="<?= $ord['id_orden'] ?>">
                                                        <div class="form-grid-layout">
                                                            <div class="form-field-group">
                                                                <label>Nº de Orden (Consecutivo)</label>
                                                                <input type="text" name="numero_orden" class="input-control" value="<?= htmlspecialchars($ord['numero_orden']) ?>" required>
                                                            </div>
                                                            <div class="form-field-group">
                                                                <label>Responsable Material</label>
                                                                <select name="id_operario" class="input-control">
                                                                    <option value="">Sin asignar</option>
                                                                    <?php foreach ($operarios as $op): ?>
                                                                        <option value="<?= $op['id_tercero'] ?>" <?= $ord['id_operario'] == $op['id_tercero'] ? 'selected' : '' ?>><?= htmlspecialchars($op['nombre']) ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-field-group">
                                                                <label>Fecha de Entrega</label>
                                                                <input type="date" name="fecha_entrega" class="input-control" value="<?= $ord['fecha_entrega'] ?>" required>
                                                            </div>
                                                            <div class="form-field-group">
                                                                <label>Unidades</label>
                                                                <input type="number" name="unidades" class="input-control" value="<?= $ord['unidades'] ?>" min="1" required>
                                                            </div>
                                                            <div class="form-field-group">
                                                                <label>% Utilidad</label>
                                                                <input type="number" step="0.01" name="porcentaje_utilidad" class="input-control" value="<?= $ord['porcentaje_utilidad'] ?>" required>
                                                            </div>
                                                            <div class="form-field-group full-width">
                                                                <label>Asignación de Materiales (Ilimitados)</label>
                                                                <div id="container-edit-<?= $ord['id_orden'] ?>" class="materials-container">
                                                                    <?php if (!empty($materialesAsignadosOrden)): ?>
                                                                        <?php foreach ($materialesAsignadosOrden as $mao): ?>
                                                                            <div class="material-item-row">
                                                                                <select name="materiales_lineas[][id_material]" class="input-control" required>
                                                                                    <option value="">Seleccione material...</option>
                                                                                    <?php foreach ($materialesDisponibles as $mat): ?>
                                                                                        <option value="<?= $mat['id_material'] ?>" <?= $mat['id_material'] == $mao['id_material'] ? 'selected' : '' ?>>
                                                                                            <?= htmlspecialchars($mat['nombre']) ?> ($<?= number_format((float)$mat['costo'], 2) ?> / <?= $mat['unidad_medida'] ?>)
                                                                                        </option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                                <input type="number" step="0.0001" min="0.0001" name="materiales_lineas[][cantidad]" class="input-control" value="<?= $mao['cantidad'] / $unidadesOrden ?>" placeholder="Cantidad x unidad" required>
                                                                                <button type="button" class="btn-remove-material" onclick="this.closest('.material-item-row').remove()">×</button>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <button type="button" class="btn btn-secondary" style="margin-top: 0.75rem;" onclick="agregarFilaMaterial('container-edit-<?= $ord['id_orden'] ?>')">➕ Agregar otro material</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-bottom">
                                                        <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar-<?= $ord['id_orden'] ?>')">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPaginas > 1): ?>
                <div class="pagination-footer">
                    <div class="pagination-meta">
                        Página <strong><?= $paginaActual ?></strong> de <strong><?= $totalPaginas ?></strong>
                    </div>
                    <div class="pagination-links">
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <?php if ($i === $paginaActual): ?>
                                <span class="active"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?pagina=<?= $i ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Principal para Registrar Nueva Orden -->
<div id="modalCrear" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-top">
            <h3>➕ Sugerir / Registrar Nueva Orden de Producción e Insumos</h3>
            <button type="button" class="modal-dismiss" onclick="cerrarModal('modalCrear')">&times;</button>
        </div>
        <form method="POST">
            <div class="modal-content">
                <input type="hidden" name="accion" value="crear">
                <div class="form-grid-layout">
                    <div class="form-field-group">
                        <label>Nº de Orden (Autogenerado)</label>
                        <input type="text" class="input-control" value="<?= htmlspecialchars($sugerenciaConsecutivo) ?>" disabled>
                        <span class="sub-text">Asignado de forma secuencial y automática al procesar.</span>
                    </div>
                    <div class="form-field-group">
                        <label>Producto a Fabricar</label>
                        <select name="id_producto" class="input-control" required>
                            <option value="">Seleccione producto...</option>
                            <?php foreach ($productos as $p): ?>
                                <option value="<?= $p['id_producto'] ?>"><?= htmlspecialchars($p['nombre_producto']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field-group">
                        <label>Cliente</label>
                        <select name="id_cliente" class="input-control" required>
                            <option value="">Seleccione cliente...</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id_tercero'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field-group">
                        <label>Asesor Comercial</label>
                        <select name="id_asesor" class="input-control" required>
                            <option value="">Seleccione asesor...</option>
                            <?php foreach ($asesores as $a): ?>
                                <option value="<?= $a['id_tercero'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field-group">
                        <label>Fabricante / Taller</label>
                        <select name="id_fabricante" class="input-control" required>
                            <option value="">Seleccione fabricante...</option>
                            <?php foreach ($fabricantes as $f): ?>
                                <option value="<?= $f['id_tercero'] ?>"><?= htmlspecialchars($f['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field-group">
                        <label>Responsable Material</label>
                        <select name="id_operario" class="input-control">
                            <option value="">Sin asignar</option>
                            <?php foreach ($operarios as $op): ?>
                                <option value="<?= $op['id_tercero'] ?>"><?= htmlspecialchars($op['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field-group">
                        <label>Unidades</label>
                        <input type="number" name="unidades" class="input-control" value="1" min="1" required>
                    </div>
                    <div class="form-field-group">
                        <label>% Utilidad</label>
                        <input type="number" step="0.01" name="porcentaje_utilidad" class="input-control" value="25.00" required>
                    </div>
                    <div class="form-field-group">
                        <label>Fecha de Pedido</label>
                        <input type="date" name="fecha_pedido" class="input-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-field-group">
                        <label>Fecha de Entrega</label>
                        <input type="date" name="fecha_entrega" class="input-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                    </div>
                    <div class="form-field-group full-width">
                        <label>Asignación de Materiales (Ilimitados)</label>
                        <div id="container-crear" class="materials-container">
                            <!-- Fila por defecto inicial -->
                            <div class="material-item-row">
                                <select name="materiales_lineas[][id_material]" class="input-control" required>
                                    <option value="">Seleccione material...</option>
                                    <?php foreach ($materialesDisponibles as $mat): ?>
                                        <option value="<?= $mat['id_material'] ?>"><?= htmlspecialchars($mat['nombre']) ?> ($<?= number_format((float)$mat['costo'], 2) ?> / <?= $mat['unidad_medida'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="number" step="0.0001" min="0.0001" name="materiales_lineas[][cantidad]" class="input-control" placeholder="Cantidad por unidad" required>
                                <button type="button" class="btn-remove-material" onclick="if(document.querySelectorAll('#container-crear .material-item-row').length > 1) { this.closest('.material-item-row').remove(); } else { alert('Debe mantener al menos un material'); }">×</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" style="margin-top: 0.75rem;" onclick="agregarFilaMaterial('container-crear')">➕ Agregar otro material</button>
                    </div>
                </div>
            </div>
            <div class="modal-bottom">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar Orden e Insumos</button>
            </div>
        </form>
    </div>
</div>

<script>
    const opcionesMaterialesHtml = `
        <option value="">Seleccione material...</option>
        <?php foreach ($materialesDisponibles as $mat): ?>
            <option value="<?= $mat['id_material'] ?>"><?= htmlspecialchars($mat['nombre']) ?> ($<?= number_format((float)$mat['costo'], 2) ?> / <?= $mat['unidad_medida'] ?>)</option>
        <?php endforeach; ?>
    `;

    function agregarFilaMaterial(containerId) {
        const container = document.getElementById(containerId);
        const nuevaFila = document.createElement('div');
        nuevaFila.className = 'material-item-row';
        nuevaFila.innerHTML = `
            <select name="materiales_lineas[][id_material]" class="input-control" required>
                ${opcionesMaterialesHtml}
            </select>
            <input type="number" step="0.0001" min="0.0001" name="materiales_lineas[][cantidad]" class="input-control" placeholder="Cantidad por unidad" required>
            <button type="button" class="btn-remove-material" onclick="this.closest('.material-item-row').remove()">×</button>
        `;
        container.appendChild(nuevaFila);
        
        // Auto-scroll interno hacia la nueva fila añadida si es extensa
        container.scrollTop = container.scrollHeight;
    }

    function abrirModal(id) {
        document.getElementById(id).style.display = 'flex';
    }
    function cerrarModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            event.target.style.display = 'none';
        }
    }
</script>

</body>
</html>