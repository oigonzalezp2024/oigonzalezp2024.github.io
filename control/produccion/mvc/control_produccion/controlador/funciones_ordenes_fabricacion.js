function agregardatos(id_orden, numero_orden, cliente_id, asesor_id, fabricante_id, operario_id, producto_id, unidades, estado, fecha_pedido, fecha_entrega, costo_subtotal, costo_mod, costo_cif, porcentaje_utilidad, monto_utilidad, monto_total, creado_en){
    cadena = "id_orden=" + id_orden +
    "&numero_orden=" + numero_orden +
    "&cliente_id=" + cliente_id +
    "&asesor_id=" + asesor_id +
    "&fabricante_id=" + fabricante_id +
    "&operario_id=" + operario_id +
    "&producto_id=" + producto_id +
    "&unidades=" + unidades +
    "&estado=" + estado +
    "&fecha_pedido=" + fecha_pedido +
    "&fecha_entrega=" + fecha_entrega +
    "&costo_subtotal=" + costo_subtotal +
    "&costo_mod=" + costo_mod +
    "&costo_cif=" + costo_cif +
    "&porcentaje_utilidad=" + porcentaje_utilidad +
    "&monto_utilidad=" + monto_utilidad +
    "&monto_total=" + monto_total +
    "&creado_en=" + creado_en;

    accion = "insertar";
    mensaje_si = "Cliente agregado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}
function agregaform(datos) {
    d = datos.split('||');
    $('#id_ordenu').val(d[0]);
    $('#numero_ordenu').val(d[1]);
    $('#cliente_idu').val(d[2]);
    $('#asesor_idu').val(d[3]);
    $('#fabricante_idu').val(d[4]);
    $('#operario_idu').val(d[5]);
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

function modificarCliente(){
    id_orden = $('#id_ordenu').val();
    numero_orden = $('#numero_ordenu').val();
    cliente_id = $('#cliente_idu').val();
    asesor_id = $('#asesor_idu').val();
    fabricante_id = $('#fabricante_idu').val();
    operario_id = $('#operario_idu').val();
    producto_id = $('#producto_idu').val();
    unidades = $('#unidadesu').val();
    estado = $('#estadou').val();
    fecha_pedido = $('#fecha_pedidou').val();
    fecha_entrega = $('#fecha_entregau').val();
    costo_subtotal = $('#costo_subtotalu').val();
    costo_mod = $('#costo_modu').val();
    costo_cif = $('#costo_cifu').val();
    porcentaje_utilidad = $('#porcentaje_utilidadu').val();
    monto_utilidad = $('#monto_utilidadu').val();
    monto_total = $('#monto_totalu').val();
    creado_en = $('#creado_enu').val();
    cadena = "id_orden=" + id_orden +
    "&numero_orden=" + numero_orden +
    "&cliente_id=" + cliente_id +
    "&asesor_id=" + asesor_id +
    "&fabricante_id=" + fabricante_id +
    "&operario_id=" + operario_id +
    "&producto_id=" + producto_id +
    "&unidades=" + unidades +
    "&estado=" + estado +
    "&fecha_pedido=" + fecha_pedido +
    "&fecha_entrega=" + fecha_entrega +
    "&costo_subtotal=" + costo_subtotal +
    "&costo_mod=" + costo_mod +
    "&costo_cif=" + costo_cif +
    "&porcentaje_utilidad=" + porcentaje_utilidad +
    "&monto_utilidad=" + monto_utilidad +
    "&monto_total=" + monto_total +
    "&creado_en=" + creado_en;

    accion = "modificar";
    mensaje_si = "Cliente modificado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_orden) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_orden);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_orden) {
    cadena = "id_orden=" + id_orden;

    accion = "borrar";
    mensaje_si = "Cliente borrado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/ordenes_fabricacion_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r == 1) {
            $('#tabla').load('../vista/componentes/vista_ordenes_fabricacion.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
