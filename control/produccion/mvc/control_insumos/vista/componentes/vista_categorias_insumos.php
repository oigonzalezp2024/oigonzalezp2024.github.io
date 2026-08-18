<?php
include_once '../../modelo/conexion.php';
$conn = conexion();
?>

<div style="width:100%; padding-top:70px; text-align:center;">
    <h2 style="margin: 0; font-weight: bold;">CATEGORÍAS DE INSUMOS</h2>
</div>

<div style="width:100%; text-align:center; margin: 15px 0;">
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevo">
        Agregar una categoría
        <span class="glyphicon glyphicon-plus"></span>
    </button>
</div>

<!-- TABLA DE DATOS -->
<div class="table-responsive">
    <table class="table table-hover table-condensed table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Categoría</th>
                <th>Descripción</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id_categoria, nombre_categoria, descripcion 
                    FROM categorias_insumos 
                    ORDER BY id_categoria DESC";
            
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($fila = mysqli_fetch_assoc($result)) {
                    $cadena_raw = $fila['id_categoria'] . "||" .
                        $fila['nombre_categoria'] . "||" .
                        $fila['descripcion'];

                    $datos = htmlspecialchars($cadena_raw, ENT_QUOTES, 'UTF-8');
            ?>
                    <tr>
                        <td><?php echo $fila['id_categoria']; ?></td>
                        <td><?php echo htmlspecialchars($fila['nombre_categoria']); ?></td>
                        <td><?php echo htmlspecialchars($fila['descripcion']); ?></td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm glyphicon glyphicon-pencil" data-toggle="modal" data-target="#modalEdicion" onclick="agregaform('<?php echo $datos; ?>')"></button>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm glyphicon glyphicon-remove" onclick="preguntarSiNo('<?php echo $fila['id_categoria']; ?>')"></button>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="5" style="text-align:center;">No hay categorías registradas</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php mysqli_close($conn); ?>
