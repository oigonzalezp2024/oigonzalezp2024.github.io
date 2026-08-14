<?php
declare(strict_types=1);

function obtenerDatosCostosOrden(PDO $pdo, int $idOrden): ?array {
    // 1. Cabecera de la orden cruzando con el producto a fabricar
    $stmtOrden = $pdo->prepare("
        SELECT 
            o.*,
            COALESCE(p.nombre_producto, 'Producto Estándar') AS producto_nombre,
            COALESCE(p.codigo_referencia, '') AS producto_codigo
        FROM ordenes_fabricacion o
        LEFT JOIN productos_catalogo p ON o.id_producto = p.id_producto
        WHERE o.id_orden = :id_orden
    ");
    $stmtOrden->execute([':id_orden' => $idOrden]);
    $orden = $stmtOrden->fetch();

    if (!$orden) {
        return null;
    }

    // 2. Detalles y costos con precio_unitario_defecto real de materiales
    $stmtDetalles = $pdo->prepare("
        SELECT 
            d.id_detalle,
            d.medidas,
            d.cantidad AS cantidad_estimada,
            d.cantidad_consumida,
            m.codigo_material,
            m.descripcion_material,
            m.unidad_medida,
            COALESCE(m.precio_unitario_defecto, 0.00) AS costo_unitario
        FROM orden_fabricacion_detalles d
        INNER JOIN materiales m ON d.id_material = m.id_material
        WHERE d.id_orden = :id_orden
        ORDER BY d.id_detalle ASC
    ");
    $stmtDetalles->execute([':id_orden' => $idOrden]);
    $detalles = $stmtDetalles->fetchAll();

    // 3. Procesamiento financiero
    $costoTotalEstimado = 0.0;
    $costoTotalReal = 0.0;
    $porcentajeUtilidadMeta = isset($orden['porcentaje_utilidad']) ? (float)$orden['porcentaje_utilidad'] : 30.0;
    
    $itemsProcesados = [];
    foreach ($detalles as $item) {
        $costoUnitario = (float)$item['costo_unitario'];
        $cantEst = (float)$item['cantidad_estimada'];
        $cantReal = $item['cantidad_consumida'] !== null ? (float)$item['cantidad_consumida'] : $cantEst;

        $subtotalEst = $cantEst * $costoUnitario;
        $subtotalReal = $cantReal * $costoUnitario;
        $desviacion = $subtotalReal - $subtotalEst;

        $costoTotalEstimado += $subtotalEst;
        $costoTotalReal += $subtotalReal;

        $esExcedido = ($item['cantidad_consumida'] !== null && $cantReal > $cantEst);

        $itemsProcesados[] = [
            'id_detalle' => (int)$item['id_detalle'],
            'codigo_material' => $item['codigo_material'],
            'descripcion_material' => $item['descripcion_material'],
            'unidad_medida' => $item['unidad_medida'],
            'medidas' => $item['medidas'],
            'costo_unitario' => $costoUnitario,
            'cantidad_estimada' => $cantEst,
            'cantidad_consumida' => $item['cantidad_consumida'] !== null ? $cantReal : null,
            'subtotal_estimado' => $subtotalEst,
            'subtotal_real' => $subtotalReal,
            'desviacion' => $desviacion,
            'es_excedido' => $esExcedido
        ];
    }

    $desviacionTotal = $costoTotalReal - $costoTotalEstimado;
    $factorUtilidad = (1 - ($porcentajeUtilidadMeta / 100));
    $precioMinimoVenta = $factorUtilidad > 0 ? ($costoTotalReal / $factorUtilidad) : $costoTotalReal;
    $montoUtilidadEsperado = $precioMinimoVenta - $costoTotalReal;

    return [
        'orden' => [
            'id_orden' => (int)$orden['id_orden'],
            'numero_orden' => $orden['numero_orden'],
            'estado' => $orden['estado'],
            'porcentaje_utilidad' => $porcentajeUtilidadMeta,
            'producto_nombre' => $orden['producto_nombre'],
            'producto_codigo' => $orden['producto_codigo']
        ],
        'detalles' => $itemsProcesados,
        'kpis' => [
            'costo_total_estimado' => $costoTotalEstimado,
            'costo_total_real' => $costoTotalReal,
            'desviacion_total' => $desviacionTotal,
            'porcentaje_utilidad_meta' => $porcentajeUtilidadMeta,
            'monto_utilidad_esperado' => $montoUtilidadEsperado,
            'precio_minimo_venta' => $precioMinimoVenta
        ]
    ];
}
