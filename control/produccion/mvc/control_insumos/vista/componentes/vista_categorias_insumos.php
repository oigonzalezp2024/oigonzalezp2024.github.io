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
<h2>Categorías de insumos</h2>
</center>
<button class="btn btn-primary navbar-left"
               data-toggle="modal"
               data-target="#modalNuevo">
    Agregar una categoría
    <span class="glyphicon glyphicon-plus"></span>
</button></div>
    <table class="table table-hover table-condensed table-bordered table-responsive">
    <thead>
        <tr>
            <th>id_categoria</th>
            <th>nombre_categoria</th>
            <th>descripcion</th>
            <th></th>
            <th></th>
        </tr>
   </thead>
    <tbody>
    <?php
    $sql = 'SELECT * FROM categorias_insumos';
    $result = mysqli_query($conn, $sql);
    WHILE($fila = mysqli_fetch_assoc($result)){
        $datos = $fila['id_categoria'] . "||" .
                  $fila['nombre_categoria'] . "||" .
                  $fila['descripcion'];
    ?>
        <tr>
            <td><?php echo $fila['id_categoria']; ?></td>
            <td><?php echo $fila['nombre_categoria']; ?></td>
            <td><?php echo $fila['descripcion']; ?></td>
            <td>
                <button class="btn btn-warning glyphicon glyphicon-pencil"
                               data-toggle="modal"
                               data-target="#modalEdicion"
                               onclick="agregaform('<?php echo $datos; ?>')">
                </button></td>
            <td>
                <button class="btn btn-danger glyphicon glyphicon-remove"
                           onclick="preguntarSiNo('<?php echo $fila['id_categoria']; ?>')">
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
