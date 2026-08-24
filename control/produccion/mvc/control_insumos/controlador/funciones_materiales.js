function agregardatos(codigo_material, descripcion_material, unidad_medida, precio_unitario_defecto, categoria_id, stock_minimo, stock_actual, stock_maximo){
    cadena = "codigo_material=" + encodeURIComponent(codigo_material) +
    "&descripcion_material=" + encodeURIComponent(descripcion_material) +
    "&unidad_medida=" + encodeURIComponent(unidad_medida) +
    "&precio_unitario_defecto=" + precio_unitario_defecto +
    "&categoria_id=" + categoria_id +
    "&stock_minimo=" + stock_minimo +
    "&stock_actual=" + stock_actual +
    "&stock_maximo=" + stock_maximo;

    accion = "insertar";
    mensaje_si = "Material agregado con éxito";
    mensaje_no = "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function agregaform(datos) {
    var d = datos.split('||');
    $('#id_materialu').val(d[0]);
    $('#codigo_materialu').val(d[1]);
    $('#descripcion_materialu').val(d[2]);
    $('#unidad_medidau').val(d[3]);
    $('#precio_unitario_defectou').val(d[4]);
    $('#categoria_idu').val(d[5]);
    $('#stock_minimou').val(d[6]);
    $('#stock_actualu').val(d[7]);
    $('#stock_maximou').val(d[8]);
}

function modificarMaterial(){
    cadena = "id_material=" + $('#id_materialu').val() +
    "&codigo_material=" + encodeURIComponent($('#codigo_materialu').val()) +
    "&descripcion_material=" + encodeURIComponent($('#descripcion_materialu').val()) +
    "&unidad_medida=" + encodeURIComponent($('#unidad_medidau').val()) +
    "&precio_unitario_defecto=" + $('#precio_unitario_defectou').val() +
    "&categoria_id=" + $('#categoria_idu').val() +
    "&stock_minimo=" + $('#stock_minimou').val() +
    "&stock_actual=" + $('#stock_actualu').val() +
    "&stock_maximo=" + $('#stock_maximou').val();

    accion = "modificar";
    mensaje_si = "Material modificado con éxito";
    mensaje_no = "Error al actualizar registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_material) {
    var opcion = confirm("¿Está seguro de eliminar este material?");
    if (opcion == true) {
        eliminarDatos(id_material);
    }
}

function eliminarDatos(id_material) {
    cadena = "id_material=" + id_material;

    accion = "borrar";
    mensaje_si = "Material borrado con éxito";
    mensaje_no = "Error al eliminar registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/materiales_modelo.php?accion=" + accion,
        data: cadena,
        success: function (r){
            if (parseInt(r) === 1) {
                if (typeof cargarTabla === 'function') {
                    cargarTabla();
                } else {
                    $('#tabla').load('componentes/vista_materiales.php');
                }
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
