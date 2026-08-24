function cargarTablaAsesores() {
    var buscar = $('#input_buscar').val() || '';
    var url = 'componentes/vista_asesores.php?buscar=' + encodeURIComponent(buscar);
    $('#tabla').load(url);
}

function evaluarBusquedaAsesores(e) {
    if (e.keyCode === 13) {
        cargarTablaAsesores();
    }
}

function agregardatosAsesor(documento, nombre, telefono) {
    if (!documento || !nombre) {
        alert("Por favor complete los campos obligatorios");
        return;
    }

    var cadena = "documento=" + encodeURIComponent(documento) +
                 "&nombre=" + encodeURIComponent(nombre) +
                 "&rol=ASESOR" +
                 "&telefono=" + encodeURIComponent(telefono);

    a_ajax_asesor(cadena, "insertar", "Asesor agregado con éxito", "Error al registrar asesor");
}

function agregaformAsesor(datos) {
    var d = datos.split('||');
    $('#id_personau').val(d[0]);
    $('#documentou').val(d[1]);
    $('#nombreu').val(d[2]);
    $('#rolu').val(d[3]);
    $('#telefonou').val(d[4]);
}

function modificarAsesor() {
    var cadena = "id_persona=" + $('#id_personau').val() +
                 "&documento=" + encodeURIComponent($('#documentou').val()) +
                 "&nombre=" + encodeURIComponent($('#nombreu').val()) +
                 "&rol=" + encodeURIComponent($('#rolu').val()) +
                 "&telefono=" + encodeURIComponent($('#telefonou').val());

    a_ajax_asesor(cadena, "modificar", "Asesor actualizado con éxito", "Error al actualizar asesor");
}

function preguntarSiNoAsesor(id_persona) {
    if (confirm("¿Está seguro de eliminar este asesor?")) {
        eliminarAsesor(id_persona);
    }
}

function eliminarAsesor(id_persona) {
    var cadena = "id_persona=" + id_persona;
    a_ajax_asesor(cadena, "borrar", "Asesor eliminado con éxito", "Error al eliminar asesor");
}

function a_ajax_asesor(cadena, accion, mensaje_si, mensaje_no) {
    $.ajax({
        type: "POST",
        url: "../modelo/asesores_modelo.php?accion=" + accion,
        data: cadena,
        success: function (r) {
            if (parseInt(r) === 1) {
                cargarTablaAsesores();
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
