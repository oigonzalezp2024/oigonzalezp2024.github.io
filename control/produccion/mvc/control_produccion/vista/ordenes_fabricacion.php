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

                    <label for="cliente_id">Cliente ID</label>
                    <input type="number" id="cliente_id" class="form-control input-sm" required>

                    <label for="asesor_id">Asesor ID</label>
                    <input type="number" id="asesor_id" class="form-control input-sm" required>

                    <label for="fabricante_id">Fabricante ID</label>
                    <input type="number" id="fabricante_id" class="form-control input-sm" required>

                    <label for="operario_id">Operario ID (Opcional)</label>
                    <input type="number" id="operario_id" class="form-control input-sm">

                    <label for="producto_id">Producto ID</label>
                    <input type="number" id="producto_id" class="form-control input-sm" required>

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

                    <label for="costo_subtotal">Costo Subtotal</label>
                    <input type="number" step="0.01" id="costo_subtotal" class="form-control input-sm" value="0.00">

                    <label for="costo_mod">Costo MOD</label>
                    <input type="number" step="0.01" id="costo_mod" class="form-control input-sm" value="0.00">

                    <label for="costo_cif">Costo CIF</label>
                    <input type="number" step="0.01" id="costo_cif" class="form-control input-sm" value="0.00">

                    <label for="porcentaje_utilidad">% Utilidad</label>
                    <input type="number" step="0.01" id="porcentaje_utilidad" class="form-control input-sm" value="0.00">

                    <label for="monto_utilidad">Monto Utilidad</label>
                    <input type="number" step="0.01" id="monto_utilidad" class="form-control input-sm" value="0.00">

                    <label for="monto_total">Monto Total</label>
                    <input type="number" step="0.01" id="monto_total" class="form-control input-sm" value="0.00">
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

                    <label for="cliente_idu">Cliente ID</label>
                    <input type="number" id="cliente_idu" class="form-control input-sm" required>

                    <label for="asesor_idu">Asesor ID</label>
                    <input type="number" id="asesor_idu" class="form-control input-sm" required>

                    <label for="fabricante_idu">Fabricante ID</label>
                    <input type="number" id="fabricante_idu" class="form-control input-sm" required>

                    <label for="operario_idu">Operario ID</label>
                    <input type="number" id="operario_idu" class="form-control input-sm">

                    <label for="producto_idu">Producto ID</label>
                    <input type="number" id="producto_idu" class="form-control input-sm" required>

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

        $(document).ready(function () {
            cargarTabla();

            $('#guardarnuevo').click(function () {
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
                var costo_subtotal = $('#costo_subtotal').val();
                var costo_mod = $('#costo_mod').val();
                var costo_cif = $('#costo_cif').val();
                var porcentaje_utilidad = $('#porcentaje_utilidad').val();
                var monto_utilidad = $('#monto_utilidad').val();
                var monto_total = $('#monto_total').val();

                agregardatos(numero_orden, cliente_id, asesor_id, fabricante_id, operario_id, producto_id, unidades, estado, fecha_pedido, fecha_entrega, costo_subtotal, costo_mod, costo_cif, porcentaje_utilidad, monto_utilidad, monto_total);
            });

            $('#actualizadatos').click(function () {
                actualizaDatos();
            });
        });
    </script>

    <?php include './footer.php'; ?>
</body>
</html>
