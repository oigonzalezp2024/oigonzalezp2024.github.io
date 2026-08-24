<?php
include 'conexion.php';
$conn = conexion();

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

if ($accion == "insertar") {
    $nombre_categoria = mysqli_real_escape_string($conn, $_POST['nombre_categoria']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);

    // id_categoria es AUTO_INCREMENT en la BD, no se envía manualmente
    $sql = "INSERT INTO categorias_insumos (nombre_categoria, descripcion) 
            VALUES ('$nombre_categoria', '$descripcion')";

    echo mysqli_query($conn, $sql) ? 1 : 0;
} 
elseif ($accion == "modificar") {
    $id_categoria = (int)$_POST['id_categoria'];
    $nombre_categoria = mysqli_real_escape_string($conn, $_POST['nombre_categoria']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);

    $sql = "UPDATE categorias_insumos SET
            nombre_categoria = '$nombre_categoria', 
            descripcion = '$descripcion'
            WHERE id_categoria = '$id_categoria'";

    echo mysqli_query($conn, $sql) ? 1 : 0;
} 
elseif ($accion == "borrar") {
    $id_categoria = (int)$_POST['id_categoria'];

    $sql = "DELETE FROM categorias_insumos WHERE id_categoria = '$id_categoria'";

    echo mysqli_query($conn, $sql) ? 1 : 0;
}

mysqli_close($conn);
