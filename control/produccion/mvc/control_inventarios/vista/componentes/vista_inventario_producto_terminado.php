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
<h2>inventario_producto_terminado</h2>
</center>
<button class="btn btn-primary navbar-left"
               data-toggle="modal"
               data-target="#modalNuevo">
    Agregar inventario_producto_terminado
    <span class="glyphicon glyphicon-plus"></span>
</button></div>
    <table class="table table-hover table-condensed table-bordered table-responsive">
    <thead>
        <tr>
            <th>id_inventario_pt</th>
            <th>producto_id</th>
            <th>orden_id</th>
            <th>cantidad</th>
            <th>ubicacion_bodega</th>
            <th>actualizado_en</th>
        </tr>
   </thead>
    <tbody>
    <?php
    $sql = 'SELECT * FROM inventario_producto_terminado';
    $result = mysqli_query($conn, $sql);
    WHILE($fila = mysqli_fetch_assoc($result)){
        $datos = $fila['id_inventario_pt'] . "||" .
                  $fila['producto_id'] . "||" .
                  $fila['orden_id'] . "||" .
                  $fila['cantidad'] . "||" .
                  $fila['ubicacion_bodega'] . "||" .
                  $fila['actualizado_en'];
    ?>
        <tr>
            <td><?php echo $fila['id_inventario_pt']; ?></td>
            <td><?php echo $fila['producto_id']; ?></td>
            <td><?php echo $fila['orden_id']; ?></td>
            <td><?php echo $fila['cantidad']; ?></td>
            <td><?php echo $fila['ubicacion_bodega']; ?></td>
            <td><?php echo $fila['actualizado_en']; ?></td>
            <td>
                <button class="btn btn-warning glyphicon glyphicon-pencil"
                               data-toggle="modal"
                               data-target="#modalEdicion"
                               onclick="agregaform('<?php echo $datos; ?>')">
                </button></td>
            <td>
                <button class="btn btn-danger glyphicon glyphicon-remove"
                           onclick="preguntarSiNo('<?php echo $fila['id_inventario_pt']; ?>')">
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
