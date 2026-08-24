<?php
include_once '../../modelo/conexion.php';
$conn = conexion();

// Configuración de la paginación (5 registros por página)
$registros_por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) {
    $pagina_actual = 1;
}
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Captura de filtros
$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$estado_filtro = isset($_GET['estado']) ? trim($_GET['estado']) : '';

// Construcción dinámica de filtros SQL
$where_clauses = [];

if ($buscar !== '') {
    $buscar_clean = mysqli_real_escape_string($conn, $buscar);

    $where_clauses[] = "(
        ord.numero_orden LIKE '%$buscar_clean%' OR 
        cli.nombre LIKE '%$buscar_clean%' OR 
        ase.nombre LIKE '%$buscar_clean%' OR 
        fab.nombre LIKE '%$buscar_clean%' OR 
        ope.nombre LIKE '%$buscar_clean%' OR 
        pro.nombre_producto LIKE '%$buscar_clean%' OR 
        pro.codigo_referencia LIKE '%$buscar_clean%'
    )";
}

if ($estado_filtro !== '') {
    $estado_clean = mysqli_real_escape_string($conn, $estado_filtro);
    $where_clauses[] = "ord.estado = '$estado_clean'";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = " WHERE " . implode(' AND ', $where_clauses);
}

// 1. Obtener el número total de registros filtrados
$sql_count = "SELECT COUNT(*) as total FROM `ordenes_fabricacion` ord
INNER JOIN `personas` cli ON ord.cliente_id = cli.id_persona
INNER JOIN `personas` ase ON ord.asesor_id = ase.id_persona
INNER JOIN `personas` fab ON ord.fabricante_id = fab.id_persona
LEFT JOIN `personas` ope ON ord.operario_id = ope.id_persona
INNER JOIN productos_catalogo pro ON ord.producto_id = pro.id_producto" . $where_sql;

