<!DOCTYPE html>
<html>
    <head>
	<meta charset="UTF-8">
	<title>Clientes</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php
	include('librerias.php');
	?>
	<script src="../controlador/funciones_movimientos_inventario.js"></script>
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
			<label>id_movimiento</label>
			<input type="number" id="id_movimiento" class="form-control input-sm" required="">
			<label>tipo_item</label>
			<input type="" id="tipo_item" class="form-control input-sm" required="">
			<label>id_item</label>
			<input type="number" id="id_item" class="form-control input-sm" required="">
			<label>tipo_movimiento</label>
			<input type="" id="tipo_movimiento" class="form-control input-sm" required="">
			<label>cantidad</label>
			<input type="" id="cantidad" class="form-control input-sm" required="">
			<label>orden_id</label>
			<input type="number" id="orden_id" class="form-control input-sm" required="">
			<label>observacion</label>
			<textarea id="observacion" rows="4" cols="50"class="form-control input-sm" required=""></textarea>
			<label>fecha_movimiento</label>
			<input type="" id="fecha_movimiento" class="form-control input-sm" required="">
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
			<input type="number" hidden="" id="id_movimientou">
			<label>tipo_item</label>
			<input type="" id="tipo_itemu" class="form-control input-sm" required="">
			<label>id_item</label>
			<input type="number" id="id_itemu" class="form-control input-sm" required="">
			<label>tipo_movimiento</label>
			<input type="" id="tipo_movimientou" class="form-control input-sm" required="">
			<label>cantidad</label>
			<input type="" id="cantidadu" class="form-control input-sm" required="">
			<label>orden_id</label>
			<input type="number" id="orden_idu" class="form-control input-sm" required="">
			<label>observacion</label>
			<textarea id="observacionu" rows="4" cols="50" class="form-control input-sm" required=""></textarea>
			<label>fecha_movimiento</label>
			<input type="" id="fecha_movimientou" class="form-control input-sm" required="">
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
		$('#tabla').load('componentes/vista_movimientos_inventario.php');
	    });
	</script>
	<script type="text/javascript">
	    $(document).ready(function () {
		$('#guardarnuevo').click(function () {
		    id_movimiento = $('#id_movimiento').val();
		    tipo_item = $('#tipo_item').val();
		    id_item = $('#id_item').val();
		    tipo_movimiento = $('#tipo_movimiento').val();
		    cantidad = $('#cantidad').val();
		    orden_id = $('#orden_id').val();
		    observacion = $('#observacion').val();
		    fecha_movimiento = $('#fecha_movimiento').val();
		    agregardatos(id_movimiento, tipo_item, id_item, tipo_movimiento, cantidad, orden_id, observacion, fecha_movimiento);
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
