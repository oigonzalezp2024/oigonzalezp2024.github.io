<?php
include 'conexion.php';
$conn = conexion();

$accion = $_GET['accion'];

if($accion == "insertar"){

    $id_categoria = $_POST['id_categoria'];
    $nombre_categoria = $_POST['nombre_categoria'];
    $descripcion = $_POST['descripcion'];

    $sql="INSERT INTO categorias_insumos(
          id_categoria, nombre_categoria, descripcion
          )VALUE(
          '$id_categoria', '$nombre_categoria', '$descripcion')";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "modificar"){

    $id_categoria = $_POST['id_categoria'];
    $nombre_categoria = $_POST['nombre_categoria'];
    $descripcion = $_POST['descripcion'];

    $sql="UPDATE categorias_insumos SET
          nombre_categoria = '$nombre_categoria', 
          descripcion = '$descripcion'
          WHERE id_categoria = '$id_categoria'";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "borrar"){

    $id_categoria = $_POST['id_categoria'];

    $sql = "DELETE FROM categorias_insumos
            WHERE id_categoria = '$id_categoria'";

    echo $consulta = mysqli_query($conn, $sql);
}


?>