$result_count = mysqli_query($conn, $sql_count);
$fila_count = mysqli_fetch_assoc($result_count);
$total_registros = $fila_count['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);
?>

<div style="width:100%; padding-top:70px; text-align:center;">
    <h2 style="margin: 0; font-weight: bold;">O. FABRICACIÓN</h2>
</div>

<div style="width:100%; text-align:center; margin: 15px 0;">
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevo">
        Agregar Orden de Fabricación
        <span class="glyphicon glyphicon-plus"></span>
    </button>
</div>

<!-- FORMULARIO DE BÚSQUEDA AJAX -->
<div style="width:100%; text-align:center; margin-bottom:15px; padding: 0 15px;">
    <form onsubmit="event.preventDefault(); cargarTabla(1, $('#input_buscar').val(), $('#select_estado').val());" class="form-inline">
        <div class="form-group" style="margin-bottom: 8px;">
            <input type="text" id="input_buscar" class="form-control" placeholder="Buscar por número, cliente, producto..." value="<?php echo htmlspecialchars($buscar); ?>">
        </div>
        <div class="form-group" style="margin-bottom: 8px;">
            <select id="select_estado" class="form-control">
                <option value="">-- Todos los estados --</option>
                <option value="simulacion" <?php echo ($estado_filtro == 'simulacion') ? 'selected' : ''; ?>>Simulación</option>
                <option value="planeacion" <?php echo ($estado_filtro == 'planeacion') ? 'selected' : ''; ?>>Planeación</option>
                <option value="activa" <?php echo ($estado_filtro == 'activa') ? 'selected' : ''; ?>>Activa</option>
                <option value="en pasillo" <?php echo ($estado_filtro == 'en pasillo') ? 'selected' : ''; ?>>En Pasillo</option>
                <option value="en ejecucion" <?php echo ($estado_filtro == 'en ejecucion') ? 'selected' : ''; ?>>En Ejecución</option>
                <option value="suspendida" <?php echo ($estado_filtro == 'suspendida') ? 'selected' : ''; ?>>Suspendida</option>
                <option value="cancelada" <?php echo ($estado_filtro == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                <option value="terminada" <?php echo ($estado_filtro == 'terminada') ? 'selected' : ''; ?>>Terminada</option>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 8px;">
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-search"></span> Buscar
            </button>
            <?php if ($buscar !== '' || $estado_filtro !== ''): ?>
                <button type="button" onclick="cargarTabla(1, '', '')" class="btn btn-link">Limpiar filtros</button>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- TABLA DE DATOS -->
<div class="table-responsive">
    <table class="table table-hover table-condensed table-bordered">
        <thead>
            <tr>
                <th>Número Orden</th>
                <th>Hoja Taller</th>
                <th>Informe Costos</th>
                <th>Cliente</th>
                <th>Asesor</th>
                <th>Fabricante</th>
                <th>Operario</th>
                <th>Producto</th>
                <th>Unidades</th>
                <th>Estado</th>
                <th>Fecha Pedido</th>
                <th>Fecha Entrega</th>
                <th>% Utilidad</th>
                <th>Creado En</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT 
                ord.id_orden, ord.numero_orden, ord.cliente_id, ord.asesor_id, ord.fabricante_id,
                ord.operario_id, ord.producto_id, ord.unidades, ord.estado, ord.fecha_pedido,
                ord.fecha_entrega, ord.costo_subtotal, ord.costo_mod, ord.costo_cif,
                ord.porcentaje_utilidad, ord.monto_utilidad, ord.monto_total, ord.creado_en,
                cli.nombre AS nombre_cliente,
                ase.nombre AS nombre_asesor,
                fab.nombre AS nombre_fabricante,
                IFNULL(ope.nombre, 'Sin asignar') AS nombre_operario,
                pro.nombre_producto AS nombre_producto
            FROM `ordenes_fabricacion` ord
            INNER JOIN `personas` cli ON ord.cliente_id = cli.id_persona
            INNER JOIN `personas` ase ON ord.asesor_id = ase.id_persona
            INNER JOIN `personas` fab ON ord.fabricante_id = fab.id_persona
            LEFT JOIN `personas` ope ON ord.operario_id = ope.id_persona
            INNER JOIN productos_catalogo pro ON ord.producto_id = pro.id_producto
            $where_sql
            ORDER BY ord.id_orden DESC
            LIMIT $inicio, $registros_por_pagina;";

            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($fila = mysqli_fetch_assoc($result)) {
                    // Sanitización completa para prevenir fallos en comillas o caracteres especiales
                    $cadena_raw = $fila['id_orden'] . "||" .
                        $fila['numero_orden'] . "||" .
                        $fila['cliente_id'] . "||" .
                        $fila['asesor_id'] . "||" .
                        $fila['fabricante_id'] . "||" .
                        $fila['operario_id'] . "||" .
                        $fila['producto_id'] . "||" .
                        $fila['unidades'] . "||" .
                        $fila['estado'] . "||" .
                        $fila['fecha_pedido'] . "||" .
                        $fila['fecha_entrega'] . "||" .
                        $fila['costo_subtotal'] . "||" .
                        $fila['costo_mod'] . "||" .
                        $fila['costo_cif'] . "||" .
                        $fila['porcentaje_utilidad'] . "||" .
                        $fila['monto_utilidad'] . "||" .
                        $fila['monto_total'] . "||" .
                        $fila['creado_en'];

                    $datos = htmlspecialchars($cadena_raw, ENT_QUOTES, 'UTF-8');
            ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fila['numero_orden']); ?></td>
                        <td><a href="../../../operario/ver_pdf_taller.php?id_orden=<?php echo $fila['id_orden']; ?>">Ver hoja taller</a></td>
                        <td><a href="../../../control_costos/ver_costos_orden.php?id_orden=<?php echo $fila['id_orden']; ?>">Ver relación de costos</a></td>
                        <td><?php echo htmlspecialchars($fila['nombre_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($fila['nombre_asesor']); ?></td>
                        <td><?php echo htmlspecialchars($fila['nombre_fabricante']); ?></td>
                        <td><?php echo htmlspecialchars($fila['nombre_operario']); ?></td>
                        <td><?php echo htmlspecialchars($fila['nombre_producto']); ?></td>
                        <td><?php echo $fila['unidades']; ?></td>
                        <td><span class="label label-default"><?php echo ucfirst($fila['estado']); ?></span></td>
                        <td><?php echo $fila['fecha_pedido']; ?></td>
                        <td><?php echo $fila['fecha_entrega']; ?></td>
                        <td><?php echo $fila['porcentaje_utilidad']; ?>%</td>
                        <td><?php echo $fila['creado_en']; ?></td>
                        <td>
                            <a href="./orden_fabricacion_detalles.php?orden_id=<?php echo $fila['id_orden']; ?>" class="btn btn-info btn-sm">
                                <span class="glyphicon glyphicon-list-alt"></span> Ver Detalle
                            </a>
                        </td>
                        <td>
                            <button class="btn btn-warning glyphicon glyphicon-pencil" data-toggle="modal" data-target="#modalEdicion" onclick="agregaform('<?php echo $datos; ?>')"></button>
                        </td>
                        <td>
                            <button class="btn btn-danger glyphicon glyphicon-remove" onclick="preguntarSiNo('<?php echo $fila['id_orden']; ?>')"></button>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="17" style="text-align:center;">No se encontraron resultados</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- PAGINACIÓN DINÁMICA MEDIANTE AJAX -->
<?php if ($total_paginas > 1): ?>
    <div style="text-align:center; margin-top: 15px;">
        <ul class="pagination">
            <li class="<?php echo ($pagina_actual <= 1) ? 'disabled' : ''; ?>">
                <a href="javascript:void(0);" onclick="<?php echo ($pagina_actual > 1) ? "cargarTabla(" . ($pagina_actual - 1) . ", '$buscar', '$estado_filtro')" : "return false;"; ?>">&laquo; Anterior</a>
            </li>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="<?php echo ($pagina_actual == $i) ? 'active' : ''; ?>">
                    <a href="javascript:void(0);" onclick="cargarTabla(<?php echo $i; ?>, '<?php echo $buscar; ?>', '<?php echo $estado_filtro; ?>')"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <li class="<?php echo ($pagina_actual >= $total_paginas) ? 'disabled' : ''; ?>">
                <a href="javascript:void(0);" onclick="<?php echo ($pagina_actual < $total_paginas) ? "cargarTabla(" . ($pagina_actual + 1) . ", '$buscar', '$estado_filtro')" : "return false;"; ?>">Siguiente &raquo;</a>
            </li>
        </ul>
    </div>
<?php endif; ?>

<?php mysqli_close($conn); ?>
