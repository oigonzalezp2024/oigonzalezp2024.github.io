<?php
/**
 * db.php - Conexión PDO a la base de datos MySQL
 */
function getDBConnection(): PDO {
    $host = 'localhost';
    $dbname = 'control_fabricacion'; // Ajusta al nombre de tu BD
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
        exit;
    }
}
