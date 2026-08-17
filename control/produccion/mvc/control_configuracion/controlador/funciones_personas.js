function agregardatos(id_persona, documento, nombre, rol, telefono){
    cadena = "id_persona=" + id_persona +
    "&documento=" + documento +
    "&nombre=" + nombre +
    "&rol=" + rol +
    "&telefono=" + telefono;

    accion = "insertar";
    mensaje_si = "Cliente agregado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}
function agregaform(datos) {
    d = datos.split('||');
    $('#id_personau').val(d[0]);
    $('#documentou').val(d[1]);
    $('#nombreu').val(d[2]);
    $('#rolu').val(d[3]);
    $('#telefonou').val(d[4]);
}

function modificarCliente(){
    id_persona = $('#id_personau').val();
    documento = $('#documentou').val();
    nombre = $('#nombreu').val();
    rol = $('#rolu').val();
    telefono = $('#telefonou').val();
    cadena = "id_persona=" + id_persona +
    "&documento=" + documento +
    "&nombre=" + nombre +
    "&rol=" + rol +
    "&telefono=" + telefono;

    accion = "modificar";
    mensaje_si = "Cliente modificado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_persona) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_persona);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_persona) {
    cadena = "id_persona=" + id_persona;

    accion = "borrar";
    mensaje_si = "Cliente borrado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/personas_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r == 1) {
            $('#tabla').load('../vista/componentes/vista_personas.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
