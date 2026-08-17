<!DOCTYPE html>
<html>
    <head>
	<meta charset="UTF-8">
	<title>Clientes</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php
	include('librerias.php');
	?>
	<script src="../controlador/funciones_ordenes_fabricacion.js"></script>
    </head>
    <body id="body">
	<?php
	include 'header.php';
	?>
	<div class="container">
	    <div id="tabla"></div>
	</div>
	<!-- MODAL PARA INSERTAR REGISTROS -->
	<div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title" id="myModalLabel">Agregar cliente</h4>
		    </div>
		    <div class="modal-body">
			<label>id_orden</label>
			<input type="number" id="id_orden" class="form-control input-sm" required="">
			<label>numero_orden</label>
			<textarea id="numero_orden" rows="4" cols="50"class="form-control input-sm" required=""></textarea>
			<label>cliente_id</label>
			<input type="number" id="cliente_id" class="form-control input-sm" required="">
			<label>asesor_id</label>
			<input type="number" id="asesor_id" class="form-control input-sm" required="">
			<label>fabricante_id</label>
			<input type="number" id="fabricante_id" class="form-control input-sm" required="">
			<label>operario_id</label>
			<input type="number" id="operario_id" class="form-control input-sm" required="">
			<label>producto_id</label>
			<input type="number" id="producto_id" class="form-control input-sm" required="">
			<label>unidades</label>
			<input type="number" id="unidades" class="form-control input-sm" required="">
			<label>estado</label>
			<input type="" id="estado" class="form-control input-sm" required="">
			<label>fecha_pedido</label>
			<input type="date" id="fecha_pedido" class="form-control input-sm" required="">
			<label>fecha_entrega</label>
			<input type="date" id="fecha_entrega" class="form-control input-sm" required="">
			<label>costo_subtotal</label>
			<input type="" id="costo_subtotal" class="form-control input-sm" required="">
			<label>costo_mod</label>
			<input type="" id="costo_mod" class="form-control input-sm" required="">
			<label>costo_cif</label>
			<input type="" id="costo_cif" class="form-control input-sm" required="">
			<label>porcentaje_utilidad</label>
			<input type="" id="porcentaje_utilidad" class="form-control input-sm" required="">
			<label>monto_utilidad</label>
			<input type="" id="monto_utilidad" class="form-control input-sm" required="">
			<label>monto_total</label>
			<input type="" id="monto_total" class="form-control input-sm" required="">
			<label>creado_en</label>
			<input type="" id="creado_en" class="form-control input-sm" required="">
			</div>
		    <div class="modal-footer">
			<button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo">
			    Agregar
			</button>
		    </div>
		</div>
	    </div>
	</div>
	<!-- MODAL PARA EDICION DE DATOS-->
	<div class="modal fade" id="modalEdicion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title" id="myModalLabel">Actualizar datos</h4>
		    </div>
		    <div class="modal-body">
			<input type="number" hidden="" id="id_ordenu">
			<label>numero_orden</label>
			<textarea id="numero_ordenu" rows="4" cols="50" class="form-control input-sm" required=""></textarea>
			<label>cliente_id</label>
			<input type="number" id="cliente_idu" class="form-control input-sm" required="">
			<label>asesor_id</label>
			<input type="number" id="asesor_idu" class="form-control input-sm" required="">
			<label>fabricante_id</label>
			<input type="number" id="fabricante_idu" class="form-control input-sm" required="">
			<label>operario_id</label>
			<input type="number" id="operario_idu" class="form-control input-sm" required="">
			<label>producto_id</label>
			<input type="number" id="producto_idu" class="form-control input-sm" required="">
			<label>unidades</label>
			<input type="number" id="unidadesu" class="form-control input-sm" required="">
			<label>estado</label>
			<input type="" id="estadou" class="form-control input-sm" required="">
			<label>fecha_pedido</label>
			<input type="date" id="fecha_pedidou" class="form-control input-sm" required="">
			<label>fecha_entrega</label>
			<input type="date" id="fecha_entregau" class="form-control input-sm" required="">
			<label>costo_subtotal</label>
			<input type="" id="costo_subtotalu" class="form-control input-sm" required="">
			<label>costo_mod</label>
			<input type="" id="costo_modu" class="form-control input-sm" required="">
			<label>costo_cif</label>
			<input type="" id="costo_cifu" class="form-control input-sm" required="">
			<label>porcentaje_utilidad</label>
			<input type="" id="porcentaje_utilidadu" class="form-control input-sm" required="">
			<label>monto_utilidad</label>
			<input type="" id="monto_utilidadu" class="form-control input-sm" required="">
			<label>monto_total</label>
			<input type="" id="monto_totalu" class="form-control input-sm" required="">
			<label>creado_en</label>
			<input type="" id="creado_enu" class="form-control input-sm" required="">
			</div>
		    <div class="modal-footer">
			<button type="button" class="btn btn-warning" data-dismiss="modal" id="actualizadatos">
			    Actualizar
			</button>
		    </div>
		</div>
	    </div>
	</div>
	<script type="text/javascript">
	    $(document).ready(function () {
		$('#tabla').load('componentes/vista_ordenes_fabricacion.php');
	    });
	</script>
	<script type="text/javascript">
	    $(document).ready(function () {
		$('#guardarnuevo').click(function () {
		    id_orden = $('#id_orden').val();
		    numero_orden = $('#numero_orden').val();
		    cliente_id = $('#cliente_id').val();
		    asesor_id = $('#asesor_id').val();
		    fabricante_id = $('#fabricante_id').val();
		    operario_id = $('#operario_id').val();
		    producto_id = $('#producto_id').val();
		    unidades = $('#unidades').val();
		    estado = $('#estado').val();
		    fecha_pedido = $('#fecha_pedido').val();
		    fecha_entrega = $('#fecha_entrega').val();
		    costo_subtotal = $('#costo_subtotal').val();
		    costo_mod = $('#costo_mod').val();
		    costo_cif = $('#costo_cif').val();
		    porcentaje_utilidad = $('#porcentaje_utilidad').val();
		    monto_utilidad = $('#monto_utilidad').val();
		    monto_total = $('#monto_total').val();
		    creado_en = $('#creado_en').val();
		    agregardatos(id_orden, numero_orden, cliente_id, asesor_id, fabricante_id, operario_id, producto_id, unidades, estado, fecha_pedido, fecha_entrega, costo_subtotal, costo_mod, costo_cif, porcentaje_utilidad, monto_utilidad, monto_total, creado_en);
		});
		$('#actualizadatos').click(function () {
		    modificarCliente();
		});
	    });
	</script>
	<?php
	include './footer.php';
	?>
    </body>
</html>
