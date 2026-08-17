<?php
function conexion(){
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'control_fabricacion_normal';
    $conn = mysqli_connect($host, $user, $password, $database);
    return $conn;
}
?>
