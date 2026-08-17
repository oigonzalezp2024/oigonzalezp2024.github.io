<!DOCTYPE html>
<html>
    <head>
	<meta charset="UTF-8">
	<title>Clientes</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php
	include('librerias.php');
	?>
	<script src="../controlador/funciones_orden_fabricacion_detalles.js"></script>
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
			<label>id_detalle</label>
			<input type="number" id="id_detalle" class="form-control input-sm" required="">
			<label>orden_id</label>
			<input type="number" id="orden_id" class="form-control input-sm" required="">
			<label>material_id</label>
			<input type="number" id="material_id" class="form-control input-sm" required="">
			<label>medidas</label>
			<textarea id="medidas" rows="4" cols="50"class="form-control input-sm" required=""></textarea>
			<label>cantidad</label>
			<input type="" id="cantidad" class="form-control input-sm" required="">
			<label>cantidad_consumida</label>
			<input type="" id="cantidad_consumida" class="form-control input-sm" required="">
			<label>valor_unitario</label>
			<input type="" id="valor_unitario" class="form-control input-sm" required="">
			<label>valor_total</label>
			<input type="" id="valor_total" class="form-control input-sm" required="">
			<label>es_destacado</label>
			<input type="" id="es_destacado" class="form-control input-sm" required="">
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
			<input type="number" hidden="" id="id_detalleu">
			<label>orden_id</label>
			<input type="number" id="orden_idu" class="form-control input-sm" required="">
			<label>material_id</label>
			<input type="number" id="material_idu" class="form-control input-sm" required="">
			<label>medidas</label>
			<textarea id="medidasu" rows="4" cols="50" class="form-control input-sm" required=""></textarea>
			<label>cantidad</label>
			<input type="" id="cantidadu" class="form-control input-sm" required="">
			<label>cantidad_consumida</label>
			<input type="" id="cantidad_consumidau" class="form-control input-sm" required="">
			<label>valor_unitario</label>
			<input type="" id="valor_unitariou" class="form-control input-sm" required="">
			<label>valor_total</label>
			<input type="" id="valor_totalu" class="form-control input-sm" required="">
			<label>es_destacado</label>
			<input type="" id="es_destacadou" class="form-control input-sm" required="">
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
		$('#tabla').load('componentes/vista_orden_fabricacion_detalles.php');
	    });
	</script>
	<script type="text/javascript">
	    $(document).ready(function () {
		$('#guardarnuevo').click(function () {
		    id_detalle = $('#id_detalle').val();
		    orden_id = $('#orden_id').val();
		    material_id = $('#material_id').val();
		    medidas = $('#medidas').val();
		    cantidad = $('#cantidad').val();
		    cantidad_consumida = $('#cantidad_consumida').val();
		    valor_unitario = $('#valor_unitario').val();
		    valor_total = $('#valor_total').val();
		    es_destacado = $('#es_destacado').val();
		    agregardatos(id_detalle, orden_id, material_id, medidas, cantidad, cantidad_consumida, valor_unitario, valor_total, es_destacado);
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
