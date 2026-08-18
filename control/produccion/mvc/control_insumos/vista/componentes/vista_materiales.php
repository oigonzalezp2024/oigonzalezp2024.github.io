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
$categoria_filtro = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';

// Construcción dinámica de filtros SQL
$where_clauses = [];

if ($buscar !== '') {
    $buscar_clean = mysqli_real_escape_string($conn, $buscar);
    $where_clauses[] = "(
        m.codigo_material LIKE '%$buscar_clean%' OR 
        m.descripcion_material LIKE '%$buscar_clean%' OR 
        m.unidad_medida LIKE '%$buscar_clean%'
    )";
}

if ($categoria_filtro !== '') {
    $categoria_clean = mysqli_real_escape_string($conn, $categoria_filtro);
    $where_clauses[] = "m.categoria_id = '$categoria_clean'";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = " WHERE " . implode(' AND ', $where_clauses);
}

// Total de registros filtrados
$sql_count = "SELECT COUNT(*) as total FROM `materiales` m
              LEFT JOIN `categorias_insumos` ci ON ci.id_categoria = m.categoria_id" . $where_sql;

$result_count = mysqli_query($conn, $sql_count);
$fila_count = mysqli_fetch_assoc($result_count);
$total_registros = $fila_count['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);
?>

<div style="width:100%; padding-top:70px; text-align:center;">
    <h2 style="margin: 0; font-weight: bold;">MATERIALES E INSUMOS</h2>
</div>

<div style="width:100%; text-align:center; margin: 15px 0;">
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevo">
        Agregar material
        <span class="glyphicon glyphicon-plus"></span>
    </button>
</div>

<!-- FORMULARIO DE BÚSQUEDA AJAX -->
<div style="width:100%; text-align:center; margin-bottom:15px; padding: 0 15px;">
    <form onsubmit="event.preventDefault(); cargarTabla(1, $('#input_buscar').val(), $('#select_categoria').val());" class="form-inline">
        <div class="form-group" style="margin-bottom: 8px;">
            <input type="text" id="input_buscar" class="form-control" placeholder="Buscar por código, descripción..." value="<?php echo htmlspecialchars($buscar); ?>">
        </div>
        <div class="form-group" style="margin-bottom: 8px;">
            <select id="select_categoria" class="form-control">
                <option value="">-- Todas las categorías --</option>
                <?php
                $res_cat = mysqli_query($conn, "SELECT id_categoria, nombre_categoria FROM categorias_insumos ORDER BY nombre_categoria ASC");
                while ($cat = mysqli_fetch_assoc($res_cat)) {
                    $selected = ($categoria_filtro == $cat['id_categoria']) ? 'selected' : '';
                    echo "<option value='".$cat['id_categoria']."' $selected>".$cat['nombre_categoria']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 8px;">
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-search"></span> Buscar
            </button>
            <?php if ($buscar !== '' || $categoria_filtro !== ''): ?>
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
                <th>ID</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>U. Medida</th>
                <th>Precio Defecto</th>
                <th>Categoría</th>
                <th>Stock Mín.</th>
                <th>Stock Act.</th>
                <th>Stock Máx.</th>
                <th>Creado En</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT 
                      m.id_material,
                      m.codigo_material,
                      m.descripcion_material,
                      m.unidad_medida,
                      m.precio_unitario_defecto,
                      m.categoria_id,
                      m.stock_minimo,
                      m.stock_actual,
                      m.stock_maximo,
                      m.creado_en,
                      IFNULL(ci.nombre_categoria, 'Sin Categoría') AS ci_nombre_categoria
                    FROM `materiales` m
                    LEFT JOIN `categorias_insumos` ci ON ci.id_categoria = m.categoria_id
                    $where_sql
                    ORDER BY m.id_material DESC
                    LIMIT $inicio, $registros_por_pagina;";

            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($fila = mysqli_fetch_assoc($result)) {
                    $cadena_raw = $fila['id_material'] . "||" .
                        $fila['codigo_material'] . "||" .
                        $fila['descripcion_material'] . "||" .
                        $fila['unidad_medida'] . "||" .
                        $fila['precio_unitario_defecto'] . "||" .
                        $fila['categoria_id'] . "||" .
                        $fila['stock_minimo'] . "||" .
                        $fila['stock_actual'] . "||" .
                        $fila['stock_maximo'];

                    $datos = htmlspecialchars($cadena_raw, ENT_QUOTES, 'UTF-8');
            ?>
                    <tr>
                        <td><?php echo $fila['id_material']; ?></td>
                        <td><?php echo htmlspecialchars($fila['codigo_material']); ?></td>
                        <td><?php echo htmlspecialchars($fila['descripcion_material']); ?></td>
                        <td><?php echo htmlspecialchars($fila['unidad_medida']); ?></td>
                        <td>$<?php echo number_format($fila['precio_unitario_defecto'], 2); ?></td>
                        <td><?php echo htmlspecialchars($fila['ci_nombre_categoria']); ?></td>
                        <td><?php echo $fila['stock_minimo']; ?></td>
                        <td><?php echo $fila['stock_actual']; ?></td>
                        <td><?php echo $fila['stock_maximo']; ?></td>
                        <td><?php echo $fila['creado_en']; ?></td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm glyphicon glyphicon-pencil" data-toggle="modal" data-target="#modalEdicion" onclick="agregaform('<?php echo $datos; ?>')"></button>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm glyphicon glyphicon-remove" onclick="preguntarSiNo('<?php echo $fila['id_material']; ?>')"></button>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="12" style="text-align:center;">No se encontraron resultados</td></tr>';
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
                <a href="javascript:void(0);" onclick="<?php echo ($pagina_actual > 1) ? "cargarTabla(" . ($pagina_actual - 1) . ", '$buscar', '$categoria_filtro')" : "return false;"; ?>">&laquo; Anterior</a>
            </li>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="<?php echo ($pagina_actual == $i) ? 'active' : ''; ?>">
                    <a href="javascript:void(0);" onclick="cargarTabla(<?php echo $i; ?>, '<?php echo $buscar; ?>', '<?php echo $categoria_filtro; ?>')"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <li class="<?php echo ($pagina_actual >= $total_paginas) ? 'disabled' : ''; ?>">
                <a href="javascript:void(0);" onclick="<?php echo ($pagina_actual < $total_paginas) ? "cargarTabla(" . ($pagina_actual + 1) . ", '$buscar', '$categoria_filtro')" : "return false;"; ?>">Siguiente &raquo;</a>
            </li>
        </ul>
    </div>
<?php endif; ?>

<?php mysqli_close($conn); ?>
