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
<h2>Personas registrados en el sistema</h2>
</center>
<button class="btn btn-primary navbar-left"
               data-toggle="modal"
               data-target="#modalNuevo">
    Agregar personas
    <span class="glyphicon glyphicon-plus"></span>
</button></div>
    <table class="table table-hover table-condensed table-bordered table-responsive">
    <thead>
        <tr>
            <th>id_persona</th>
            <th>documento</th>
            <th>nombre</th>
            <th>rol</th>
            <th>telefono</th>
            <th>creado_en</th>
            <th></th>
            <th></th>
        </tr>
   </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM `personas` WHERE `rol` = 'CLIENTE';";
    $result = mysqli_query($conn, $sql);
    WHILE($fila = mysqli_fetch_assoc($result)){
        $datos = $fila['id_persona'] . "||" .
                  $fila['documento'] . "||" .
                  $fila['nombre'] . "||" .
                  $fila['rol'] . "||" .
                  $fila['telefono'] . "||" .
                  $fila['creado_en'];
    ?>
        <tr>
            <td><?php echo $fila['id_persona']; ?></td>
            <td><?php echo $fila['documento']; ?></td>
            <td><?php echo $fila['nombre']; ?></td>
            <td><?php echo $fila['rol']; ?></td>
            <td><?php echo $fila['telefono']; ?></td>
            <td><?php echo $fila['creado_en']; ?></td>
            <td>
                <button class="btn btn-warning glyphicon glyphicon-pencil"
                               data-toggle="modal"
                               data-target="#modalEdicion"
                               onclick="agregaform('<?php echo $datos; ?>')">
                </button></td>
            <td>
                <button class="btn btn-danger glyphicon glyphicon-remove"
                           onclick="preguntarSiNo('<?php echo $fila['id_persona']; ?>')">
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
