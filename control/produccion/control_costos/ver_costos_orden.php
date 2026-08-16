<?php
declare(strict_types=1);

require_once 'api.php';

$idOrden = filter_input(INPUT_GET, 'id_orden', FILTER_VALIDATE_INT);

if (!$idOrden) {
    http_response_code(400);
    die("ID de orden no válido o ausente.");
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
} catch (Exception $e) {
    http_response_code(500);
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

$data = obtenerDatosCostosOrden($pdo, $idOrden);

if (!$data) {
    http_response_code(404);
    die("La orden solicitada no existe.");
}

$orden = $data['orden'];
$detalles = $data['detalles'];
$kpis = $data['kpis'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis de Costos - Orden #<?= htmlspecialchars((string)$orden['numero_orden']) ?></title>
    <style>
        :root { --danger: #dc2626; --success: #16a34a; --bg: #f8fafc; --card: #ffffff; --border: #e2e8f0; }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background: var(--bg); color: #1e293b; padding: 20px; }
        .container { max-width: 1050px; margin: 0 auto; background: var(--card); padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid var(--border); }
        
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--border); padding-bottom: 15px; margin-bottom: 25px; }
        .header h1 { font-size: 1.4rem; color: #0f172a; }
        .header p { font-size: 0.85rem; color: #64748b; margin-top: 4px; }
        .product-badge { background: #e0f2fe; color: #0369a1; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 0.9rem; display: inline-block; margin-top: 8px; border: 1px solid #bae6fd; }

        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 15px; margin-bottom: 30px; }
        .kpi-card { background: #f8fafc; padding: 18px; border-radius: 10px; border: 1px solid var(--border); border-left: 4px solid #0284c7; }
        .kpi-card.warning { border-left-color: var(--danger); background: #fef2f2; }
        .kpi-card.success { border-left-color: var(--success); background: #f0fdf4; }
        .kpi-title { font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px; }
        .kpi-value { font-size: 1.35rem; font-weight: 800; margin-top: 6px; color: #0f172a; }
        .kpi-card small { display: block; font-size: 0.78rem; color: #64748b; margin-top: 4px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 0.88rem; }
        th { background: #0f172a; color: white; text-align: left; padding: 12px 10px; font-weight: 600; font-size: 0.8rem; letter-spacing: 0.5px; }
        td { padding: 12px 10px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        .row-exceeded { background-color: #fef2f2; }
        .text-danger { color: var(--danger); font-weight: bold; }
        .text-success { color: var(--success); font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .btn-action { display: inline-flex; align-items: center; gap: 6px; background: #2563eb; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.85rem; transition: background 0.2s; }
        .btn-action:hover { background: #1d4ed8; }

        @media print {
            body { background: white; padding: 0; }
            .container { box-shadow: none; border: none; padding: 0; max-width: 100%; }
            .btn-action { display: none; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div>
            <h1>Auditoría de Costos y Rentabilidad</h1>
            <p>Orden N°: <strong><?= htmlspecialchars((string)$orden['numero_orden']) ?></strong> | Estado: <span style="text-transform: uppercase; font-weight: bold;"><?= htmlspecialchars((string)$orden['estado']) ?></span></p>
            <div class="product-badge">
                📦 Producto a Fabricar: [<?= htmlspecialchars((string)$orden['producto_codigo']) ?>] <?= htmlspecialchars((string)$orden['producto_nombre']) ?>
            </div>
        </div>
        <a href="javascript:window.print()" class="btn-action">🖨️ Imprimir Reporte</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código / Material</th>
                <th class="text-center">Costo Unit.</th>
                <th class="text-center">Cant. Est.</th>
                <th class="text-center">Cant. Real</th>
                <th class="text-right">Subtotal Est.</th>
                <th class="text-right">Subtotal Real</th>
                <th class="text-right">Desviación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $item): ?>
                <tr class="<?= $item['es_excedido'] ? 'row-exceeded' : '' ?>">
                    <td>
                        <strong>[<?= htmlspecialchars((string)$item['codigo_material']) ?>]</strong> 
                        <?= htmlspecialchars((string)$item['descripcion_material']) ?>
                        <?php if (!empty($item['medidas'])): ?>
                            <br><small style="color: #64748b;">Medida: <?= htmlspecialchars((string)$item['medidas']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">$<?= number_format($item['costo_unitario'], 2) ?></td>
                    <td class="text-center"><?= number_format($item['cantidad_estimada'], 2) ?> <?= htmlspecialchars((string)$item['unidad_medida']) ?></td>
                    <td class="text-center <?= $item['es_excedido'] ? 'text-danger' : '' ?>">
                        <?= number_format($item['cantidad_consumida'] ?? $item['cantidad_estimada'], 2) ?> <?= htmlspecialchars((string)$item['unidad_medida']) ?>
                        <?= $item['es_excedido'] ? '⚠️' : '' ?>
                    </td>
                    <td class="text-right">$<?= number_format($item['subtotal_estimado'], 2) ?></td>
                    <td class="text-right">$<?= number_format($item['subtotal_real'], 2) ?></td>
                    <td class="text-right <?= $item['desviacion'] > 0 ? 'text-danger' : ($item['desviacion'] < 0 ? 'text-success' : '') ?>">
                        <?php if ($item['desviacion'] > 0): ?>
                            +$<?= number_format($item['desviacion'], 2) ?>
                        <?php elseif ($item['desviacion'] < 0): ?>
                            -$<?= number_format(abs($item['desviacion']), 2) ?>
                        <?php else: ?>
                            $0.00
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-title">Costo Real Ejecutado</div>
            <div class="kpi-value">$<?= number_format($kpis['costo_total_real'], 2) ?></div>
            <small>Presupuestado: $<?= number_format($kpis['costo_total_estimado'], 2) ?></small>
        </div>

        <div class="kpi-card <?= $kpis['desviacion_total'] > 0 ? 'warning' : ($kpis['desviacion_total'] < 0 ? 'success' : '') ?>">
            <div class="kpi-title">Desviación Total</div>
            <div class="kpi-value">
                <?php if ($kpis['desviacion_total'] > 0): ?>
                    +$<?= number_format($kpis['desviacion_total'], 2) ?>
                <?php elseif ($kpis['desviacion_total'] < 0): ?>
                    -$<?= number_format(abs($kpis['desviacion_total']), 2) ?>
                <?php else: ?>
                    $0.00
                <?php endif; ?>
            </div>
            <small>
                <?php if ($kpis['desviacion_total'] > 0): ?>
                    Sobrecosto en materiales
                <?php elseif ($kpis['desviacion_total'] < 0): ?>
                    Eficiencia / Ahorro en materiales
                <?php else: ?>
                    Bajo control presupuestal
                <?php endif; ?>
            </small>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">Margen Meta (Utilidad)</div>
            <div class="kpi-value"><?= number_format($kpis['porcentaje_utilidad_meta'], 1) ?>%</div>
            <small>Utilidad neta: $<?= number_format($kpis['monto_utilidad_esperado'], 2) ?></small>
        </div>

        <div class="kpi-card success">
            <div class="kpi-title">Mínimo Sugerido de Venta</div>
            <div class="kpi-value" style="color: #15803d;">$<?= number_format($kpis['precio_minimo_venta'], 2) ?></div>
            <small>Piso comercial de rentabilidad</small>
        </div>
    </div>
</div>

</body>
</html>
