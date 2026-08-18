<?php
include_once '../../modelo/conexion.php';
$conn = conexion();

$orden_id = isset($_GET['orden_id']) ? (int)$_GET['orden_id'] : 0;

if ($orden_id <= 0) {
    echo '<div class="alert alert-danger text-center">No se especificó una orden de fabricación válida.</div>';
    exit;
}

// 1. Consulta SQL ajustada para traer los campos autorizados de la orden
$sql_orden_padre = "SELECT 
    ord.id_orden, 
    ord.numero_orden, 
    cli.nombre AS nombre_cliente, 
    ase.nombre AS nombre_asesor, 
    fab.nombre AS nombre_fabricante, 
    ope.nombre AS nombre_operario, 
    pro.nombre_producto, 
    ord.unidades, 
    ord.estado, 
    ord.fecha_pedido, 
    ord.fecha_entrega, 
    ord.porcentaje_utilidad, 
    ord.creado_en 
FROM `ordenes_fabricacion` ord
LEFT JOIN `personas` cli ON ord.cliente_id = cli.id_persona
LEFT JOIN `personas` ase ON ord.asesor_id = ase.id_persona
LEFT JOIN `personas` fab ON ord.fabricante_id = fab.id_persona
LEFT JOIN `personas` ope ON ord.operario_id = ope.id_persona
LEFT JOIN `productos_catalogo` pro ON ord.producto_id = pro.id_producto
WHERE ord.id_orden = $orden_id LIMIT 1";

$res_orden_padre = mysqli_query($conn, $sql_orden_padre);
$fila = mysqli_fetch_assoc($res_orden_padre);
?>

<!-- TARJETA/FICHA DE LA ÓRDEN CON LOS CAMPOS AUTORIZADOS -->
<?php if ($fila): ?>
<div class="panel panel-primary" style="margin-top: 15px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 20px;">
        <h3 class="panel-title" style="font-size: 18px; font-weight: bold;">
            <span class="glyphicon glyphicon-file"></span> <?php echo htmlspecialchars($fila['numero_orden']); ?>
        </h3>
        <span class="label label-default" style="font-size: 13px; padding: 6px 12px;">
            <?php echo ucfirst(htmlspecialchars($fila['estado'])); ?>
        </span>
    </div>
    <div class="panel-body" style="background-color: #fcfcfc;">
        <div class="row" style="font-size: 14px; line-height: 1.8;">
            <!-- Columna 1: Equipo / Personas -->
            <div class="col-md-3 col-sm-6">
                <p><strong><span class="glyphicon glyphicon-user"></span> Cliente:</strong> <br><?php echo htmlspecialchars($fila['nombre_cliente']); ?></p>
                <p><strong><span class="glyphicon glyphicon-briefcase"></span> Asesor:</strong> <br><?php echo htmlspecialchars($fila['nombre_asesor']); ?></p>
                <p><strong><span class="glyphicon glyphicon-wrench"></span> Fabricante:</strong> <br><?php echo htmlspecialchars($fila['nombre_fabricante']); ?></p>
                <p><strong><span class="glyphicon glyphicon-cog"></span> Operario:</strong> <br><?php echo htmlspecialchars($fila['nombre_operario']); ?></p>
            </div>

            <!-- Columna 2: Producto y Cantidad -->
            <div class="col-md-3 col-sm-6">
                <p><strong><span class="glyphicon glyphicon-box"></span> Producto:</strong> <br><?php echo htmlspecialchars($fila['nombre_producto']); ?></p>
                <p><strong><span class="glyphicon glyphicon-th"></span> Unidades:</strong> <br><span class="badge" style="background-color: #337ab7; font-size: 13px;"><?php echo $fila['unidades']; ?></span></p>
            </div>

            <!-- Columna 3: Fechas y Tiempos -->
            <div class="col-md-3 col-sm-6">
                <p><strong><span class="glyphicon glyphicon-calendar"></span> Fecha Pedido:</strong> <br><?php echo $fila['fecha_pedido']; ?></p>
                <p><strong><span class="glyphicon glyphicon-time"></span> Fecha Entrega:</strong> <br><?php echo $fila['fecha_entrega']; ?></p>
                <p><strong><span class="glyphicon glyphicon-time"></span> Creado En:</strong> <br><?php echo $fila['creado_en']; ?></p>
            </div>

            <!-- Columna 4: Enlaces Directos -->
            <div class="col-md-3 col-sm-6 text-center" style="border-left: 1px solid #ddd; padding-top: 10px;">
                <p style="margin-bottom: 15px;">
                    <a href="../../../operario/ver_pdf_taller.php?id_orden=<?php echo $fila['id_orden']; ?>" class="btn btn-default btn-block" target="_blank">
                        <span class="glyphicon glyphicon-print"></span> Ver hoja taller
                    </a>
                </p>
                <p>
                    <a href="../../../control_costos/ver_costos_orden.php?id_orden=<?php echo $fila['id_orden']; ?>" class="btn btn-info btn-block" target="_blank">
                        <span class="glyphicon glyphicon-usd"></span> Ver relación de costos
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="alert alert-warning text-center">No se encontraron los datos de la orden #<?php echo $orden_id; ?>.</div>
<?php endif; ?>

