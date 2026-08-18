<?php
include 'conexion.php';
$conn = conexion();

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

if($accion == "insertar"){

    $codigo_material = $_POST['codigo_material'];
    $descripcion_material = $_POST['descripcion_material'];
    $unidad_medida = $_POST['unidad_medida'];
    $precio_unitario_defecto = $_POST['precio_unitario_defecto'];
    
    // Si viene vacío o no numérico, asigna NULL explícito
    $categoria_id = (!empty($_POST['categoria_id']) && is_numeric($_POST['categoria_id'])) ? (int)$_POST['categoria_id'] : NULL;
    
    $stock_minimo = $_POST['stock_minimo'];
    $stock_actual = $_POST['stock_actual'];
    $stock_maximo = $_POST['stock_maximo'];

    $sql = "INSERT INTO materiales (codigo_material, descripcion_material, unidad_medida, precio_unitario_defecto, categoria_id, stock_minimo, stock_actual, stock_maximo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    
    // Se usa 's' o 'i'/'d' según corresponda
    mysqli_stmt_bind_param($stmt, "sssddddd", 
        $codigo_material, 
        $descripcion_material, 
        $unidad_medida, 
        $precio_unitario_defecto, 
        $categoria_id, 
        $stock_minimo, 
        $stock_actual, 
        $stock_maximo
    );
    
    echo mysqli_stmt_execute($stmt) ? 1 : 0;
    mysqli_stmt_close($stmt);

} elseif($accion == "modificar"){

    $id_material = $_POST['id_material'];
    $codigo_material = $_POST['codigo_material'];
    $descripcion_material = $_POST['descripcion_material'];
    $unidad_medida = $_POST['unidad_medida'];
    $precio_unitario_defecto = $_POST['precio_unitario_defecto'];
    
    // Si viene vacío o no numérico, asigna NULL explícito
    $categoria_id = (!empty($_POST['categoria_id']) && is_numeric($_POST['categoria_id'])) ? (int)$_POST['categoria_id'] : NULL;
    
    $stock_minimo = $_POST['stock_minimo'];
    $stock_actual = $_POST['stock_actual'];
    $stock_maximo = $_POST['stock_maximo'];

    $sql = "UPDATE materiales SET 
            codigo_material = ?, 
            descripcion_material = ?, 
            unidad_medida = ?, 
            precio_unitario_defecto = ?, 
            categoria_id = ?, 
            stock_minimo = ?, 
            stock_actual = ?, 
            stock_maximo = ? 
            WHERE id_material = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssdddddi", 
        $codigo_material, 
        $descripcion_material, 
        $unidad_medida, 
        $precio_unitario_defecto, 
        $categoria_id, 
        $stock_minimo, 
        $stock_actual, 
        $stock_maximo, 
        $id_material
    );

    echo mysqli_stmt_execute($stmt) ? 1 : 0;
    mysqli_stmt_close($stmt);

} elseif($accion == "borrar"){

    $id_material = $_POST['id_material'];

    $sql = "DELETE FROM materiales WHERE id_material = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_material);

    echo mysqli_stmt_execute($stmt) ? 1 : 0;
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
