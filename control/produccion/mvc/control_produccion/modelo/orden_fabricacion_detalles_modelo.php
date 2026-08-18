<?php
include 'conexion.php';
$conn = conexion();

$accion = $_GET['accion'];

if($accion == "insertar"){

    $orden_id = $_POST['orden_id'];
    $material_id = $_POST['material_id'];
    $medidas = $_POST['medidas'];
    $cantidad = $_POST['cantidad'];
    $cantidad_consumida = $_POST['cantidad_consumida'];
    $valor_unitario = $_POST['valor_unitario'];
    $valor_total = $_POST['valor_total'];
    $es_destacado = $_POST['es_destacado'];

    $sql="INSERT INTO orden_fabricacion_detalles(
          orden_id, material_id, medidas, cantidad, cantidad_consumida, valor_unitario, valor_total, es_destacado
          )VALUE(
          '$orden_id', '$material_id', '$medidas', '$cantidad', '$cantidad_consumida', '$valor_unitario', '$valor_total', '$es_destacado')";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "modificar"){

    $id_detalle = $_POST['id_detalle'];
    $orden_id = $_POST['orden_id'];
    $material_id = $_POST['material_id'];
    $medidas = $_POST['medidas'];
    $cantidad = $_POST['cantidad'];
    $cantidad_consumida = $_POST['cantidad_consumida'];
    $valor_unitario = $_POST['valor_unitario'];
    $valor_total = $_POST['valor_total'];
    $es_destacado = $_POST['es_destacado'];

    $sql="UPDATE orden_fabricacion_detalles SET
          orden_id = '$orden_id', 
          material_id = '$material_id', 
          medidas = '$medidas', 
          cantidad = '$cantidad', 
          cantidad_consumida = '$cantidad_consumida', 
          valor_unitario = '$valor_unitario', 
          valor_total = '$valor_total', 
          es_destacado = '$es_destacado'
          WHERE id_detalle = '$id_detalle'";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "borrar"){

    $id_detalle = $_POST['id_detalle'];

    $sql = "DELETE FROM orden_fabricacion_detalles
            WHERE id_detalle = '$id_detalle'";

    echo $consulta = mysqli_query($conn, $sql);
}


?>