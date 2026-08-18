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
            <h2>materiales</h2>
        </center>
        <button class="btn btn-primary navbar-left"
            data-toggle="modal"
            data-target="#modalNuevo">
            Agregar materiales
            <span class="glyphicon glyphicon-plus"></span>
        </button>
    </div>
    <table class="table table-hover table-condensed table-bordered table-responsive">
        <thead>
            <tr>
                <th>id_material</th>
                <th>codigo_material</th>
                <th>descripcion_material</th>
                <th>unidad_medida</th>
                <th>precio_unitario_defecto</th>
                <th>stock_minimo</th>
                <th>stock_actual</th>
                <th>stock_maximo</th>
                <th>categoría</th>
                <th>creado_en</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = 'SELECT 
  m.id_material AS id_material,
  m.codigo_material AS codigo_material,
  m.descripcion_material AS descripcion_material,
  m.unidad_medida AS unidad_medida,
  m.precio_unitario_defecto AS precio_unitario_defecto,
  m.categoria_id AS categoria_id,
  m.stock_minimo AS stock_minimo,
  m.stock_actual AS stock_actual,
  m.stock_maximo AS stock_maximo,
  m.creado_en AS creado_en,
  ci.nombre_categoria AS ci_nombre_categoria, 
  ci.descripcion AS ci_descripcion
FROM `materiales` m
INNER JOIN `categorias_insumos` ci 
  ON ci.id_categoria = m.categoria_id';
            $result = mysqli_query($conn, $sql);
            while ($fila = mysqli_fetch_assoc($result)) {
                $datos = $fila['id_material'] . "||" .
                    $fila['codigo_material'] . "||" .
                    $fila['descripcion_material'] . "||" .
                    $fila['unidad_medida'] . "||" .
                    $fila['precio_unitario_defecto'] . "||" .
                    $fila['categoria_id'] . "||" .
                    $fila['stock_minimo'] . "||" .
                    $fila['stock_actual'] . "||" .
                    $fila['stock_maximo'] . "||" .
                    $fila['creado_en'];
            ?>
                <tr>
                    <td><?php echo $fila['id_material']; ?></td>
                    <td><?php echo $fila['codigo_material']; ?></td>
                    <td><?php echo $fila['descripcion_material']; ?></td>
                    <td><?php echo $fila['unidad_medida']; ?></td>
                    <td><?php echo $fila['precio_unitario_defecto']; ?></td>
                    <td>
                        <?php echo $fila['ci_nombre_categoria']; ?>
                    </td>
                    <td><?php echo $fila['stock_minimo']; ?></td>
                    <td><?php echo $fila['stock_actual']; ?></td>
                    <td><?php echo $fila['stock_maximo']; ?></td>
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
                            onclick="preguntarSiNo('<?php echo $fila['id_material']; ?>')">
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