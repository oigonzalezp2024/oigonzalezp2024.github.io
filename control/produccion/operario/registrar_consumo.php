<?php
declare(strict_types=1);

$idOrden = filter_input(INPUT_GET, 'id_orden', FILTER_VALIDATE_INT);

if (!$idOrden) {
    die("Error: Parámetro de orden no válido.");
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
        SELECT o.id_orden, o.numero_orden, p.nombre_producto, o.unidades
        FROM ordenes_fabricacion o
        JOIN productos_catalogo p ON o.id_producto = p.id_producto
        WHERE o.id_orden = :id_orden
    ");
    $stmtEnc->execute([':id_orden' => $idOrden]);
    $orden = $stmtEnc->fetch();

    if (!$orden) {
        die("Error: La orden especificada no existe.");
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

} catch (Exception $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Registro Consumo - Orden #<?= htmlspecialchars((string)$orden['numero_orden']) ?></title>
    <style>
        :root {
            --bg: #f8fafc;
            --card: #ffffff;
            --primary: #2563eb;
            --text: #0f172a;
            --border: #e2e8f0;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background: var(--bg); color: var(--text); padding: 12px; padding-bottom: 85px; }
        
        .header-card { 
            background: var(--card); 
            padding: 16px; 
            border-radius: 12px; 
            border: 1px solid var(--border); 
            margin-bottom: 16px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); 
        }
        .header-card h1 { font-size: 1.1rem; color: var(--primary); }
        .header-card p { font-size: 0.85rem; color: #64748b; margin-top: 4px; }

        .item-card { 
            background: var(--card); 
            padding: 14px; 
            border-radius: 10px; 
            border: 1px solid var(--border); 
            margin-bottom: 12px; 
        }
        .item-card.highlight { border-left: 4px solid #ef4444; }
        .item-title { font-size: 0.9rem; font-weight: 600; margin-bottom: 4px; }
        .item-meta { font-size: 0.78rem; color: #64748b; margin-bottom: 10px; }
        
        .grid-inputs { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; align-items: center; }
        .est-box { background: #f1f5f9; padding: 8px; border-radius: 6px; font-size: 0.8rem; text-align: center; }
        .est-box strong { display: block; font-size: 0.95rem; color: #334155; }
        
        .input-group label { display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 4px; }
        .input-group input { 
            width: 100%; 
            padding: 10px; 
            font-size: 1rem; 
            border: 1px solid #cbd5e1; 
            border-radius: 6px; 
            text-align: right; 
        }
        .input-group input:focus { border-color: var(--primary); outline: none; }

        .btn-submit { 
            position: fixed; 
            bottom: 12px; 
            left: 12px; 
            right: 12px; 
            background: var(--primary); 
            color: white; 
            border: none; 
            padding: 14px; 
            border-radius: 10px; 
            font-size: 1rem; 
            font-weight: 700; 
            box-shadow: 0 4px 12px rgba(37,99,235,0.3); 
        }
    </style>
</head>
<body>

    <div class="header-card">
        <h1>Orden N° <?= htmlspecialchars((string)$orden['numero_orden']) ?></h1>
        <p><strong>Producto:</strong> <?= htmlspecialchars((string)$orden['nombre_producto']) ?> (<?= (int)$orden['unidades'] ?> Unid.)</p>
    </div>

    <form action="guardar_consumo.php" method="POST">
        <input type="hidden" name="id_orden" value="<?= $idOrden ?>">

        <?php foreach ($detalles as $det): ?>
            <?php 
                $cantVal = $det['cantidad_consumida'] !== null 
                    ? $det['cantidad_consumida'] 
                    : $det['cantidad_estimada']; 
            ?>
            <div class="item-card <?= !empty($det['es_destacado']) ? 'highlight' : '' ?>">
                <div class="item-title">
                    [<?= htmlspecialchars((string)$det['codigo_material']) ?>] <?= htmlspecialchars((string)$det['descripcion_material']) ?>
                </div>
                <div class="item-meta">
                    Medida: <?= htmlspecialchars((string)($det['medidas'] ?: '-')) ?> | Unidad: <?= htmlspecialchars((string)$det['unidad_medida']) ?>
                </div>

                <div class="grid-inputs">
                    <div class="est-box">
                        <span>Estimado:</span>
                        <strong><?= number_format((float)$det['cantidad_estimada'], 2) ?></strong>
                    </div>
                    <div class="input-group">
                        <label>Consumo Real:</label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="cant_consumida[<?= (int)$det['id_detalle'] ?>]" 
                            value="<?= htmlspecialchars((string)$cantVal) ?>" 
                            required
                        >
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn-submit">Guardar Registro de Consumo</button>
    </form>

</body>
</html>
