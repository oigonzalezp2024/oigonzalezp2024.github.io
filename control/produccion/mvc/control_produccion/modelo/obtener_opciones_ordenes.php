<?php
header('Content-Type: application/json; charset=utf-8');

// Configuración de la conexión PDO
$host = 'localhost';
$db   = 'control_fabricacion';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Consulta de Personas clasificadas por Rol
    $sqlPersonas = "SELECT id_persona, nombre, rol FROM personas WHERE rol IN ('CLIENTE', 'ASESOR', 'FABRICANTE', 'OPERARIO') ORDER BY nombre ASC";
    $stmtPersonas = $pdo->query($sqlPersonas);
    $personas = $stmtPersonas->fetchAll();

    $clientes = [];
    $asesores = [];
    $fabricantes = [];
    $operarios = [];

    foreach ($personas as $p) {
        switch ($p['rol']) {
            case 'CLIENTE':
                $clientes[] = $p;
                break;
            case 'ASESOR':
                $asesores[] = $p;
                break;
            case 'FABRICANTE':
                $fabricantes[] = $p;
                break;
            case 'OPERARIO':
                $operarios[] = $p;
                break;
        }
    }

    // Consulta de Productos del Catálogo
    $sqlProductos = "SELECT id_producto, nombre_producto, codigo_referencia FROM productos_catalogo ORDER BY nombre_producto ASC";
    $stmtProductos = $pdo->query($sqlProductos);
    $productos = $stmtProductos->fetchAll();

    echo json_encode([
        'status' => 'success',
        'data' => [
            'clientes' => $clientes,
            'asesores' => $asesores,
            'fabricantes' => $fabricantes,
            'operarios' => $operarios,
            'productos' => $productos
        ]
    ]);

} catch (\PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
