function agregardatos(id_movimiento, tipo_item, id_item, tipo_movimiento, cantidad, orden_id, observacion, fecha_movimiento){
    cadena = "id_movimiento=" + id_movimiento +
    "&tipo_item=" + tipo_item +
    "&id_item=" + id_item +
    "&tipo_movimiento=" + tipo_movimiento +
    "&cantidad=" + cantidad +
    "&orden_id=" + orden_id +
    "&observacion=" + observacion +
    "&fecha_movimiento=" + fecha_movimiento;

    accion = "insertar";
    mensaje_si = "Cliente agregado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}
function agregaform(datos) {
    d = datos.split('||');
    $('#id_movimientou').val(d[0]);
    $('#tipo_itemu').val(d[1]);
    $('#id_itemu').val(d[2]);
    $('#tipo_movimientou').val(d[3]);
    $('#cantidadu').val(d[4]);
    $('#orden_idu').val(d[5]);
    $('#observacionu').val(d[6]);
    $('#fecha_movimientou').val(d[7]);
}

function modificarCliente(){
    id_movimiento = $('#id_movimientou').val();
    tipo_item = $('#tipo_itemu').val();
    id_item = $('#id_itemu').val();
    tipo_movimiento = $('#tipo_movimientou').val();
    cantidad = $('#cantidadu').val();
    orden_id = $('#orden_idu').val();
    observacion = $('#observacionu').val();
    fecha_movimiento = $('#fecha_movimientou').val();
    cadena = "id_movimiento=" + id_movimiento +
    "&tipo_item=" + tipo_item +
    "&id_item=" + id_item +
    "&tipo_movimiento=" + tipo_movimiento +
    "&cantidad=" + cantidad +
    "&orden_id=" + orden_id +
    "&observacion=" + observacion +
    "&fecha_movimiento=" + fecha_movimiento;

    accion = "modificar";
    mensaje_si = "Cliente modificado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_movimiento) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_movimiento);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_movimiento) {
    cadena = "id_movimiento=" + id_movimiento;

    accion = "borrar";
    mensaje_si = "Cliente borrado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/movimientos_inventario_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r == 1) {
            $('#tabla').load('../vista/componentes/vista_movimientos_inventario.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
