function agregardatos(id_producto, codigo_referencia, nombre_producto, descripcion){
    cadena = "id_producto=" + id_producto +
    "&codigo_referencia=" + codigo_referencia +
    "&nombre_producto=" + nombre_producto +
    "&descripcion=" + descripcion;

    accion = "insertar";
    mensaje_si = "Cliente agregado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}
function agregaform(datos) {
    d = datos.split('||');
    $('#id_productou').val(d[0]);
    $('#codigo_referenciau').val(d[1]);
    $('#nombre_productou').val(d[2]);
    $('#descripcionu').val(d[3]);
}

function modificarCliente(){
    id_producto = $('#id_productou').val();
    codigo_referencia = $('#codigo_referenciau').val();
    nombre_producto = $('#nombre_productou').val();
    descripcion = $('#descripcionu').val();
    cadena = "id_producto=" + id_producto +
    "&codigo_referencia=" + codigo_referencia +
    "&nombre_producto=" + nombre_producto +
    "&descripcion=" + descripcion;

    accion = "modificar";
    mensaje_si = "Cliente modificado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_producto) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_producto);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_producto) {
    cadena = "id_producto=" + id_producto;

    accion = "borrar";
    mensaje_si = "Cliente borrado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/productos_catalogo_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r == 1) {
            $('#tabla').load('../vista/componentes/vista_productos_catalogo.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
