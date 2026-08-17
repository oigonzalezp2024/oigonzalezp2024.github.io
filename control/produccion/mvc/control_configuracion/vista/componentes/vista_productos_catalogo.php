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
<h2>productos_catalogo</h2>
</center>
<button class="btn btn-primary navbar-left"
               data-toggle="modal"
               data-target="#modalNuevo">
    Agregar productos_catalogo
    <span class="glyphicon glyphicon-plus"></span>
</button></div>
    <table class="table table-hover table-condensed table-bordered table-responsive">
    <thead>
        <tr>
            <th>id_producto</th>
            <th>codigo_referencia</th>
            <th>nombre_producto</th>
            <th>descripcion</th>
            <th>creado_en</th>
        </tr>
   </thead>
    <tbody>
    <?php
    $sql = 'SELECT * FROM productos_catalogo';
    $result = mysqli_query($conn, $sql);
    WHILE($fila = mysqli_fetch_assoc($result)){
        $datos = $fila['id_producto'] . "||" .
                  $fila['codigo_referencia'] . "||" .
                  $fila['nombre_producto'] . "||" .
                  $fila['descripcion'] . "||" .
                  $fila['creado_en'];
    ?>
        <tr>
            <td><?php echo $fila['id_producto']; ?></td>
            <td><?php echo $fila['codigo_referencia']; ?></td>
            <td><?php echo $fila['nombre_producto']; ?></td>
            <td><?php echo $fila['descripcion']; ?></td>
            <td><?php echo $fila['creado_en']; ?></td>
            <td>
                <button class="btn btn-warning glyphicon glyphicon-pencil"
                               data-toggle="modal"
                               data-target="#modalEdicion"
                               onclick="agregaform('<?php echo $datos; ?>')">
                </button></td>
            <td>
                <button class="btn btn-danger glyphicon glyphicon-remove"
                           onclick="preguntarSiNo('<?php echo $fila['id_producto']; ?>')">
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
