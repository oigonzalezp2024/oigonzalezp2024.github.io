<?php
include 'conexion.php';
$conn = conexion();

// Establecer zona horaria de Colombia
date_default_timezone_set('America/Bogota');

$accion = $_GET['accion'];

if($accion == "insertar"){

    $id_persona = $_POST['id_persona'];
    $documento = $_POST['documento'];
    $nombre = $_POST['nombre'];
    $rol = $_POST['rol'];
    $telefono = $_POST['telefono'];
    $creado_en = date('Y-m-d H:i:s');

    $sql="INSERT INTO personas(
          id_persona, documento, nombre, rol, telefono, creado_en
          )VALUES(
          '$id_persona', '$documento', '$nombre', '$rol', '$telefono', '$creado_en')";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "modificar"){

    $id_persona = $_POST['id_persona'];
    $documento = $_POST['documento'];
    $nombre = $_POST['nombre'];
    $rol = $_POST['rol'];
    $telefono = $_POST['telefono'];

    $sql="UPDATE personas SET
          documento = '$documento', 
          nombre = '$nombre', 
          rol = '$rol', 
          telefono = '$telefono'
          WHERE id_persona = '$id_persona'";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "borrar"){

    $id_persona = $_POST['id_persona'];

    $sql = "DELETE FROM personas
            WHERE id_persona = '$id_persona'";

    echo $consulta = mysqli_query($conn, $sql);
}
