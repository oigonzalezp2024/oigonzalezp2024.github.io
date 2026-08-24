<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?php include('librerias.php'); ?>
    <script src="../controlador/funciones_productos_catalogo.js"></script>
</head>
<body id="body">
    <?php include 'header.php'; ?>

    <div id="tabla"></div>

    <!-- MODAL INSERTAR -->
    <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar Producto</h4>
                </div>
                <div class="modal-body">
                    <label for="codigo_referencia">Código Referencia</label>
                    <input type="text" id="codigo_referencia" class="form-control input-sm" required>

                    <label for="nombre_producto">Nombre del Producto</label>
                    <input type="text" id="nombre_producto" class="form-control input-sm" required>

                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" rows="3" class="form-control input-sm"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDICIÓN -->
    <div class="modal fade" id="modalEdicion" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Actualizar Producto</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_productou">

                    <label for="codigo_referenciau">Código Referencia</label>
                    <input type="text" id="codigo_referenciau" class="form-control input-sm" required>

                    <label for="nombre_productou">Nombre del Producto</label>
                    <input type="text" id="nombre_productou" class="form-control input-sm" required>

                    <label for="descripcionu">Descripción</label>
                    <textarea id="descripcionu" rows="3" class="form-control input-sm"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="actualizadatos">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            cargarTablaProductos();

            $('#guardarnuevo').click(function () {
                agregardatosProducto(
                    $('#codigo_referencia').val(),
                    $('#nombre_producto').val(),
                    $('#descripcion').val()
                );
            });

            $('#actualizadatos').click(function () {
                modificarProducto();
            });
        });
    </script>

    <?php include './footer.php'; ?>
</body>
</html>
