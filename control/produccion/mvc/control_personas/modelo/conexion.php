<?php
function conexion(){
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'control_fabricacion';
    $conn = mysqli_connect($host, $user, $password, $database);
    return $conn;
}
?>
