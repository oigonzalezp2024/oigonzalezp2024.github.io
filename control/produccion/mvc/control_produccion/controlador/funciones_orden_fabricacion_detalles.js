function agregardatos(id_detalle, orden_id, material_id, medidas, cantidad, cantidad_consumida, valor_unitario, valor_total, es_destacado){
    cadena = "id_detalle=" + id_detalle +
    "&orden_id=" + orden_id +
    "&material_id=" + material_id +
    "&medidas=" + medidas +
    "&cantidad=" + cantidad +
    "&cantidad_consumida=" + cantidad_consumida +
    "&valor_unitario=" + valor_unitario +
    "&valor_total=" + valor_total +
    "&es_destacado=" + es_destacado;

    accion = "insertar";
    mensaje_si = "Cliente agregado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}
function agregaform(datos) {
    d = datos.split('||');
    $('#id_detalleu').val(d[0]);
    $('#orden_idu').val(d[1]);
    $('#material_idu').val(d[2]);
    $('#medidasu').val(d[3]);
    $('#cantidadu').val(d[4]);
    $('#cantidad_consumidau').val(d[5]);
    $('#valor_unitariou').val(d[6]);
    $('#valor_totalu').val(d[7]);
    $('#es_destacadou').val(d[8]);
}

function modificarCliente(){
    id_detalle = $('#id_detalleu').val();
    orden_id = $('#orden_idu').val();
    material_id = $('#material_idu').val();
    medidas = $('#medidasu').val();
    cantidad = $('#cantidadu').val();
    cantidad_consumida = $('#cantidad_consumidau').val();
    valor_unitario = $('#valor_unitariou').val();
    valor_total = $('#valor_totalu').val();
    es_destacado = $('#es_destacadou').val();
    cadena = "id_detalle=" + id_detalle +
    "&orden_id=" + orden_id +
    "&material_id=" + material_id +
    "&medidas=" + medidas +
    "&cantidad=" + cantidad +
    "&cantidad_consumida=" + cantidad_consumida +
    "&valor_unitario=" + valor_unitario +
    "&valor_total=" + valor_total +
    "&es_destacado=" + es_destacado;

    accion = "modificar";
    mensaje_si = "Cliente modificado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_detalle) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_detalle);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_detalle) {
    cadena = "id_detalle=" + id_detalle;

    accion = "borrar";
    mensaje_si = "Cliente borrado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/orden_fabricacion_detalles_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r == 1) {
            $('#tabla').load('../vista/componentes/vista_orden_fabricacion_detalles.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
