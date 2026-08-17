<!DOCTYPE html>
<html>
    <head>
	<meta charset="UTF-8">
	<title>Clientes</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php
	include('librerias.php');
	?>
	<script src="../controlador/funciones_inventario_producto_terminado.js"></script>
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
			<label>id_inventario_pt</label>
			<input type="number" id="id_inventario_pt" class="form-control input-sm" required="">
			<label>producto_id</label>
			<input type="number" id="producto_id" class="form-control input-sm" required="">
			<label>orden_id</label>
			<input type="number" id="orden_id" class="form-control input-sm" required="">
			<label>cantidad</label>
			<input type="" id="cantidad" class="form-control input-sm" required="">
			<label>ubicacion_bodega</label>
			<textarea id="ubicacion_bodega" rows="4" cols="50"class="form-control input-sm" required=""></textarea>
			<label>actualizado_en</label>
			<input type="" id="actualizado_en" class="form-control input-sm" required="">
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
			<input type="number" hidden="" id="id_inventario_ptu">
			<label>producto_id</label>
			<input type="number" id="producto_idu" class="form-control input-sm" required="">
			<label>orden_id</label>
			<input type="number" id="orden_idu" class="form-control input-sm" required="">
			<label>cantidad</label>
			<input type="" id="cantidadu" class="form-control input-sm" required="">
			<label>ubicacion_bodega</label>
			<textarea id="ubicacion_bodegau" rows="4" cols="50" class="form-control input-sm" required=""></textarea>
			<label>actualizado_en</label>
			<input type="" id="actualizado_enu" class="form-control input-sm" required="">
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
		$('#tabla').load('componentes/vista_inventario_producto_terminado.php');
	    });
	</script>
	<script type="text/javascript">
	    $(document).ready(function () {
		$('#guardarnuevo').click(function () {
		    id_inventario_pt = $('#id_inventario_pt').val();
		    producto_id = $('#producto_id').val();
		    orden_id = $('#orden_id').val();
		    cantidad = $('#cantidad').val();
		    ubicacion_bodega = $('#ubicacion_bodega').val();
		    actualizado_en = $('#actualizado_en').val();
		    agregardatos(id_inventario_pt, producto_id, orden_id, cantidad, ubicacion_bodega, actualizado_en);
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
