<?php
include 'conexion.php';
$conn = conexion();

date_default_timezone_set('America/Bogota');

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

if ($accion === "insertar") {
    $documento = mysqli_real_escape_string($conn, trim($_POST['documento']));
    $nombre    = mysqli_real_escape_string($conn, trim($_POST['nombre']));
    $rol       = mysqli_real_escape_string($conn, trim($_POST['rol'])); // OPERARIO
    $telefono  = mysqli_real_escape_string($conn, trim($_POST['telefono']));
    $creado_en = date('Y-m-d H:i:s');

    $sql = "INSERT INTO personas (documento, nombre, rol, telefono, creado_en) 
            VALUES ('$documento', '$nombre', '$rol', '$telefono', '$creado_en')";

    echo mysqli_query($conn, $sql) ? 1 : 0;
} 
elseif ($accion === "modificar") {
    $id_persona = (int)$_POST['id_persona'];
    $documento  = mysqli_real_escape_string($conn, trim($_POST['documento']));
    $nombre     = mysqli_real_escape_string($conn, trim($_POST['nombre']));
    $rol        = mysqli_real_escape_string($conn, trim($_POST['rol']));
    $telefono   = mysqli_real_escape_string($conn, trim($_POST['telefono']));

    $sql = "UPDATE personas SET
            documento = '$documento', 
            nombre = '$nombre', 
            rol = '$rol', 
            telefono = '$telefono'
            WHERE id_persona = $id_persona";

    echo mysqli_query($conn, $sql) ? 1 : 0;
} 
elseif ($accion === "borrar") {
    $id_persona = (int)$_POST['id_persona'];

    $sql = "DELETE FROM personas WHERE id_persona = $id_persona";

    echo mysqli_query($conn, $sql) ? 1 : 0;
}

mysqli_close($conn);
