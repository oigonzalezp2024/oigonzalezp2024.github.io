<?php
include 'conexion.php';
$conn = conexion();

// Define la zona horaria oficial de Colombia
date_default_timezone_set('America/Bogota');

$accion = $_GET['accion'] ?? '';

if ($accion == "insertar") {
    $numero_orden = $_POST['numero_orden'];
    $cliente_id = $_POST['cliente_id'];
    $asesor_id = $_POST['asesor_id'];
    $fabricante_id = $_POST['fabricante_id'];
    $operario_id = !empty($_POST['operario_id']) ? "'" . mysqli_real_escape_string($conn, $_POST['operario_id']) . "'" : "NULL";
    $producto_id = $_POST['producto_id'];
    $unidades = $_POST['unidades'];
    $estado = $_POST['estado'];
    $fecha_pedido = $_POST['fecha_pedido'];
    $fecha_entrega = $_POST['fecha_entrega'];
    
    // Genera timestamp en hora local de Colombia
    $creado_en = date('Y-m-d H:i:s');

    $sql = "INSERT INTO ordenes_fabricacion (
            numero_orden, cliente_id, asesor_id, fabricante_id, operario_id, 
            producto_id, unidades, estado, fecha_pedido, fecha_entrega, 
            creado_en
          ) VALUES (
            '$numero_orden', '$cliente_id', '$asesor_id', '$fabricante_id', $operario_id, 
            '$producto_id', '$unidades', '$estado', '$fecha_pedido', '$fecha_entrega', 
            '$creado_en')";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif ($accion == "modificar") {

    $id_orden = $_POST['id_orden'];
    $numero_orden = $_POST['numero_orden'];
    $cliente_id = $_POST['cliente_id'];
    $asesor_id = $_POST['asesor_id'];
    $fabricante_id = $_POST['fabricante_id'];
    $operario_id = !empty($_POST['operario_id']) ? "'" . mysqli_real_escape_string($conn, $_POST['operario_id']) . "'" : "NULL";
    $producto_id = $_POST['producto_id'];
    $unidades = $_POST['unidades'];
    $estado = $_POST['estado'];
    $fecha_pedido = $_POST['fecha_pedido'];
    $fecha_entrega = $_POST['fecha_entrega'];
    $costo_subtotal = $_POST['costo_subtotal'];
    $costo_mod = $_POST['costo_mod'];
    $costo_cif = $_POST['costo_cif'];
    $porcentaje_utilidad = $_POST['porcentaje_utilidad'];
    $monto_utilidad = $_POST['monto_utilidad'];
    $monto_total = $_POST['monto_total'];

    $sql = "UPDATE ordenes_fabricacion SET
            numero_orden = '$numero_orden', 
            cliente_id = '$cliente_id', 
            asesor_id = '$asesor_id', 
            fabricante_id = '$fabricante_id', 
            operario_id = $operario_id, 
            producto_id = '$producto_id', 
            unidades = '$unidades', 
            estado = '$estado', 
            fecha_pedido = '$fecha_pedido', 
            fecha_entrega = '$fecha_entrega', 
            costo_subtotal = '$costo_subtotal', 
            costo_mod = '$costo_mod', 
            costo_cif = '$costo_cif', 
            porcentaje_utilidad = '$porcentaje_utilidad', 
            monto_utilidad = '$monto_utilidad', 
            monto_total = '$monto_total'
            WHERE id_orden = '$id_orden'";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif ($accion == "borrar") {

    $id_orden = $_POST['id_orden'];

    $sql = "DELETE FROM ordenes_fabricacion WHERE id_orden = '$id_orden'";

    echo $consulta = mysqli_query($conn, $sql);
}
