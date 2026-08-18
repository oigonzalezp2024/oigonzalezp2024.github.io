<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Órdenes de Fabricación</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?php include('librerias.php'); ?>
    <script src="../controlador/funciones_ordenes_fabricacion.js"></script>
</head>
<body id="body">
    <?php include 'header.php'; ?>

    <!-- Contenedor dinámico donde se carga la tabla mediante AJAX -->
    <div id="tabla"></div>

    <!-- MODAL PARA INSERTAR REGISTROS -->
    <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevoLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalNuevoLabel">Agregar Órden de Fabricación</h4>
                </div>
                <div class="modal-body">
                    <label for="numero_orden">Número de Orden</label>
                    <input type="text" id="numero_orden" class="form-control input-sm" placeholder="ORD-2026-XXXX" required>

                    <label for="cliente_id">Cliente</label>
                    <select id="cliente_id" class="form-control input-sm" required></select>

                    <label for="asesor_id">Asesor</label>
                    <select id="asesor_id" class="form-control input-sm" required></select>

                    <label for="fabricante_id">Fabricante</label>
                    <select id="fabricante_id" class="form-control input-sm" required></select>

                    <label for="operario_id">Operario (Opcional)</label>
                    <select id="operario_id" class="form-control input-sm"></select>

                    <label for="producto_id">Producto</label>
                    <select id="producto_id" class="form-control input-sm" required></select>

                    <label for="unidades">Unidades</label>
                    <input type="number" id="unidades" class="form-control input-sm" value="1" required>

                    <label for="estado">Estado</label>
                    <select id="estado" class="form-control input-sm">
                        <option value="simulacion">Simulación</option>
                        <option value="planeacion" selected>Planeación</option>
                        <option value="activa">Activa</option>
                        <option value="en pasillo">En Pasillo</option>
                        <option value="en ejecucion">En Ejecución</option>
                        <option value="suspendida">Suspendida</option>
                        <option value="cancelada">Cancelada</option>
                        <option value="terminada">Terminada</option>
                    </select>

                    <label for="fecha_pedido">Fecha de Pedido</label>
                    <input type="date" id="fecha_pedido" class="form-control input-sm" required>

                    <label for="fecha_entrega">Fecha de Entrega</label>
                    <input type="date" id="fecha_entrega" class="form-control input-sm" required>
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
                    <h4 class="modal-title" id="modalEdicionLabel">Actualizar Orden</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_ordenu">

                    <label for="numero_ordenu">Número de Orden</label>
                    <input type="text" id="numero_ordenu" class="form-control input-sm" required>

                    <label for="cliente_idu">Cliente</label>
                    <select id="cliente_idu" class="form-control input-sm" required></select>

                    <label for="asesor_idu">Asesor</label>
                    <select id="asesor_idu" class="form-control input-sm" required></select>

                    <label for="fabricante_idu">Fabricante</label>
                    <select id="fabricante_idu" class="form-control input-sm" required></select>

                    <label for="operario_idu">Operario</label>
                    <select id="operario_idu" class="form-control input-sm"></select>

                    <label for="producto_idu">Producto</label>
                    <select id="producto_idu" class="form-control input-sm" required></select>

                    <label for="unidadesu">Unidades</label>
                    <input type="number" id="unidadesu" class="form-control input-sm" required>

                    <label for="estadou">Estado</label>
                    <select id="estadou" class="form-control input-sm">
                        <option value="simulacion">Simulación</option>
                        <option value="planeacion">Planeación</option>
                        <option value="activa">Activa</option>
                        <option value="en pasillo">En Pasillo</option>
                        <option value="en ejecucion">En Ejecución</option>
                        <option value="suspendida">Suspendida</option>
                        <option value="cancelada">Cancelada</option>
                        <option value="terminada">Terminada</option>
                    </select>

                    <label for="fecha_pedidou">Fecha de Pedido</label>
                    <input type="date" id="fecha_pedidou" class="form-control input-sm" required>

                    <label for="fecha_entregau">Fecha de Entrega</label>
                    <input type="date" id="fecha_entregau" class="form-control input-sm" required>

                    <label for="costo_subtotalu">Costo Subtotal</label>
                    <input type="number" step="0.01" id="costo_subtotalu" class="form-control input-sm">

                    <label for="costo_modu">Costo MOD</label>
                    <input type="number" step="0.01" id="costo_modu" class="form-control input-sm">

                    <label for="costo_cifu">Costo CIF</label>
                    <input type="number" step="0.01" id="costo_cifu" class="form-control input-sm">

                    <label for="porcentaje_utilidadu">% Utilidad</label>
                    <input type="number" step="0.01" id="porcentaje_utilidadu" class="form-control input-sm">

                    <label for="monto_utilidadu">Monto Utilidad</label>
                    <input type="number" step="0.01" id="monto_utilidadu" class="form-control input-sm">

                    <label for="monto_totalu">Monto Total</label>
                    <input type="number" step="0.01" id="monto_totalu" class="form-control input-sm">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="actualizadatos">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT CONTROLADOR DE AJAX Y EVENTOS -->
    <script type="text/javascript">
        function cargarTabla(pagina = 1, buscar = '', estado = '') {
            var url = 'componentes/vista_ordenes_fabricacion.php?pagina=' + pagina + '&buscar=' + encodeURIComponent(buscar) + '&estado=' + encodeURIComponent(estado);
            $('#tabla').load(url);
        }

        function cargarDesplegablesFormulario() {
            $.ajax({
                url: '../modelo/obtener_opciones_ordenes.php',
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        var data = res.data;

                        function llenarSelect(selectId, items, valueKey, textKey, defaultText, esOpcional) {
                            var $select = $(selectId);
                            $select.empty();
                            
                            if (esOpcional) {
                                $select.append('<option value="">' + defaultText + '</option>');
                            } else {
                                $select.append('<option value="" disabled selected>' + defaultText + '</option>');
                            }

                            $.each(items, function (i, item) {
                                var texto = item[textKey];
                                if (item.codigo_referencia) {
                                    texto += ' (' + item.codigo_referencia + ')';
                                }
                                $select.append('<option value="' + item[valueKey] + '">' + texto + '</option>');
                            });
                        }

                        // Modal Crear
                        llenarSelect('#cliente_id', data.clientes, 'id_persona', 'nombre', '-- Seleccione Cliente --', false);
                        llenarSelect('#asesor_id', data.asesores, 'id_persona', 'nombre', '-- Seleccione Asesor --', false);
                        llenarSelect('#fabricante_id', data.fabricantes, 'id_persona', 'nombre', '-- Seleccione Fabricante --', false);
                        llenarSelect('#operario_id', data.operarios, 'id_persona', 'nombre', '-- Sin Asignar --', true);
                        llenarSelect('#producto_id', data.productos, 'id_producto', 'nombre_producto', '-- Seleccione Producto --', false);

                        // Modal Edición
                        llenarSelect('#cliente_idu', data.clientes, 'id_persona', 'nombre', '-- Seleccione Cliente --', false);
                        llenarSelect('#asesor_idu', data.asesores, 'id_persona', 'nombre', '-- Seleccione Asesor --', false);
                        llenarSelect('#fabricante_idu', data.fabricantes, 'id_persona', 'nombre', '-- Seleccione Fabricante --', false);
                        llenarSelect('#operario_idu', data.operarios, 'id_persona', 'nombre', '-- Sin Asignar --', true);
                        llenarSelect('#producto_idu', data.productos, 'id_producto', 'nombre_producto', '-- Seleccione Producto --', false);
                    }
                }
            });
        }

        $(document).ready(function () {
            cargarTabla();
            cargarDesplegablesFormulario(); // Ejecución al inicializar la vista

            $('#guardarnuevo').click(function () {
                // Mantiene la recolección de .val() de los elementos
                var numero_orden = $('#numero_orden').val();
                var cliente_id = $('#cliente_id').val();
                var asesor_id = $('#asesor_id').val();
                var fabricante_id = $('#fabricante_id').val();
                var operario_id = $('#operario_id').val();
                var producto_id = $('#producto_id').val();
                var unidades = $('#unidades').val();
                var estado = $('#estado').val();
                var fecha_pedido = $('#fecha_pedido').val();
                var fecha_entrega = $('#fecha_entrega').val();
                agregardatos(numero_orden, cliente_id, asesor_id, fabricante_id, operario_id, producto_id, unidades, estado, fecha_pedido, fecha_entrega);
            });

            $('#actualizadatos').click(function () {
                actualizaDatos();
            });
        });
    </script>

    <?php include './footer.php'; ?>
</body>
</html>
