<!DOCTYPE html>
<html>
    <head>
	<meta charset="UTF-8">
	<title>Clientes</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php
	include('librerias.php');
	?>
	<script src="../controlador/funciones_materiales.js"></script>
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
			<label>id_material</label>
			<input type="number" id="id_material" class="form-control input-sm" required="">
			<label>codigo_material</label>
			<textarea id="codigo_material" rows="4" cols="50"class="form-control input-sm" required=""></textarea>
			<label>descripcion_material</label>
			<textarea id="descripcion_material" rows="4" cols="50"class="form-control input-sm" required=""></textarea>
			<label>unidad_medida</label>
			<textarea id="unidad_medida" rows="4" cols="50"class="form-control input-sm" required=""></textarea>
			<label>precio_unitario_defecto</label>
			<input type="" id="precio_unitario_defecto" class="form-control input-sm" required="">
			<label>categoria_id</label>
			<input type="number" id="categoria_id" class="form-control input-sm" required="">
			<label>stock_minimo</label>
			<input type="" id="stock_minimo" class="form-control input-sm" required="">
			<label>stock_actual</label>
			<input type="" id="stock_actual" class="form-control input-sm" required="">
			<label>stock_maximo</label>
			<input type="" id="stock_maximo" class="form-control input-sm" required="">
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
			<input type="number" hidden="" id="id_materialu">
			<label>codigo_material</label>
			<textarea id="codigo_materialu" rows="4" cols="50" class="form-control input-sm" required=""></textarea>
			<label>descripcion_material</label>
			<textarea id="descripcion_materialu" rows="4" cols="50" class="form-control input-sm" required=""></textarea>
			<label>unidad_medida</label>
			<textarea id="unidad_medidau" rows="4" cols="50" class="form-control input-sm" required=""></textarea>
			<label>precio_unitario_defecto</label>
			<input type="" id="precio_unitario_defectou" class="form-control input-sm" required="">
			<label>categoria_id</label>
			<input type="number" id="categoria_idu" class="form-control input-sm" required="">
			<label>stock_minimo</label>
			<input type="" id="stock_minimou" class="form-control input-sm" required="">
			<label>stock_actual</label>
			<input type="" id="stock_actualu" class="form-control input-sm" required="">
			<label>stock_maximo</label>
			<input type="" id="stock_maximou" class="form-control input-sm" required="">
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
		$('#tabla').load('componentes/vista_materiales.php');
	    });
	</script>
	<script type="text/javascript">
	    $(document).ready(function () {
		$('#guardarnuevo').click(function () {
		    id_material = $('#id_material').val();
		    codigo_material = $('#codigo_material').val();
		    descripcion_material = $('#descripcion_material').val();
		    unidad_medida = $('#unidad_medida').val();
		    precio_unitario_defecto = $('#precio_unitario_defecto').val();
		    categoria_id = $('#categoria_id').val();
		    stock_minimo = $('#stock_minimo').val();
		    stock_actual = $('#stock_actual').val();
		    stock_maximo = $('#stock_maximo').val();
		    creado_en = $('#creado_en').val();
		    agregardatos(id_material, codigo_material, descripcion_material, unidad_medida, precio_unitario_defecto, categoria_id, stock_minimo, stock_actual, stock_maximo, creado_en);
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
