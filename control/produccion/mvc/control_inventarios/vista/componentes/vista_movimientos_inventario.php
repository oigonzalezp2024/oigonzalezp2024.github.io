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
<h2>movimientos_inventario</h2>
</center>
<button class="btn btn-primary navbar-left"
               data-toggle="modal"
               data-target="#modalNuevo">
    Agregar movimientos_inventario
    <span class="glyphicon glyphicon-plus"></span>
</button></div>
    <table class="table table-hover table-condensed table-bordered table-responsive">
    <thead>
        <tr>
            <th>id_movimiento</th>
            <th>tipo_item</th>
            <th>id_item</th>
            <th>tipo_movimiento</th>
            <th>cantidad</th>
            <th>orden_id</th>
            <th>observacion</th>
            <th>fecha_movimiento</th>
        </tr>
   </thead>
    <tbody>
    <?php
    $sql = 'SELECT * FROM movimientos_inventario';
    $result = mysqli_query($conn, $sql);
    WHILE($fila = mysqli_fetch_assoc($result)){
        $datos = $fila['id_movimiento'] . "||" .
                  $fila['tipo_item'] . "||" .
                  $fila['id_item'] . "||" .
                  $fila['tipo_movimiento'] . "||" .
                  $fila['cantidad'] . "||" .
                  $fila['orden_id'] . "||" .
                  $fila['observacion'] . "||" .
                  $fila['fecha_movimiento'];
    ?>
        <tr>
            <td><?php echo $fila['id_movimiento']; ?></td>
            <td><?php echo $fila['tipo_item']; ?></td>
            <td><?php echo $fila['id_item']; ?></td>
            <td><?php echo $fila['tipo_movimiento']; ?></td>
            <td><?php echo $fila['cantidad']; ?></td>
            <td><?php echo $fila['orden_id']; ?></td>
            <td><?php echo $fila['observacion']; ?></td>
            <td><?php echo $fila['fecha_movimiento']; ?></td>
            <td>
                <button class="btn btn-warning glyphicon glyphicon-pencil"
                               data-toggle="modal"
                               data-target="#modalEdicion"
                               onclick="agregaform('<?php echo $datos; ?>')">
                </button></td>
            <td>
                <button class="btn btn-danger glyphicon glyphicon-remove"
                           onclick="preguntarSiNo('<?php echo $fila['id_movimiento']; ?>')">
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
