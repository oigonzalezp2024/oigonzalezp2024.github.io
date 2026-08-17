<?php
include 'conexion.php';
$conn = conexion();

$accion = $_GET['accion'];

if($accion == "insertar"){

    $id_inventario_pt = $_POST['id_inventario_pt'];
    $producto_id = $_POST['producto_id'];
    $orden_id = $_POST['orden_id'];
    $cantidad = $_POST['cantidad'];
    $ubicacion_bodega = $_POST['ubicacion_bodega'];
    $actualizado_en = $_POST['actualizado_en'];

    $sql="INSERT INTO inventario_producto_terminado(
          id_inventario_pt, producto_id, orden_id, cantidad, ubicacion_bodega, actualizado_en
          )VALUE(
          '$id_inventario_pt', '$producto_id', '$orden_id', '$cantidad', '$ubicacion_bodega', '$actualizado_en')";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "modificar"){

    $id_inventario_pt = $_POST['id_inventario_pt'];
    $producto_id = $_POST['producto_id'];
    $orden_id = $_POST['orden_id'];
    $cantidad = $_POST['cantidad'];
    $ubicacion_bodega = $_POST['ubicacion_bodega'];
    $actualizado_en = $_POST['actualizado_en'];

    $sql="UPDATE inventario_producto_terminado SET
          producto_id = '$producto_id', 
          orden_id = '$orden_id', 
          cantidad = '$cantidad', 
          ubicacion_bodega = '$ubicacion_bodega', 
          actualizado_en = '$actualizado_en'
          WHERE id_inventario_pt = '$id_inventario_pt'";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "borrar"){

    $id_inventario_pt = $_POST['id_inventario_pt'];

    $sql = "DELETE FROM inventario_producto_terminado
            WHERE id_inventario_pt = '$id_inventario_pt'";

    echo $consulta = mysqli_query($conn, $sql);
}


?>