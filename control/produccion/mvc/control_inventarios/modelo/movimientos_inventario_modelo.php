<?php
include 'conexion.php';
$conn = conexion();

$accion = $_GET['accion'];

if($accion == "insertar"){

    $id_movimiento = $_POST['id_movimiento'];
    $tipo_item = $_POST['tipo_item'];
    $id_item = $_POST['id_item'];
    $tipo_movimiento = $_POST['tipo_movimiento'];
    $cantidad = $_POST['cantidad'];
    $orden_id = $_POST['orden_id'];
    $observacion = $_POST['observacion'];
    $fecha_movimiento = $_POST['fecha_movimiento'];

    $sql="INSERT INTO movimientos_inventario(
          id_movimiento, tipo_item, id_item, tipo_movimiento, cantidad, orden_id, observacion, fecha_movimiento
          )VALUE(
          '$id_movimiento', '$tipo_item', '$id_item', '$tipo_movimiento', '$cantidad', '$orden_id', '$observacion', '$fecha_movimiento')";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "modificar"){

    $id_movimiento = $_POST['id_movimiento'];
    $tipo_item = $_POST['tipo_item'];
    $id_item = $_POST['id_item'];
    $tipo_movimiento = $_POST['tipo_movimiento'];
    $cantidad = $_POST['cantidad'];
    $orden_id = $_POST['orden_id'];
    $observacion = $_POST['observacion'];
    $fecha_movimiento = $_POST['fecha_movimiento'];

    $sql="UPDATE movimientos_inventario SET
          tipo_item = '$tipo_item', 
          id_item = '$id_item', 
          tipo_movimiento = '$tipo_movimiento', 
          cantidad = '$cantidad', 
          orden_id = '$orden_id', 
          observacion = '$observacion', 
          fecha_movimiento = '$fecha_movimiento'
          WHERE id_movimiento = '$id_movimiento'";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "borrar"){

    $id_movimiento = $_POST['id_movimiento'];

    $sql = "DELETE FROM movimientos_inventario
            WHERE id_movimiento = '$id_movimiento'";

    echo $consulta = mysqli_query($conn, $sql);
}


?>