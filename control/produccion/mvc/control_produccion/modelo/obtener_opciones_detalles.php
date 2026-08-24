<?php
include_once 'conexion.php';
$conn = conexion();

header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'data' => []
];

// Se utiliza descripcion_material en lugar de nombre_material
$sql_materiales = "SELECT id_material, descripcion_material FROM materiales ORDER BY descripcion_material ASC";
$res_materiales = mysqli_query($conn, $sql_materiales);

if ($res_materiales) {
    $materiales = [];
    while ($row = mysqli_fetch_assoc($res_materiales)) {
        $materiales[] = $row;
    }
    
    $response['status'] = 'success';
    $response['data'] = [
        'materiales' => $materiales
    ];
} else {
    $response['message'] = mysqli_error($conn);
}

echo json_encode($response);
mysqli_close($conn);
