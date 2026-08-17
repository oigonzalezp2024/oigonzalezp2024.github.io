<?php
/**
 * api.php - Backend REST API para operaciones CRUD genéricas
 */
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$entity = $_GET['entity'] ?? '';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;

$db = getDBConnection();

$allowedEntities = [
    'terceros'                  => ['id' => 'id_tercero'],
    'productos_catalogo'        => ['id' => 'id_producto'],
    'materiales'                => ['id' => 'id_material'],
    'ordenes_fabricacion'       => ['id' => 'id_orden'],
    'orden_fabricacion_detalles'=> ['id' => 'id_detalle']
];

if (!array_key_exists($entity, $allowedEntities)) {
    http_response_code(400);
    echo json_encode(['error' => 'Entidad no válida']);
    exit;
}

$primaryKey = $allowedEntities[$entity]['id'];

try {
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $db->prepare("SELECT * FROM {$entity} WHERE {$primaryKey} = :id");
                $stmt->execute([':id' => $id]);
                $data = $stmt->fetch();
                if (!$data) {
                    http_response_code(404);
                    echo json_encode(['error' => 'Registro no encontrado']);
                    exit;
                }
            } else {
                $stmt = $db->query("SELECT * FROM {$entity} ORDER BY {$primaryKey} DESC");
                $data = $stmt->fetchAll();
            }
            echo json_encode($data);
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            unset($input[$primaryKey]);

            $columns = array_keys($input);
            $fields  = implode(', ', $columns);
            $params  = ':' . implode(', :', $columns);

            $stmt = $db->prepare("INSERT INTO {$entity} ({$fields}) VALUES ({$params})");
            $stmt->execute($input);

            echo json_encode(['status' => 'success', 'id' => $db->lastInsertId()]);
            break;

        case 'PUT':
        // Dentro de api.php -> case 'PUT':

        if ($entity === 'ordenes_fabricacion') {
            $stmtCheck = $db->prepare("SELECT estado FROM ordenes_fabricacion WHERE id_orden = :id");
            $stmtCheck->execute([':id' => $id]);
            $ordenActual = $stmtCheck->fetch();

            if (!$ordenActual) {
                http_response_code(404);
                echo json_encode(['error' => 'Orden no encontrada']);
                exit;
            }

            $userRole = $_SERVER['HTTP_X_USER_ROLE'] ?? 'ADMIN';

            if ($userRole === 'OPERARIO') {
                $estadosPermitidos = ['activa', 'en ejecucion'];

                if (!in_array($ordenActual['estado'], $estadosPermitidos)) {
                    http_response_code(403);
                    echo json_encode([
                        'error' => "Permiso denegado: El operario solo puede modificar órdenes en estado 'activa' o 'en ejecucion'. Estado actual: '{$ordenActual['estado']}'."
                    ]);
                    exit;
                }
            }
        }

            echo json_encode(['status' => 'success']);
            break;

        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'ID requerido para eliminar']);
                exit;
            }
            $stmt = $db->prepare("DELETE FROM {$entity} WHERE {$primaryKey} = :id");
            $stmt->execute([':id' => $id]);

            echo json_encode(['status' => 'success']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
