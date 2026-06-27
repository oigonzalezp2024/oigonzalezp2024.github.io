<?php
require_once "../src/domain/Article.php";
require_once "../src/domain/ArtContenido.php";
require_once "../src/infrastructure/ArticuloRepository.php";
require_once "../src/application/ArticuloPresentacion.php";
require_once "../src/infrastructure/Database.php"; 

header('Content-Type: application/json; charset=utf-8');

try {
    // 1. Inicializar la conexión PDO
    $db = Database::getConnection(); // Asegúrate de que este método devuelva el PDO configurado

    // 2. Inyección de dependencias
    $articuloRepository = new ArticuloRepositoryMySQL($db);
    $articuloPresentacion = new ArticuloPresentacion($articuloRepository);

    // 3. Obtener los datos
    // Se recomienda validar que el ID venga de una fuente segura (ej. $_GET['id'])
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 3;
    $data = $articuloPresentacion->construirArticulo($id);

    // 4. Salida exitosa
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // 5. Manejo seguro de errores
    // No imprimas $e->getMessage() directamente en producción si contiene rutas de archivos
    http_response_code(500);
    echo json_encode([
        "error" => "Ocurrió un error al procesar la solicitud.",
        "details" => $e->getMessage() // Útil solo en desarrollo, en producción podrías quitarlo
    ]);
}
