<?php
include_once '../../modelo/conexion.php';
$conn = conexion();

$buscar = isset($_GET['buscar']) ? mysqli_real_escape_string($conn, trim($_GET['buscar'])) : '';

$where_sql = "";
if ($buscar != '') {
    $where_sql = "WHERE nombre_producto LIKE '%$buscar%' OR codigo_referencia LIKE '%$buscar%'";
}
?>

<div style="width:100%; padding-top:70px; text-align:center;">
    <h2 style="margin: 0; font-weight: bold;">CATÁLOGO DE PRODUCTOS</h2>
</div>

<div style="width:90%; margin: 20px auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevo">
        Agregar Producto
        <span class="glyphicon glyphicon-plus"></span>
    </button>

    <div style="display: flex; gap: 10px; align-items: center;">
        <input type="text" id="input_buscar" class="form-control input-sm" style="width: 220px;" placeholder="Buscar referencia o nombre..." value="<?php echo htmlspecialchars($buscar); ?>" onkeyup="evaluarBusquedaProductos(event)">
        <button class="btn btn-default btn-sm" onclick="cargarTablaProductos()">
            <span class="glyphicon glyphicon-search"></span> Buscar
        </button>
    </div>
</div>

<div class="table-responsive" style="width:90%; margin: 0 auto;">
    <table class="table table-hover table-condensed table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código Ref.</th>
                <th>Nombre del Producto</th>
                <th>Descripción</th>
                <th>Creado en</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id_producto, codigo_referencia, nombre_producto, descripcion, creado_en 
                    FROM productos_catalogo 
                    $where_sql 
                    ORDER BY id_producto DESC";

            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($fila = mysqli_fetch_assoc($result)) {
                    $cadena_raw = $fila['id_producto'] . "||" .
                        $fila['codigo_referencia'] . "||" .
                        $fila['nombre_producto'] . "||" .
                        $fila['descripcion'];

                    $datos = htmlspecialchars($cadena_raw, ENT_QUOTES, 'UTF-8');
            ?>
                    <tr>
                        <td><?php echo $fila['id_producto']; ?></td>
                        <td><strong><?php echo htmlspecialchars($fila['codigo_referencia']); ?></strong></td>
                        <td><?php echo htmlspecialchars($fila['nombre_producto']); ?></td>
                        <td><?php echo htmlspecialchars($fila['descripcion']); ?></td>
                        <td><?php echo $fila['creado_en']; ?></td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm glyphicon glyphicon-pencil" data-toggle="modal" data-target="#modalEdicion" onclick="agregaformProducto('<?php echo $datos; ?>')"></button>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm glyphicon glyphicon-remove" onclick="preguntarSiNoProducto('<?php echo $fila['id_producto']; ?>')"></button>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="7" style="text-align:center;">No se encontraron productos en el catálogo</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php mysqli_close($conn); ?>