<hr style="border-top: 2px solid #eee; margin: 25px 0;">

<!-- TABLA DE MATERIALES Y DETALLES DE LA ÓRDEN -->
<div style="width:100%; text-align:center; margin-bottom: 15px;">
    <h2 style="margin: 0 0 15px 0;">MATERIALES Y DETALLES DE LA ÓRDEN</h2>
</div>
<div style="width:100%; text-align:center; margin-bottom: 15px;">
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevo">
        <span class="glyphicon glyphicon-plus"></span> Agregar Detalle
    </button>
</div>

<div class="table-responsive">
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">ID Detalle</th>
                <th class="text-center">ID Material</th>
                <th>Medidas</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Cant. Consumida</th>
                <th class="text-right">Valor Unitario</th>
                <th class="text-right">Valor Total</th>
                <th class="text-center">Destacado</th>
                <th class="text-center">Editar</th>
                <th class="text-center">Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql_detalles = "SELECT * FROM orden_fabricacion_detalles 
                             WHERE orden_id = $orden_id 
                             ORDER BY id_detalle DESC;";

            $result_detalles = mysqli_query($conn, $sql_detalles);

            if ($result_detalles && mysqli_num_rows($result_detalles) > 0) {
                while ($fila_det = mysqli_fetch_assoc($result_detalles)) {
                    $datos = $fila_det['id_detalle'] . "||" .
                        $fila_det['orden_id'] . "||" .
                        $fila_det['material_id'] . "||" .
                        $fila_det['medidas'] . "||" .
                        $fila_det['cantidad'] . "||" .
                        $fila_det['cantidad_consumida'] . "||" .
                        $fila_det['valor_unitario'] . "||" .
                        $fila_det['valor_total'] . "||" .
                        $fila_det['es_destacado'];
                ?>
                    <tr>
                        <td class="text-center"><strong><?php echo $fila_det['id_detalle']; ?></strong></td>
                        <td class="text-center"><?php echo $fila_det['material_id']; ?></td>
                        <td><?php echo nl2br(htmlspecialchars($fila_det['medidas'])); ?></td>
                        <td class="text-center"><?php echo $fila_det['cantidad']; ?></td>
                        <td class="text-center"><?php echo $fila_det['cantidad_consumida']; ?></td>
                        <td class="text-right">$<?php echo number_format($fila_det['valor_unitario'], 2); ?></td>
                        <td class="text-right"><strong>$<?php echo number_format($fila_det['valor_total'], 2); ?></strong></td>
                        <td class="text-center">
                            <?php if ($fila_det['es_destacado'] == 1): ?>
                                <span class="label label-warning">Sí</span>
                            <?php else: ?>
                                <span class="label label-default">No</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm glyphicon glyphicon-pencil" data-toggle="modal" data-target="#modalEdicion" onclick="agregaform('<?php echo $datos; ?>')"></button>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm glyphicon glyphicon-remove" onclick="preguntarSiNo('<?php echo $fila_det['id_detalle']; ?>')"></button>
                        </td>
                    </tr>
                <?php
                }
            } else {
                echo '<tr><td colspan="10" class="text-center text-muted">No hay materiales registrados en esta orden.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php mysqli_close($conn); ?>
