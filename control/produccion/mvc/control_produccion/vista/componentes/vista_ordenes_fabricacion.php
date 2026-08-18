<?php
include_once '../../modelo/conexion.php';
$conn = conexion();

// Configuración de la paginación a 5 registros por página
$registros_por_pagina = 5; 
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) {
    $pagina_actual = 1;
}
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// 1. Obtener el total de registros
$sql_count = "SELECT COUNT(*) as total FROM `ordenes_fabricacion` ord
INNER JOIN `personas` cli ON ord.cliente_id = cli.id_persona
INNER JOIN `personas` ase ON ord.asesor_id = ase.id_persona
INNER JOIN `personas` fab ON ord.fabricante_id = fab.id_persona
INNER JOIN `personas` ope ON ord.operario_id = ope.id_persona
INNER JOIN productos_catalogo pro ON ord.producto_id = pro.id_producto";

$result_count = mysqli_query($conn, $sql_count);
$fila_count = mysqli_fetch_assoc($result_count);
$total_registros = $fila_count['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordenes de Fabricación</title>
</head>

<body>
    <div style="width:100%; padding-top:50px; text-align:center;">
        <h2>O. fabricación</h2>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-condensed table-bordered">
            <thead>
                <tr>
                    <th>numero_orden</th>
                    <th>hoja_taller</th>
                    <th>informe_costos</th>
                    <th>cliente</th>
                    <th>asesor</th>
                    <th>fabricante</th>
                    <th>operario</th>
                    <th>producto</th>
                    <th>unidades</th>
                    <th>estado</th>
                    <th>fecha_pedido</th>
                    <th>fecha_entrega</th>
                    <th>porcentaje_utilidad</th>
                    <th>creado_en</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 2. Consulta principal con LIMIT de 5 registros
                $sql = "SELECT 
                    ord.id_orden,
                    ord.numero_orden,
                    ord.cliente_id,
                    ord.asesor_id,
                    ord.fabricante_id,
                    ord.operario_id,
                    ord.producto_id,
                    ord.unidades,
                    ord.estado,
                    ord.fecha_pedido,
                    ord.fecha_entrega,
                    ord.costo_subtotal,
                    ord.costo_mod,
                    ord.costo_cif,
                    ord.porcentaje_utilidad,
                    ord.monto_utilidad,
                    ord.monto_total,
                    ord.creado_en,
                    cli.nombre AS nombre_cliente,
                    ase.nombre AS nombre_asesor,
                    fab.nombre AS nombre_fabricante,
                    ope.nombre AS nombre_operario,
                    pro.nombre_producto AS nombre_producto,
                    pro.codigo_referencia AS codigo_referencia
                FROM `ordenes_fabricacion` ord
                INNER JOIN `personas` cli ON ord.cliente_id = cli.id_persona
                INNER JOIN `personas` ase ON ord.asesor_id = ase.id_persona
                INNER JOIN `personas` fab ON ord.fabricante_id = fab.id_persona
                INNER JOIN `personas` ope ON ord.operario_id = ope.id_persona
                INNER JOIN productos_catalogo pro ON ord.producto_id = pro.id_producto
                LIMIT $inicio, $registros_por_pagina;";
                
                $result = mysqli_query($conn, $sql);
                while ($fila = mysqli_fetch_assoc($result)) {
                    $datos = $fila['id_orden'] . "||" .
                        $fila['numero_orden'] . "||" .
                        $fila['cliente_id'] . "||" .
                        $fila['asesor_id'] . "||" .
                        $fila['fabricante_id'] . "||" .
                        $fila['operario_id'] . "||" .
                        $fila['producto_id'] . "||" .
                        $fila['unidades'] . "||" .
                        $fila['estado'] . "||" .
                        $fila['fecha_pedido'] . "||" .
                        $fila['fecha_entrega'] . "||" .
                        $fila['costo_subtotal'] . "||" .
                        $fila['costo_mod'] . "||" .
                        $fila['costo_cif'] . "||" .
                        $fila['porcentaje_utilidad'] . "||" .
                        $fila['monto_utilidad'] . "||" .
                        $fila['monto_total'] . "||" .
                        $fila['creado_en'];
                ?>
                    <tr>
                        <td><?php echo $fila['numero_orden']; ?></td>
                        <td>
                            <a href="../../../operario/ver_pdf_taller.php?id_orden=<?php echo $fila['id_orden']; ?>">ver hoja taller</a>
                        </td>
                        <td>
                            <a href="../../../control_costos/ver_costos_orden.php?id_orden=<?php echo $fila['id_orden']; ?>">ver relación de costos</a>
                        </td>
                        <td><?php echo $fila['nombre_cliente']; ?></td>
                        <td><?php echo $fila['nombre_asesor']; ?></td>
                        <td><?php echo $fila['nombre_fabricante']; ?></td>
                        <td><?php echo $fila['nombre_operario']; ?></td>
                        <td><?php echo $fila['nombre_producto']; ?></td>
                        <td><?php echo $fila['unidades']; ?></td>
                        <td><?php echo $fila['estado']; ?></td>
                        <td><?php echo $fila['fecha_pedido']; ?></td>
                        <td><?php echo $fila['fecha_entrega']; ?></td>
                        <td><?php echo $fila['porcentaje_utilidad']; ?></td>
                        <td><?php echo $fila['creado_en']; ?></td>
                        <td>
                            <button class="btn btn-warning glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $datos; ?>')">
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-danger glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $fila['id_orden']; ?>')">
                            </button>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div style="width:100%; display:inline-block; text-align:center;">
        <button class="btn btn-primary navbar-left"
            data-toggle="modal"
            data-target="#modalNuevo">
            Agregar ordenes_fabricacion
            <span class="glyphicon glyphicon-plus"></span>
        </button>
    </div>

    <!-- 3. Navegación (solo se muestra si hay más de 5 registros en total) -->
    <?php if ($total_paginas > 1): ?>
    <div style="text-align:center;">
        <ul class="pagination">
            <li class="<?php echo ($pagina_actual <= 1) ? 'disabled' : ''; ?>">
                <a href="<?php echo ($pagina_actual > 1) ? '?pagina=' . ($pagina_actual - 1) : '#'; ?>">&laquo; Anterior</a>
            </li>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="<?php echo ($pagina_actual == $i) ? 'active' : ''; ?>">
                    <a href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <li class="<?php echo ($pagina_actual >= $total_paginas) ? 'disabled' : ''; ?>">
                <a href="<?php echo ($pagina_actual < $total_paginas) ? '?pagina=' . ($pagina_actual + 1) : '#'; ?>">Siguiente &raquo;</a>
            </li>
        </ul>
    </div>
    <?php endif; ?>

</body>

</html>
<?php
mysqli_close($conn);
?>
