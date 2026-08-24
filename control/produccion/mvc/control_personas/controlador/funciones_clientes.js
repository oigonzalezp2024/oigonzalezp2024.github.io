function cargarTablaClientes() {
    var buscar = $('#input_buscar').val() || '';
    var url = 'componentes/vista_clientes.php?buscar=' + encodeURIComponent(buscar);
    $('#tabla').load(url);
}

function evaluarBusquedaClientes(e) {
    if (e.keyCode === 13) {
        cargarTablaClientes();
    }
}

function agregardatosCliente(documento, nombre, telefono) {
    if (!documento || !nombre) {
        alert("Por favor complete los campos obligatorios");
        return;
    }

    var cadena = "documento=" + encodeURIComponent(documento) +
                 "&nombre=" + encodeURIComponent(nombre) +
                 "&rol=CLIENTE" +
                 "&telefono=" + encodeURIComponent(telefono);

    a_ajax_cliente(cadena, "insertar", "Cliente agregado con éxito", "Error al registrar cliente");
}

function agregaformCliente(datos) {
    var d = datos.split('||');
    $('#id_personau').val(d[0]);
    $('#documentou').val(d[1]);
    $('#nombreu').val(d[2]);
    $('#rolu').val(d[3]);
    $('#telefonou').val(d[4]);
}

function modificarCliente() {
    var cadena = "id_persona=" + $('#id_personau').val() +
                 "&documento=" + encodeURIComponent($('#documentou').val()) +
                 "&nombre=" + encodeURIComponent($('#nombreu').val()) +
                 "&rol=" + encodeURIComponent($('#rolu').val()) +
                 "&telefono=" + encodeURIComponent($('#telefonou').val());

    a_ajax_cliente(cadena, "modificar", "Cliente actualizado con éxito", "Error al actualizar cliente");
}

function preguntarSiNoCliente(id_persona) {
    if (confirm("¿Está seguro de eliminar este cliente?")) {
        eliminarCliente(id_persona);
    }
}

function eliminarCliente(id_persona) {
    var cadena = "id_persona=" + id_persona;
    a_ajax_cliente(cadena, "borrar", "Cliente eliminado con éxito", "Error al eliminar cliente");
}

function a_ajax_cliente(cadena, accion, mensaje_si, mensaje_no) {
    $.ajax({
        type: "POST",
        url: "../modelo/clientes_modelo.php?accion=" + accion,
        data: cadena,
        success: function (r) {
            if (parseInt(r) === 1) {
                cargarTablaClientes();
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
