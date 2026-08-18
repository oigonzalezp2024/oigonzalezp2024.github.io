function cargarTablaOperarios() {
    var buscar = $('#input_buscar').val() || '';
    var url = 'componentes/vista_operarios.php?buscar=' + encodeURIComponent(buscar);
    $('#tabla').load(url);
}

function evaluarBusquedaOperarios(e) {
    if (e.keyCode === 13) {
        cargarTablaOperarios();
    }
}

function agregardatosOperario(documento, nombre, telefono) {
    if (!documento || !nombre) {
        alert("Por favor complete los campos obligatorios");
        return;
    }

    var cadena = "documento=" + encodeURIComponent(documento) +
                 "&nombre=" + encodeURIComponent(nombre) +
                 "&rol=OPERARIO" +
                 "&telefono=" + encodeURIComponent(telefono);

    a_ajax_operario(cadena, "insertar", "Operario agregado con éxito", "Error al registrar operario");
}

function agregaformOperario(datos) {
    var d = datos.split('||');
    $('#id_personau').val(d[0]);
    $('#documentou').val(d[1]);
    $('#nombreu').val(d[2]);
    $('#rolu').val(d[3]);
    $('#telefonou').val(d[4]);
}

function modificarOperario() {
    var cadena = "id_persona=" + $('#id_personau').val() +
                 "&documento=" + encodeURIComponent($('#documentou').val()) +
                 "&nombre=" + encodeURIComponent($('#nombreu').val()) +
                 "&rol=" + encodeURIComponent($('#rolu').val()) +
                 "&telefono=" + encodeURIComponent($('#telefonou').val());

    a_ajax_operario(cadena, "modificar", "Operario actualizado con éxito", "Error al actualizar operario");
}

function preguntarSiNoOperario(id_persona) {
    if (confirm("¿Está seguro de eliminar este operario?")) {
        eliminarOperario(id_persona);
    }
}

function eliminarOperario(id_persona) {
    var cadena = "id_persona=" + id_persona;
    a_ajax_operario(cadena, "borrar", "Operario eliminado con éxito", "Error al eliminar operario");
}

function a_ajax_operario(cadena, accion, mensaje_si, mensaje_no) {
    $.ajax({
        type: "POST",
        url: "../modelo/operarios_modelo.php?accion=" + accion,
        data: cadena,
        success: function (r) {
            if (parseInt(r) === 1) {
                cargarTablaOperarios();
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
