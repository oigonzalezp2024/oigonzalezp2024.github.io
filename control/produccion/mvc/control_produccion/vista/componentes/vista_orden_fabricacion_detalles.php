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
<h2>orden_fabricacion_detalles</h2>
</center>
<button class="btn btn-primary navbar-left"
               data-toggle="modal"
               data-target="#modalNuevo">
    Agregar orden_fabricacion_detalles
    <span class="glyphicon glyphicon-plus"></span>
</button></div>
    <table class="table table-hover table-condensed table-bordered table-responsive">
    <thead>
        <tr>
            <th>id_detalle</th>
            <th>orden_id</th>
            <th>material_id</th>
            <th>medidas</th>
            <th>cantidad</th>
            <th>cantidad_consumida</th>
            <th>valor_unitario</th>
            <th>valor_total</th>
            <th>es_destacado</th>
            <th></th>
            <th></th>
        </tr>
   </thead>
    <tbody>
    <?php
    $sql = 'SELECT * FROM orden_fabricacion_detalles';
    $result = mysqli_query($conn, $sql);
    WHILE($fila = mysqli_fetch_assoc($result)){
        $datos = $fila['id_detalle'] . "||" .
                  $fila['orden_id'] . "||" .
                  $fila['material_id'] . "||" .
                  $fila['medidas'] . "||" .
                  $fila['cantidad'] . "||" .
                  $fila['cantidad_consumida'] . "||" .
                  $fila['valor_unitario'] . "||" .
                  $fila['valor_total'] . "||" .
                  $fila['es_destacado'];
    ?>
        <tr>
            <td><?php echo $fila['id_detalle']; ?></td>
            <td><?php echo $fila['orden_id']; ?></td>
            <td><?php echo $fila['material_id']; ?></td>
            <td><?php echo $fila['medidas']; ?></td>
            <td><?php echo $fila['cantidad']; ?></td>
            <td><?php echo $fila['cantidad_consumida']; ?></td>
            <td><?php echo $fila['valor_unitario']; ?></td>
            <td><?php echo $fila['valor_total']; ?></td>
            <td><?php echo $fila['es_destacado']; ?></td>
            <td>
                <button class="btn btn-warning glyphicon glyphicon-pencil"
                               data-toggle="modal"
                               data-target="#modalEdicion"
                               onclick="agregaform('<?php echo $datos; ?>')">
                </button></td>
            <td>
                <button class="btn btn-danger glyphicon glyphicon-remove"
                           onclick="preguntarSiNo('<?php echo $fila['id_detalle']; ?>')">
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
