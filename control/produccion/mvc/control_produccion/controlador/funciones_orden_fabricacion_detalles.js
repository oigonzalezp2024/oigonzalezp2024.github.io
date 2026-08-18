// Ajusta agregardatos y modificarCliente para incluir orden_id al final
function agregardatos(orden_id, material_id, medidas, cantidad, cantidad_consumida, valor_unitario, valor_total, es_destacado){
    cadena = "orden_id=" + orden_id +
    "&material_id=" + material_id +
    "&medidas=" + encodeURIComponent(medidas) +
    "&cantidad=" + cantidad +
    "&cantidad_consumida=" + cantidad_consumida +
    "&valor_unitario=" + valor_unitario +
    "&valor_total=" + valor_total +
    "&es_destacado=" + es_destacado;

    accion = "insertar";
    mensaje_si = "Detalle agregado con éxito";
    mensaje_no = "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no, orden_id);
}

function agregaform(datos) {
    var d = datos.split('||');
    
    $('#id_detalleu').val(d[0]);
    $('#orden_idu').val(d[1]);
    
    // Seleccionar el material correspondiente y disparar cambio
    $('#material_idu').val(d[2]).change();
    
    $('#medidasu').val(d[3]);
    $('#cantidadu').val(d[4]);
    $('#cantidad_consumidau').val(d[5]);
    $('#valor_unitariou').val(d[6]);
    $('#valor_totalu').val(d[7]);
    $('#es_destacadou').val(d[8]).change();
}

function modificarDetalle(){
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
    "&medidas=" + encodeURIComponent(medidas) +
    "&cantidad=" + cantidad +
    "&cantidad_consumida=" + cantidad_consumida +
    "&valor_unitario=" + valor_unitario +
    "&valor_total=" + valor_total +
    "&es_destacado=" + es_destacado;

    accion = "modificar";
    mensaje_si = "Detalle modificado con éxito";
    mensaje_no = "Error de actualización";
    a_ajax(cadena, accion, mensaje_si, mensaje_no, orden_id);
}

function preguntarSiNo(id_detalle, orden_id) {
    var opcion = confirm("¿Está seguro de eliminar el registro?");
    if (opcion == true) {
        eliminarDatos(id_detalle, orden_id);
    }
}

function eliminarDatos(id_detalle, orden_id) {
    cadena = "id_detalle=" + id_detalle + "&orden_id=" + orden_id;

    accion = "borrar";
    mensaje_si = "Detalle borrado con éxito";
    mensaje_no = "Error al eliminar el registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no, orden_id);
}

// Firma actualizada para recibir orden_id de forma directa y determinista
function a_ajax(cadena, accion, mensaje_si, mensaje_no, orden_id){
    $.ajax({
        type: "POST",
        url: "../modelo/orden_fabricacion_detalles_modelo.php?accion=" + accion,
        data: cadena,
        success: function (r){
            if (r == 1) {
                // La recarga se realiza de forma limpia con el id explícito del flujo
                $('#tabla').load('componentes/vista_orden_fabricacion_detalles.php?orden_id=' + orden_id);
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
