<?php
// Validar que se reciba el orden_id por GET
$orden_id = isset($_GET['orden_id']) ? (int)$_GET['orden_id'] : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de la Orden #<?php echo $orden_id; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?php include('librerias.php'); ?>
    <script src="../controlador/funciones_orden_fabricacion_detalles.js"></script>
</head>
<body id="body">
    <?php include 'header.php'; ?>

    <div class="container" style="padding-top: 20px;">
        <!-- Botón para regresar al listado general de órdenes -->
        <a href="index.php" class="btn btn-default" style="margin-bottom: 15px;">
            <span class="glyphicon glyphicon-arrow-left"></span> Volver a Órdenes
        </a>

        <!-- Contenedor dinámico donde se carga la tabla de detalles -->
        <div id="tabla"></div>
    </div>

    <!-- MODAL PARA INSERTAR NUEVO DETALLE -->
    <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevoLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalNuevoLabel">Agregar Material/Detalle</h4>
                </div>
                <div class="modal-body">
                    <!-- Campo oculto o bloqueado con el orden_id actual -->
                    <label for="orden_id">ID Órden</label>
                    <input type="number" id="orden_id" class="form-control input-sm" value="<?php echo $orden_id; ?>" readonly>

                    <label for="material_id">ID Material</label>
                    <input type="number" id="material_id" class="form-control input-sm" required>

                    <label for="medidas">Medidas</label>
                    <textarea id="medidas" rows="3" class="form-control input-sm" required></textarea>

                    <label for="cantidad">Cantidad</label>
                    <input type="number" step="0.01" id="cantidad" class="form-control input-sm" value="1.00" required>

                    <label for="cantidad_consumida">Cantidad Consumida</label>
                    <input type="number" step="0.01" id="cantidad_consumida" class="form-control input-sm" value="0.00" required>

                    <label for="valor_unitario">Valor Unitario</label>
                    <input type="number" step="0.01" id="valor_unitario" class="form-control input-sm" value="0.00" required>

                    <label for="valor_total">Valor Total</label>
                    <input type="number" step="0.01" id="valor_total" class="form-control input-sm" value="0.00" required>

                    <label for="es_destacado">Es Destacado</label>
                    <select id="es_destacado" class="form-control input-sm">
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo">
                        Agregar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA EDICIÓN DE DATOS -->
    <div class="modal fade" id="modalEdicion" tabindex="-1" role="dialog" aria-labelledby="modalEdicionLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalEdicionLabel">Actualizar Detalle</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_detalleu">

                    <label for="orden_idu">ID Órden</label>
                    <input type="number" id="orden_idu" class="form-control input-sm" readonly>

                    <label for="material_idu">ID Material</label>
                    <input type="number" id="material_idu" class="form-control input-sm" required>

                    <label for="medidasu">Medidas</label>
                    <textarea id="medidasu" rows="3" class="form-control input-sm" required></textarea>

                    <label for="cantidadu">Cantidad</label>
                    <input type="number" step="0.01" id="cantidadu" class="form-control input-sm" required>

                    <label for="cantidad_consumidau">Cantidad Consumida</label>
                    <input type="number" step="0.01" id="cantidad_consumidau" class="form-control input-sm" required>

                    <label for="valor_unitariou">Valor Unitario</label>
                    <input type="number" step="0.01" id="valor_unitariou" class="form-control input-sm" required>

                    <label for="valor_totalu">Valor Total</label>
                    <input type="number" step="0.01" id="valor_totalu" class="form-control input-sm" required>

                    <label for="es_destacadou">Es Destacado</label>
                    <select id="es_destacadou" class="form-control input-sm">
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="actualizadatos">
                        Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTROLADOR AJAX -->
    <script type="text/javascript">
        var ordenIdActual = <?php echo $orden_id; ?>;

        function cargarTabla() {
            var url = 'componentes/vista_orden_fabricacion_detalles.php?orden_id=' + ordenIdActual;
            $('#tabla').load(url);
        }

        $(document).ready(function () {
            // Carga inicial filtrando explícitamente por la orden enviada por GET
            cargarTabla();

            $('#guardarnuevo').click(function () {
                var orden_id = $('#orden_id').val();
                var material_id = $('#material_id').val();
                var medidas = $('#medidas').val();
                var cantidad = $('#cantidad').val();
                var cantidad_consumida = $('#cantidad_consumida').val();
                var valor_unitario = $('#valor_unitario').val();
                var valor_total = $('#valor_total').val();
                var es_destacado = $('#es_destacado').val();

                agregardatos(orden_id, material_id, medidas, cantidad, cantidad_consumida, valor_unitario, valor_total, es_destacado);
            });

            $('#actualizadatos').click(function () {
                modificarCliente();
            });
        });
    </script>

    <?php include './footer.php'; ?>
</body>
</html>
