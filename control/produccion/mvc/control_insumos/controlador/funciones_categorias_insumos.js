function agregardatos(nombre_categoria, descripcion){
    var cadena = "nombre_categoria=" + encodeURIComponent(nombre_categoria) +
                 "&descripcion=" + encodeURIComponent(descripcion);

    var accion = "insertar";
    var mensaje_si = "Categoría agregada con éxito";
    var mensaje_no = "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function agregaform(datos) {
    var d = datos.split('||');
    $('#id_categoriau').val(d[0]);
    $('#nombre_categoriau').val(d[1]);
    $('#descripcionu').val(d[2]);
}

function modificarCategoria(){
    var cadena = "id_categoria=" + $('#id_categoriau').val() +
                 "&nombre_categoria=" + encodeURIComponent($('#nombre_categoriau').val()) +
                 "&descripcion=" + encodeURIComponent($('#descripcionu').val());

    var accion = "modificar";
    var mensaje_si = "Categoría modificada con éxito";
    var mensaje_no = "Error al actualizar registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_categoria) {
    var opcion = confirm("¿Está seguro de eliminar esta categoría?");
    if (opcion == true) {
        eliminarDatos(id_categoria);
    }
}

function eliminarDatos(id_categoria) {
    var cadena = "id_categoria=" + id_categoria;

    var accion = "borrar";
    var mensaje_si = "Categoría borrada con éxito";
    var mensaje_no = "Error al eliminar registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/categorias_insumos_modelo.php?accion=" + accion,
        data: cadena,
        success: function (r){
            if (parseInt(r) === 1) {
                if (typeof cargarTabla === 'function') {
                    cargarTabla();
                } else {
                    $('#tabla').load('componentes/vista_categorias_insumos.php');
                }
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}