<?php
// data.php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

// 1. Interfaz Única (Cumple ISP y DIP)
interface DataProviderInterface {
    public function getData(): mixed;
}

// 2. Servicios de Dominio (Cumplen SRP)

class ProductService implements DataProviderInterface {
    public function __construct(private PDO $pdo) {}

    public function getData(): array {
        return $this->pdo->query("SELECT id_producto, nombre_producto FROM productos_catalogo")->fetchAll(PDO::FETCH_ASSOC);
    }
}

class MaterialService implements DataProviderInterface {
    public function __construct(private PDO $pdo) {}

    public function getData(): array {
        $sql = "SELECT id_material, descripcion_material AS nombre, precio_unitario_defecto AS costo, unidad_medida 
                FROM materiales 
                ORDER BY descripcion_material ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}

class ClienteService implements DataProviderInterface {
    public function __construct(private PDO $pdo) {}

    public function getData(): array {
        return $this->pdo->query("SELECT id_persona, nombre FROM personas WHERE rol = 'CLIENTE'")->fetchAll(PDO::FETCH_ASSOC);
    }
}

class AsesorService implements DataProviderInterface {
    public function __construct(private PDO $pdo) {}

    public function getData(): array {
        return $this->pdo->query("SELECT id_persona, nombre FROM personas WHERE rol = 'ASESOR'")->fetchAll(PDO::FETCH_ASSOC);
    }
}

class FabricanteService implements DataProviderInterface {
    public function __construct(private PDO $pdo) {}

    public function getData(): array {
        return $this->pdo->query("SELECT id_persona, nombre FROM personas WHERE rol = 'FABRICANTE'")->fetchAll(PDO::FETCH_ASSOC);
    }
}

class OperarioService implements DataProviderInterface {
    public function __construct(private PDO $pdo) {}

    public function getData(): array {
        return $this->pdo->query("SELECT id_persona, nombre FROM personas WHERE rol = 'OPERARIO'")->fetchAll(PDO::FETCH_ASSOC);
    }
}

class OrderService implements DataProviderInterface {
    public function __construct(
        private PDO $pdo,
        private int $limit = 50,
        private int $offset = 0
    ) {}

    public function getData(): array {
        $stmt = $this->pdo->prepare("
            SELECT o.*, p.nombre_producto, c.nombre AS cliente_nombre, op.nombre AS operario_nombre
            FROM ordenes_fabricacion o
            INNER JOIN productos_catalogo p ON o.producto_id = p.id_producto
            INNER JOIN personas c ON o.cliente_id = c.id_persona
            LEFT JOIN personas op ON o.operario_id = op.id_persona
            ORDER BY o.id_orden DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $this->offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Retorna una función diferida (Closure) para evitar consultas N+1 y sobrecarga de memoria
class OrderDetailsService implements DataProviderInterface {
    public function __construct(private PDO $pdo) {}

    public function getData(): Closure {
        return function (int $orderId): array {
            $stmt = $this->pdo->prepare("
                SELECT d.*, m.descripcion_material AS nombre
                FROM orden_fabricacion_detalles d
                INNER JOIN materiales m ON d.material_id = m.id_material
                WHERE d.orden_id = :id
            ");
            $stmt->execute([':id' => $orderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        };
    }
}

// 3. Coordinador de Datos
class Desplegable {
    public function __construct(
        private DataProviderInterface $productService,
        private DataProviderInterface $materialService,
        private DataProviderInterface $clienteService,
        private DataProviderInterface $asesorService,
        private DataProviderInterface $fabricanteService,
        private DataProviderInterface $operarioService,
        private DataProviderInterface $orderService,
        private DataProviderInterface $orderDetailsService
    ) {}

    public function desplegableDatos(): array {
        return [
            'productos'       => $this->productService->getData(),
            'materiales'      => $this->materialService->getData(),
            'clientes'        => $this->clienteService->getData(),
            'asesores'        => $this->asesorService->getData(),
            'fabricantes'     => $this->fabricanteService->getData(),
            'operarios'       => $this->operarioService->getData(),
            'ordenes'         => $this->orderService->getData(),
            'obtenerDetalles' => $this->orderDetailsService->getData()
        ];
    }
}

// 4. Instanciación y Ejecución
$pdo = getPDOConnection();

// Recibe las variables definidas en index.php o asigna valores por defecto
$porPagina = $porPagina ?? 50;
$offset    = $offset ?? 0;

$desplegable = new Desplegable(
    new ProductService($pdo),
    new MaterialService($pdo),
    new ClienteService($pdo),
    new AsesorService($pdo),
    new FabricanteService($pdo),
    new OperarioService($pdo),
    new OrderService($pdo, $porPagina, $offset),
    new OrderDetailsService($pdo)
);

$data = $desplegable->desplegableDatos();
