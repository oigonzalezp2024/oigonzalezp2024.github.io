<?php
include_once '../../modelo/conexion.php';
$conn = conexion();

$buscar = isset($_GET['buscar']) ? mysqli_real_escape_string($conn, trim($_GET['buscar'])) : '';

$where_sql = "WHERE rol = 'CLIENTE'";
if ($buscar != '') {
    $where_sql .= " AND (nombre LIKE '%$buscar%' OR documento LIKE '%$buscar%')";
}
?>

<div style="width:100%; padding-top:70px; text-align:center;">
    <h2 style="margin: 0; font-weight: bold;">CLIENTES REGISTRADOS EN EL SISTEMA</h2>
</div>

<div style="width:90%; margin: 20px auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevo">
        Agregar Cliente
        <span class="glyphicon glyphicon-plus"></span>
    </button>

    <div style="display: flex; gap: 10px; align-items: center;">
        <input type="text" id="input_buscar" class="form-control input-sm" style="width: 220px;" placeholder="Buscar por nombre o doc..." value="<?php echo htmlspecialchars($buscar); ?>" onkeyup="evaluarBusquedaClientes(event)">
        <button class="btn btn-default btn-sm" onclick="cargarTablaClientes()">
            <span class="glyphicon glyphicon-search"></span> Buscar
        </button>
    </div>
</div>

<div class="table-responsive" style="width:90%; margin: 0 auto;">
    <table class="table table-hover table-condensed table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Teléfono</th>
                <th>Creado en</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id_persona, documento, nombre, rol, telefono, creado_en 
                    FROM personas 
                    $where_sql 
                    ORDER BY id_persona DESC";

            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($fila = mysqli_fetch_assoc($result)) {
                    $cadena_raw = $fila['id_persona'] . "||" .
                        $fila['documento'] . "||" .
                        $fila['nombre'] . "||" .
                        $fila['rol'] . "||" .
                        $fila['telefono'];

                    $datos = htmlspecialchars($cadena_raw, ENT_QUOTES, 'UTF-8');
            ?>
                    <tr>
                        <td><?php echo $fila['id_persona']; ?></td>
                        <td><?php echo htmlspecialchars($fila['documento']); ?></td>
                        <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                        <td><span class="label label-info"><?php echo htmlspecialchars($fila['rol']); ?></span></td>
                        <td><?php echo htmlspecialchars($fila['telefono']); ?></td>
                        <td><?php echo $fila['creado_en']; ?></td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm glyphicon glyphicon-pencil" data-toggle="modal" data-target="#modalEdicion" onclick="agregaformCliente('<?php echo $datos; ?>')"></button>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm glyphicon glyphicon-remove" onclick="preguntarSiNoCliente('<?php echo $fila['id_persona']; ?>')"></button>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="8" style="text-align:center;">No se encontraron clientes registrados</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php mysqli_close($conn); ?>
