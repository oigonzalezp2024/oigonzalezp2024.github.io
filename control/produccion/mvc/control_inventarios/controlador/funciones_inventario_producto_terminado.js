function agregardatos(id_inventario_pt, producto_id, orden_id, cantidad, ubicacion_bodega, actualizado_en){
    cadena = "id_inventario_pt=" + id_inventario_pt +
    "&producto_id=" + producto_id +
    "&orden_id=" + orden_id +
    "&cantidad=" + cantidad +
    "&ubicacion_bodega=" + ubicacion_bodega +
    "&actualizado_en=" + actualizado_en;

    accion = "insertar";
    mensaje_si = "Cliente agregado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}
function agregaform(datos) {
    d = datos.split('||');
    $('#id_inventario_ptu').val(d[0]);
    $('#producto_idu').val(d[1]);
    $('#orden_idu').val(d[2]);
    $('#cantidadu').val(d[3]);
    $('#ubicacion_bodegau').val(d[4]);
    $('#actualizado_enu').val(d[5]);
}

function modificarCliente(){
    id_inventario_pt = $('#id_inventario_ptu').val();
    producto_id = $('#producto_idu').val();
    orden_id = $('#orden_idu').val();
    cantidad = $('#cantidadu').val();
    ubicacion_bodega = $('#ubicacion_bodegau').val();
    actualizado_en = $('#actualizado_enu').val();
    cadena = "id_inventario_pt=" + id_inventario_pt +
    "&producto_id=" + producto_id +
    "&orden_id=" + orden_id +
    "&cantidad=" + cantidad +
    "&ubicacion_bodega=" + ubicacion_bodega +
    "&actualizado_en=" + actualizado_en;

    accion = "modificar";
    mensaje_si = "Cliente modificado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_inventario_pt) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_inventario_pt);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_inventario_pt) {
    cadena = "id_inventario_pt=" + id_inventario_pt;

    accion = "borrar";
    mensaje_si = "Cliente borrado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/inventario_producto_terminado_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r == 1) {
            $('#tabla').load('../vista/componentes/vista_inventario_producto_terminado.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
