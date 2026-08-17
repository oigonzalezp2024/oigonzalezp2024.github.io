function agregardatos(id_categoria, nombre_categoria, descripcion){
    cadena = "id_categoria=" + id_categoria +
    "&nombre_categoria=" + nombre_categoria +
    "&descripcion=" + descripcion;

    accion = "insertar";
    mensaje_si = "Cliente agregado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}
function agregaform(datos) {
    d = datos.split('||');
    $('#id_categoriau').val(d[0]);
    $('#nombre_categoriau').val(d[1]);
    $('#descripcionu').val(d[2]);
}

function modificarCliente(){
    id_categoria = $('#id_categoriau').val();
    nombre_categoria = $('#nombre_categoriau').val();
    descripcion = $('#descripcionu').val();
    cadena = "id_categoria=" + id_categoria +
    "&nombre_categoria=" + nombre_categoria +
    "&descripcion=" + descripcion;

    accion = "modificar";
    mensaje_si = "Cliente modificado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_categoria) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_categoria);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_categoria) {
    cadena = "id_categoria=" + id_categoria;

    accion = "borrar";
    mensaje_si = "Cliente borrado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/categorias_insumos_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r == 1) {
            $('#tabla').load('../vista/componentes/vista_categorias_insumos.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
