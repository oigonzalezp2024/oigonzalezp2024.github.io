<?php
include 'conexion.php';
$conn = conexion();

$accion = $_GET['accion'];

if($accion == "insertar"){

    $id_material = $_POST['id_material'];
    $codigo_material = $_POST['codigo_material'];
    $descripcion_material = $_POST['descripcion_material'];
    $unidad_medida = $_POST['unidad_medida'];
    $precio_unitario_defecto = $_POST['precio_unitario_defecto'];
    $categoria_id = $_POST['categoria_id'];
    $stock_minimo = $_POST['stock_minimo'];
    $stock_actual = $_POST['stock_actual'];
    $stock_maximo = $_POST['stock_maximo'];
    $creado_en = $_POST['creado_en'];

    $sql="INSERT INTO materiales(
          id_material, codigo_material, descripcion_material, unidad_medida, precio_unitario_defecto, categoria_id, stock_minimo, stock_actual, stock_maximo, creado_en
          )VALUE(
          '$id_material', '$codigo_material', '$descripcion_material', '$unidad_medida', '$precio_unitario_defecto', '$categoria_id', '$stock_minimo', '$stock_actual', '$stock_maximo', '$creado_en')";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "modificar"){

    $id_material = $_POST['id_material'];
    $codigo_material = $_POST['codigo_material'];
    $descripcion_material = $_POST['descripcion_material'];
    $unidad_medida = $_POST['unidad_medida'];
    $precio_unitario_defecto = $_POST['precio_unitario_defecto'];
    $categoria_id = $_POST['categoria_id'];
    $stock_minimo = $_POST['stock_minimo'];
    $stock_actual = $_POST['stock_actual'];
    $stock_maximo = $_POST['stock_maximo'];
    $creado_en = $_POST['creado_en'];

    $sql="UPDATE materiales SET
          codigo_material = '$codigo_material', 
          descripcion_material = '$descripcion_material', 
          unidad_medida = '$unidad_medida', 
          precio_unitario_defecto = '$precio_unitario_defecto', 
          categoria_id = '$categoria_id', 
          stock_minimo = '$stock_minimo', 
          stock_actual = '$stock_actual', 
          stock_maximo = '$stock_maximo', 
          creado_en = '$creado_en'
          WHERE id_material = '$id_material'";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "borrar"){

    $id_material = $_POST['id_material'];

    $sql = "DELETE FROM materiales
            WHERE id_material = '$id_material'";

    echo $consulta = mysqli_query($conn, $sql);
}


?>