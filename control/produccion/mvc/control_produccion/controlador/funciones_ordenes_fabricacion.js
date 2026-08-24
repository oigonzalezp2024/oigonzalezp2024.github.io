function agregardatos(numero_orden, cliente_id, asesor_id, fabricante_id, operario_id, producto_id, unidades, estado, fecha_pedido, fecha_entrega) {
    var cadena = "numero_orden=" + encodeURIComponent(numero_orden) +
        "&cliente_id=" + encodeURIComponent(cliente_id) +
        "&asesor_id=" + encodeURIComponent(asesor_id) +
        "&fabricante_id=" + encodeURIComponent(fabricante_id) +
        "&operario_id=" + encodeURIComponent(operario_id || '') +
        "&producto_id=" + encodeURIComponent(producto_id) +
        "&unidades=" + encodeURIComponent(unidades) +
        "&estado=" + encodeURIComponent(estado) +
        "&fecha_pedido=" + encodeURIComponent(fecha_pedido) +
        "&fecha_entrega=" + encodeURIComponent(fecha_entrega);

    var accion = "insertar";
    var mensaje_si = "Orden de producción creada con éxito.";
    var mensaje_no = "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function agregaform(datos) {
    var d = datos.split('||');
    $('#id_ordenu').val(d[0]);
    $('#numero_ordenu').val(d[1]);
    $('#cliente_idu').val(d[2]);
    $('#asesor_idu').val(d[3]);
    $('#fabricante_idu').val(d[4]);
    $('#operario_idu').val(d[5] === 'NULL' ? '' : d[5]);
    $('#producto_idu').val(d[6]);
    $('#unidadesu').val(d[7]);
    $('#estadou').val(d[8]);
    $('#fecha_pedidou').val(d[9]);
    $('#fecha_entregau').val(d[10]);
    $('#costo_subtotalu').val(d[11]);
    $('#costo_modu').val(d[12]);
    $('#costo_cifu').val(d[13]);
    $('#porcentaje_utilidadu').val(d[14]);
    $('#monto_utilidadu').val(d[15]);
    $('#monto_totalu').val(d[16]);
    $('#creado_enu').val(d[17]);
}

function actualizaDatos() {
    var id_orden = $('#id_ordenu').val();
    var numero_orden = $('#numero_ordenu').val();
    var cliente_id = $('#cliente_idu').val();
    var asesor_id = $('#asesor_idu').val();
    var fabricante_id = $('#fabricante_idu').val();
    var operario_id = $('#operario_idu').val();
    var producto_id = $('#producto_idu').val();
    var unidades = $('#unidadesu').val();
    var estado = $('#estadou').val();
    var fecha_pedido = $('#fecha_pedidou').val();
    var fecha_entrega = $('#fecha_entregau').val();
    var costo_subtotal = $('#costo_subtotalu').val();
    var costo_mod = $('#costo_modu').val();
    var costo_cif = $('#costo_cifu').val();
    var porcentaje_utilidad = $('#porcentaje_utilidadu').val();
    var monto_utilidad = $('#monto_utilidadu').val();
    var monto_total = $('#monto_totalu').val();
    var creado_en = $('#creado_enu').val();

    var cadena = "id_orden=" + encodeURIComponent(id_orden) +
        "&numero_orden=" + encodeURIComponent(numero_orden) +
        "&cliente_id=" + encodeURIComponent(cliente_id) +
        "&asesor_id=" + encodeURIComponent(asesor_id) +
        "&fabricante_id=" + encodeURIComponent(fabricante_id) +
        "&operario_id=" + encodeURIComponent(operario_id || '') +
        "&producto_id=" + encodeURIComponent(producto_id) +
        "&unidades=" + encodeURIComponent(unidades) +
        "&estado=" + encodeURIComponent(estado) +
        "&fecha_pedido=" + encodeURIComponent(fecha_pedido) +
        "&fecha_entrega=" + encodeURIComponent(fecha_entrega) +
        "&costo_subtotal=" + encodeURIComponent(costo_subtotal) +
        "&costo_mod=" + encodeURIComponent(costo_mod) +
        "&costo_cif=" + encodeURIComponent(costo_cif) +
        "&porcentaje_utilidad=" + encodeURIComponent(porcentaje_utilidad) +
        "&monto_utilidad=" + encodeURIComponent(monto_utilidad) +
        "&monto_total=" + encodeURIComponent(monto_total) +
        "&creado_en=" + encodeURIComponent(creado_en);

    var accion = "modificar";
    var mensaje_si = "Orden de producción modificada con éxito.";
    var mensaje_no = "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_orden) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        eliminarDatos(id_orden);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_orden) {
    var cadena = "id_orden=" + encodeURIComponent(id_orden);

    var accion = "borrar";
    var mensaje_si = "Orden de producción eliminada con éxito.";
    var mensaje_no = "Para que una orden de producción pueda ser eliminada, necesariamente todos sus detalles deben ser eliminados con anterioridad.";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no) {
    $.ajax({
        type: "POST",
        url: "../modelo/ordenes_fabricacion_modelo.php?accion=" + accion,
        data: cadena,
        success: function (r) {
            if (r == 1) {
                if (typeof cargarTabla === 'function') {
                    cargarTabla();
                } else {
                    $('#tabla').load('componentes/vista_ordenes_fabricacion.php');
                }
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
