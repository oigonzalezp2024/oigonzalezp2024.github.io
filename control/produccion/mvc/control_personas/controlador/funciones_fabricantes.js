function cargarTablaFabricantes() {
    var buscar = $('#input_buscar').val() || '';
    var url = 'componentes/vista_fabricantes.php?buscar=' + encodeURIComponent(buscar);
    $('#tabla').load(url);
}

function evaluarBusquedaFabricantes(e) {
    if (e.keyCode === 13) {
        cargarTablaFabricantes();
    }
}

function agregardatosFabricante(documento, nombre, telefono) {
    if (!documento || !nombre) {
        alert("Por favor complete los campos obligatorios");
        return;
    }

    var cadena = "documento=" + encodeURIComponent(documento) +
                 "&nombre=" + encodeURIComponent(nombre) +
                 "&rol=FABRICANTE" +
                 "&telefono=" + encodeURIComponent(telefono);

    a_ajax_fabricante(cadena, "insertar", "Fabricante agregado con éxito", "Error al registrar fabricante");
}

function agregaformFabricante(datos) {
    var d = datos.split('||');
    $('#id_personau').val(d[0]);
    $('#documentou').val(d[1]);
    $('#nombreu').val(d[2]);
    $('#rolu').val(d[3]);
    $('#telefonou').val(d[4]);
}

function modificarFabricante() {
    var cadena = "id_persona=" + $('#id_personau').val() +
                 "&documento=" + encodeURIComponent($('#documentou').val()) +
                 "&nombre=" + encodeURIComponent($('#nombreu').val()) +
                 "&rol=" + encodeURIComponent($('#rolu').val()) +
                 "&telefono=" + encodeURIComponent($('#telefonou').val());

    a_ajax_fabricante(cadena, "modificar", "Fabricante actualizado con éxito", "Error al actualizar fabricante");
}

function preguntarSiNoFabricante(id_persona) {
    if (confirm("¿Está seguro de eliminar este fabricante?")) {
        eliminarFabricante(id_persona);
    }
}

function eliminarFabricante(id_persona) {
    var cadena = "id_persona=" + id_persona;
    a_ajax_fabricante(cadena, "borrar", "Fabricante eliminado con éxito", "Error al eliminar fabricante");
}

function a_ajax_fabricante(cadena, accion, mensaje_si, mensaje_no) {
    $.ajax({
        type: "POST",
        url: "../modelo/fabricantes_modelo.php?accion=" + accion,
        data: cadena,
        success: function (r) {
            if (parseInt(r) === 1) {
                cargarTablaFabricantes();
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
