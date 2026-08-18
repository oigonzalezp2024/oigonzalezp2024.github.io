function cargarTablaProductos() {
    var buscar = $('#input_buscar').val() || '';
    var url = 'componentes/vista_productos_catalogo.php?buscar=' + encodeURIComponent(buscar);
    $('#tabla').load(url);
}

function evaluarBusquedaProductos(e) {
    if (e.keyCode === 13) {
        cargarTablaProductos();
    }
}

function agregardatosProducto(codigo_referencia, nombre_producto, descripcion) {
    if (!codigo_referencia || !nombre_producto) {
        alert("Por favor complete los campos obligatorios");
        return;
    }

    var cadena = "codigo_referencia=" + encodeURIComponent(codigo_referencia) +
                 "&nombre_producto=" + encodeURIComponent(nombre_producto) +
                 "&descripcion=" + encodeURIComponent(descripcion);

    a_ajax_producto(cadena, "insertar", "Producto agregado con éxito", "Error al registrar el producto");
}

function agregaformProducto(datos) {
    var d = datos.split('||');
    $('#id_productou').val(d[0]);
    $('#codigo_referenciau').val(d[1]);
    $('#nombre_productou').val(d[2]);
    $('#descripcionu').val(d[3]);
}

function modificarProducto() {
    var cadena = "id_producto=" + $('#id_productou').val() +
                 "&codigo_referencia=" + encodeURIComponent($('#codigo_referenciau').val()) +
                 "&nombre_producto=" + encodeURIComponent($('#nombre_productou').val()) +
                 "&descripcion=" + encodeURIComponent($('#descripcionu').val());

    a_ajax_producto(cadena, "modificar", "Producto actualizado con éxito", "Error al actualizar el producto");
}

function preguntarSiNoProducto(id_producto) {
    if (confirm("¿Está seguro de eliminar este producto del catálogo?")) {
        eliminarProducto(id_producto);
    }
}

function eliminarProducto(id_producto) {
    var cadena = "id_producto=" + id_producto;
    a_ajax_producto(cadena, "borrar", "Producto eliminado con éxito", "Error al eliminar el producto");
}

function a_ajax_producto(cadena, accion, mensaje_si, mensaje_no) {
    $.ajax({
        type: "POST",
        url: "../modelo/productos_catalogo_modelo.php?accion=" + accion,
        data: cadena,
        success: function (r) {
            if (parseInt(r) === 1) {
                cargarTablaProductos();
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
