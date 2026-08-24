<?php
include 'conexion.php';
$conn = conexion();

date_default_timezone_set('America/Bogota');

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

if ($accion === "insertar") {
    $codigo_referencia = mysqli_real_escape_string($conn, trim($_POST['codigo_referencia']));
    $nombre_producto   = mysqli_real_escape_string($conn, trim($_POST['nombre_producto']));
    $descripcion       = mysqli_real_escape_string($conn, trim($_POST['descripcion']));
    $creado_en         = date('Y-m-d H:i:s');

    $sql = "INSERT INTO productos_catalogo (codigo_referencia, nombre_producto, descripcion, creado_en) 
            VALUES ('$codigo_referencia', '$nombre_producto', '$descripcion', '$creado_en')";

    echo mysqli_query($conn, $sql) ? 1 : 0;
} 
elseif ($accion === "modificar") {
    $id_producto       = (int)$_POST['id_producto'];
    $codigo_referencia = mysqli_real_escape_string($conn, trim($_POST['codigo_referencia']));
    $nombre_producto   = mysqli_real_escape_string($conn, trim($_POST['nombre_producto']));
    $descripcion       = mysqli_real_escape_string($conn, trim($_POST['descripcion']));

    $sql = "UPDATE productos_catalogo SET
            codigo_referencia = '$codigo_referencia', 
            nombre_producto = '$nombre_producto', 
            descripcion = '$descripcion'
            WHERE id_producto = $id_producto";

    echo mysqli_query($conn, $sql) ? 1 : 0;
} 
elseif ($accion === "borrar") {
    $id_producto = (int)$_POST['id_producto'];

    $sql = "DELETE FROM productos_catalogo WHERE id_producto = $id_producto";

    echo mysqli_query($conn, $sql) ? 1 : 0;
}

mysqli_close($conn);
