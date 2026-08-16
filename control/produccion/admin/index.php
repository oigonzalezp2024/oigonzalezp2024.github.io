<?php
declare(strict_types=1);

// Paginación ajustada a 5 registros por página
$paginaActual = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?: 1;
if ($paginaActual < 1) {
    $paginaActual = 1;
}

$porPagina = 5;
$offset    = ($paginaActual - 1) * $porPagina;

require_once __DIR__ . '/data.php';

// Desestructuración desde data.php
$ordenes               = $data['ordenes'];
$materialesDisponibles = $data['materiales'];
$productos             = $data['productos'];
$clientes              = $data['clientes'];
$asesores              = $data['asesores'];
$fabricantes           = $data['fabricantes'];
$operarios             = $data['operarios'];
$obtenerDetalles       = $data['obtenerDetalles'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Producción</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/produccion.css">
</head>
<body>

<select id="select-materiales-template" style="display:none;">
    <option value="">Seleccione material...</option>
    <?php foreach ($materialesDisponibles as $mat): ?>
        <option value="<?= $mat['id_material'] ?>"><?= htmlspecialchars($mat['nombre']) ?> ($<?= number_format((float)$mat['costo'], 2) ?>)</option>
    <?php endforeach; ?>
</select>

<div class="app-container">
    <div class="dashboard-card">
        <div class="dashboard-header">
            <div>
                <h1>🛠️ Control de Producción e Insumos</h1>
            </div>
            <button class="btn btn-primary" onclick="abrirModal('modalCrear')">➕ Sugerir / Registrar Orden</button>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nº Orden</th>
                        <th>Producto / Cliente</th>
                        <th>Responsable</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ordenes)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">No hay órdenes registradas en esta página.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ordenes as $ord): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars((string)$ord['numero_orden']) ?></strong></td>
                            <td><?= htmlspecialchars((string)$ord['nombre_producto']) ?><br><small>Cliente: <?= htmlspecialchars((string)$ord['cliente_nombre']) ?></small></td>
                            <td><?= htmlspecialchars((string)($ord['operario_nombre'] ?? 'Sin asignar')) ?></td>
                            <td>
                                <select onchange="cambiarEstado(<?= $ord['id_orden'] ?>, this.value)" class="input-control">
                                    <?php foreach (['planeacion', 'activa', 'en ejecucion', 'suspendida', 'cancelada', 'terminada'] as $st): ?>
                                        <option value="<?= $st ?>" <?= $ord['estado'] === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <button class="btn-action-warning" onclick="abrirModal('modalEditar-<?= $ord['id_orden'] ?>')">✏️</button>
                                <button class="btn-action-danger" onclick="eliminarOrden(<?= $ord['id_orden'] ?>)">🗑️</button>

                                <div id="modalEditar-<?= $ord['id_orden'] ?>" class="modal-overlay">
                                    <div class="modal-box">
                                        <div class="modal-top">
                                            <h3>Modificar Orden <?= htmlspecialchars((string)$ord['numero_orden']) ?></h3>
                                            <button onclick="cerrarModal('modalEditar-<?= $ord['id_orden'] ?>')">&times;</button>
                                        </div>
                                        <form class="form-ajax-editar">
                                            <input type="hidden" name="id_orden" value="<?= $ord['id_orden'] ?>">
                                            <div class="modal-content">
                                                <div class="form-grid-layout">
                                                    <div class="form-field-group">
                                                        <label>Nº Orden</label>
                                                        <input type="text" name="numero_orden" class="input-control" value="<?= htmlspecialchars((string)$ord['numero_orden']) ?>" required>
                                                    </div>
                                                    <div class="form-field-group">
                                                        <label>Unidades</label>
                                                        <input type="number" name="unidades" class="input-control" value="<?= $ord['unidades'] ?>" required>
                                                    </div>
                                                    <div class="form-field-group full-width">
                                                        <label>Materiales</label>
                                                        <div id="container-edit-<?= $ord['id_orden'] ?>" class="materials-list">
                                                            <?php
                                                            $detalles = $obtenerDetalles((int)$ord['id_orden']);
                                                            foreach ($detalles as $det):
                                                            ?>
                                                            <div class="material-item-row">
                                                                <select class="input-control select-material" required>
                                                                    <?php foreach ($materialesDisponibles as $mat): ?>
                                                                        <option value="<?= $mat['id_material'] ?>" <?= $mat['id_material'] == $det['id_material'] ? 'selected' : '' ?>>
                                                                            <?= htmlspecialchars((string)$mat['nombre']) ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <input type="text" class="input-control input-medidas" value="<?= htmlspecialchars((string)($det['medidas'] ?? '')) ?>" placeholder="Medidas">
                                                                <input type="number" step="0.0001" class="input-control input-cantidad" value="<?= $det['cantidad'] / max(1, $ord['unidades']) ?>" required>
                                                                <button type="button" class="btn-action-danger" onclick="this.closest('.material-item-row').remove()">×</button>
                                                            </div>
                                                            <?php endforeach; ?>
                                                        </div>

                                                        <button type="button" class="btn btn-secondary btn-add-material" onclick="agregarFilaMaterial('container-edit-<?= $ord['id_orden'] ?>')">➕ Agregar Material</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-bottom">
                                                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar-<?= $ord['id_orden'] ?>')">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Barra de Paginación -->
        <div class="dashboard-footer" style="display: flex; justify: space-between; align-items: center; padding: 15px 0;">
            <div>
                <span>Página <strong><?= $paginaActual ?></strong> (Mostrando hasta <?= $porPagina ?> registros)</span>
            </div>
            <div style="display: flex; gap: 10px;">
                <?php if ($paginaActual > 1): ?>
                    <a href="?pagina=<?= $paginaActual - 1 ?>" class="btn btn-secondary">« Anterior</a>
                <?php endif; ?>

                <?php if (count($ordenes) === $porPagina): ?>
                    <a href="?pagina=<?= $paginaActual + 1 ?>" class="btn btn-secondary">Siguiente »</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<div id="modalCrear" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-top">
            <h3>Nueva Orden de Producción</h3>
            <button onclick="cerrarModal('modalCrear')">&times;</button>
        </div>
        <form class="form-ajax-crear">
            <div class="modal-content">
                <div class="form-grid-layout">
                    <div class="form-field-group">
                        <label>Producto</label>
                        <select name="id_producto" class="input-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($productos as $p): ?>
                                <option value="<?= $p['id_producto'] ?>"><?= htmlspecialchars((string)$p['nombre_producto']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field-group">
                        <label>Cliente</label>
                        <select name="id_cliente" class="input-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id_tercero'] ?>"><?= htmlspecialchars((string)$c['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field-group">
                        <label>Asesor</label>
                        <select name="id_asesor" class="input-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($asesores as $a): ?>
                                <option value="<?= $a['id_tercero'] ?>"><?= htmlspecialchars((string)$a['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field-group">
                        <label>Fabricante</label>
                        <select name="id_fabricante" class="input-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($fabricantes as $f): ?>
                                <option value="<?= $f['id_tercero'] ?>"><?= htmlspecialchars((string)$f['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field-group full-width">
                        <label>Materiales</label>

                        <div id="container-crear" class="materials-list"></div>

                        <button type="button" class="btn btn-secondary btn-add-material" onclick="agregarFilaMaterial('container-crear')">➕ Agregar Material</button>
                    </div>
                </div>
            </div>
            <div class="modal-bottom">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Orden</button>
            </div>
        </form>
    </div>
</div>

<script src="js/produccion.js"></script>
</body>
</html>
