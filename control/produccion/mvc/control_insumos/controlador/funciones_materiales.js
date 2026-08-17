function agregardatos(id_material, codigo_material, descripcion_material, unidad_medida, precio_unitario_defecto, categoria_id, stock_minimo, stock_actual, stock_maximo, creado_en){
    cadena = "id_material=" + id_material +
    "&codigo_material=" + codigo_material +
    "&descripcion_material=" + descripcion_material +
    "&unidad_medida=" + unidad_medida +
    "&precio_unitario_defecto=" + precio_unitario_defecto +
    "&categoria_id=" + categoria_id +
    "&stock_minimo=" + stock_minimo +
    "&stock_actual=" + stock_actual +
    "&stock_maximo=" + stock_maximo +
    "&creado_en=" + creado_en;

    accion = "insertar";
    mensaje_si = "Cliente agregado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}
function agregaform(datos) {
    d = datos.split('||');
    $('#id_materialu').val(d[0]);
    $('#codigo_materialu').val(d[1]);
    $('#descripcion_materialu').val(d[2]);
    $('#unidad_medidau').val(d[3]);
    $('#precio_unitario_defectou').val(d[4]);
    $('#categoria_idu').val(d[5]);
    $('#stock_minimou').val(d[6]);
    $('#stock_actualu').val(d[7]);
    $('#stock_maximou').val(d[8]);
    $('#creado_enu').val(d[9]);
}

function modificarCliente(){
    id_material = $('#id_materialu').val();
    codigo_material = $('#codigo_materialu').val();
    descripcion_material = $('#descripcion_materialu').val();
    unidad_medida = $('#unidad_medidau').val();
    precio_unitario_defecto = $('#precio_unitario_defectou').val();
    categoria_id = $('#categoria_idu').val();
    stock_minimo = $('#stock_minimou').val();
    stock_actual = $('#stock_actualu').val();
    stock_maximo = $('#stock_maximou').val();
    creado_en = $('#creado_enu').val();
    cadena = "id_material=" + id_material +
    "&codigo_material=" + codigo_material +
    "&descripcion_material=" + descripcion_material +
    "&unidad_medida=" + unidad_medida +
    "&precio_unitario_defecto=" + precio_unitario_defecto +
    "&categoria_id=" + categoria_id +
    "&stock_minimo=" + stock_minimo +
    "&stock_actual=" + stock_actual +
    "&stock_maximo=" + stock_maximo +
    "&creado_en=" + creado_en;

    accion = "modificar";
    mensaje_si = "Cliente modificado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_material) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_material);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_material) {
    cadena = "id_material=" + id_material;

    accion = "borrar";
    mensaje_si = "Cliente borrado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/materiales_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r == 1) {
            $('#tabla').load('../vista/componentes/vista_materiales.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
