<?php
include_once '../../modelo/conexion.php';
$conn = conexion();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>arreglos</title>
</head>
<div class="row"><br><br><br><br>
    <div>
<center>
<h2>ordenes_fabricacion</h2>
</center>
<button class="btn btn-primary navbar-left"
               data-toggle="modal"
               data-target="#modalNuevo">
    Agregar ordenes_fabricacion
    <span class="glyphicon glyphicon-plus"></span>
</button></div>
    <table class="table table-hover table-condensed table-bordered table-responsive">
    <thead>
        <tr>
            <th>id_orden</th>
            <th>numero_orden</th>
            <th>cliente_id</th>
            <th>asesor_id</th>
            <th>fabricante_id</th>
            <th>operario_id</th>
            <th>producto_id</th>
            <th>unidades</th>
            <th>estado</th>
            <th>fecha_pedido</th>
            <th>fecha_entrega</th>
            <th>costo_subtotal</th>
            <th>costo_mod</th>
            <th>costo_cif</th>
            <th>porcentaje_utilidad</th>
            <th>monto_utilidad</th>
            <th>monto_total</th>
            <th>creado_en</th>
            <th></th>
            <th></th>
        </tr>
   </thead>
    <tbody>
    <?php
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
INNER JOIN productos_catalogo pro ON ord.producto_id = pro.id_producto;";
    $result = mysqli_query($conn, $sql);
    WHILE($fila = mysqli_fetch_assoc($result)){
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
            <td><?php echo $fila['id_orden']; ?></td>
            <td><?php echo $fila['numero_orden']; ?></td>
            <td><?php echo $fila['nombre_cliente']; ?></td>
            <td><?php echo $fila['nombre_asesor']; ?></td>
            <td><?php echo $fila['nombre_fabricante']; ?></td>
            <td><?php echo $fila['nombre_operario']; ?></td>
            <td><?php echo $fila['nombre_producto']; ?></td>
            <td><?php echo $fila['unidades']; ?></td>
            <td><?php echo $fila['estado']; ?></td>
            <td><?php echo $fila['fecha_pedido']; ?></td>
            <td><?php echo $fila['fecha_entrega']; ?></td>
            <td><?php echo $fila['costo_subtotal']; ?></td>
            <td><?php echo $fila['costo_mod']; ?></td>
            <td><?php echo $fila['costo_cif']; ?></td>
            <td><?php echo $fila['porcentaje_utilidad']; ?></td>
            <td><?php echo $fila['monto_utilidad']; ?></td>
            <td><?php echo $fila['monto_total']; ?></td>
            <td><?php echo $fila['creado_en']; ?></td>
            <td>
                <button class="btn btn-warning glyphicon glyphicon-pencil"
                               data-toggle="modal"
                               data-target="#modalEdicion"
                               onclick="agregaform('<?php echo $datos; ?>')">
                </button></td>
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
</body>
</html>
<?php
mysqli_close($conn);
?>
