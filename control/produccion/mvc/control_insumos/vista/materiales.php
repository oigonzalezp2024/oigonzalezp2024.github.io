<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Materiales</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?php include('librerias.php'); ?>
    <script src="../controlador/funciones_materiales.js"></script>
</head>
<body id="body">
    <?php include 'header.php'; ?>

    <!-- Contenedor dinámico cargado vía AJAX -->
    <div id="tabla"></div>

    <!-- MODAL PARA INSERTAR REGISTROS -->
    <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevoLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalNuevoLabel">Agregar Material</h4>
                </div>
                <div class="modal-body">
                    <label for="codigo_material">Código Material</label>
                    <input type="text" id="codigo_material" class="form-control input-sm" required>

                    <label for="descripcion_material">Descripción</label>
                    <textarea id="descripcion_material" rows="3" class="form-control input-sm" required></textarea>

                    <label for="unidad_medida">Unidad Medida</label>
                    <input type="text" id="unidad_medida" class="form-control input-sm" placeholder="Ej: MTS, PLIEGO, UND" required>

                    <label for="precio_unitario_defecto">Precio Unitario Defecto</label>
                    <input type="number" step="0.01" id="precio_unitario_defecto" class="form-control input-sm" value="0.00" required>

                    <label for="categoria_id">Categoría</label>
                    <select id="categoria_id" class="form-control input-sm" required>
                        <option value="">-- Seleccione Categoría --</option>
                        <?php
                        include_once '../modelo/conexion.php';
                        $c = conexion();
                        $res_cat = mysqli_query($c, "SELECT id_categoria, nombre_categoria FROM categorias_insumos ORDER BY nombre_categoria ASC");
                        while ($cat = mysqli_fetch_assoc($res_cat)) {
                            echo "<option value='".$cat['id_categoria']."'>".$cat['nombre_categoria']."</option>";
                        }
                        mysqli_close($c);
                        ?>
                    </select>

                    <label for="stock_minimo">Stock Mínimo</label>
                    <input type="number" step="0.01" id="stock_minimo" class="form-control input-sm" value="0.00" required>

                    <label for="stock_actual">Stock Actual</label>
                    <input type="number" step="0.01" id="stock_actual" class="form-control input-sm" value="0.00" required>

                    <label for="stock_maximo">Stock Máximo</label>
                    <input type="number" step="0.01" id="stock_maximo" class="form-control input-sm" value="0.00" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA EDICIÓN DE DATOS -->
    <div class="modal fade" id="modalEdicion" tabindex="-1" role="dialog" aria-labelledby="modalEdicionLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalEdicionLabel">Actualizar Material</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_materialu">

                    <label for="codigo_materialu">Código Material</label>
                    <input type="text" id="codigo_materialu" class="form-control input-sm" required>

                    <label for="descripcion_materialu">Descripción</label>
                    <textarea id="descripcion_materialu" rows="3" class="form-control input-sm" required></textarea>

                    <label for="unidad_medidau">Unidad Medida</label>
                    <input type="text" id="unidad_medidau" class="form-control input-sm" required>

                    <label for="precio_unitario_defectou">Precio Unitario Defecto</label>
                    <input type="number" step="0.01" id="precio_unitario_defectou" class="form-control input-sm" required>

                    <label for="categoria_idu">Categoría</label>
                    <select id="categoria_idu" class="form-control input-sm" required>
                        <option value="">-- Seleccione Categoría --</option>
                        <?php
                        $c = conexion();
                        $res_cat = mysqli_query($c, "SELECT id_categoria, nombre_categoria FROM categorias_insumos ORDER BY nombre_categoria ASC");
                        while ($cat = mysqli_fetch_assoc($res_cat)) {
                            echo "<option value='".$cat['id_categoria']."'>".$cat['nombre_categoria']."</option>";
                        }
                        mysqli_close($c);
                        ?>
                    </select>

                    <label for="stock_minimou">Stock Mínimo</label>
                    <input type="number" step="0.01" id="stock_minimou" class="form-control input-sm" required>

                    <label for="stock_actualu">Stock Actual</label>
                    <input type="number" step="0.01" id="stock_actualu" class="form-control input-sm" required>

                    <label for="stock_maximou">Stock Máximo</label>
                    <input type="number" step="0.01" id="stock_maximou" class="form-control input-sm" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="actualizadatos">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT CONTROLADOR DE AJAX Y EVENTOS -->
    <script type="text/javascript">
        function cargarTabla(pagina = 1, buscar = '', categoria = '') {
            var url = 'componentes/vista_materiales.php?pagina=' + pagina + 
                      '&buscar=' + encodeURIComponent(buscar) + 
                      '&categoria=' + encodeURIComponent(categoria);
            $('#tabla').load(url);
        }

        $(document).ready(function () {
            cargarTabla();

            $('#guardarnuevo').click(function () {
                agregardatos(
                    $('#codigo_material').val(),
                    $('#descripcion_material').val(),
                    $('#unidad_medida').val(),
                    $('#precio_unitario_defecto').val(),
                    $('#categoria_id').val(),
                    $('#stock_minimo').val(),
                    $('#stock_actual').val(),
                    $('#stock_maximo').val()
                );
            });

            $('#actualizadatos').click(function () {
                modificarMaterial();
            });
        });
    </script>

    <?php include './footer.php'; ?>
</body>
</html>
