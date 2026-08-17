<?php
include 'conexion.php';
$conn = conexion();

// Establecer zona horaria de Colombia
date_default_timezone_set('America/Bogota');

$accion = $_GET['accion'];

if($accion == "insertar"){

    $id_producto = $_POST['id_producto'];
    $codigo_referencia = $_POST['codigo_referencia'];
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion = $_POST['descripcion'];
    $creado_en = date('Y-m-d H:i:s');

    $sql="INSERT INTO productos_catalogo(
          id_producto, codigo_referencia, nombre_producto, descripcion, creado_en
          )VALUE(
          '$id_producto', '$codigo_referencia', '$nombre_producto', '$descripcion', '$creado_en')";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "modificar"){

    $id_producto = $_POST['id_producto'];
    $codigo_referencia = $_POST['codigo_referencia'];
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion = $_POST['descripcion'];

    $sql="UPDATE productos_catalogo SET
          codigo_referencia = '$codigo_referencia', 
          nombre_producto = '$nombre_producto', 
          descripcion = '$descripcion'
          WHERE id_producto = '$id_producto'";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "borrar"){

    $id_producto = $_POST['id_producto'];

    $sql = "DELETE FROM productos_catalogo
            WHERE id_producto = '$id_producto'";

    echo $consulta = mysqli_query($conn, $sql);
}
