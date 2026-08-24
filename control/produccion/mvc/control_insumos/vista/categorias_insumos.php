<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Categorías</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?php include('librerias.php'); ?>
    <script src="../controlador/funciones_categorias_insumos.js"></script>
</head>
<body id="body">
    <?php include 'header.php'; ?>

    <!-- Contenedor dinámico cargado vía AJAX -->
    <div id="tabla"></div>

    <!-- MODAL PARA INSERTAR REGISTROS -->
    <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevoLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalNuevoLabel">Agregar Categoría</h4>
                </div>
                <div class="modal-body">
                    <label for="nombre_categoria">Nombre Categoría</label>
                    <input type="text" id="nombre_categoria" class="form-control input-sm" required>

                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" rows="3" class="form-control input-sm"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA EDICIÓN DE DATOS -->
    <div class="modal fade" id="modalEdicion" tabindex="-1" role="dialog" aria-labelledby="modalEdicionLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalEdicionLabel">Actualizar Categoría</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_categoriau">

                    <label for="nombre_categoriau">Nombre Categoría</label>
                    <input type="text" id="nombre_categoriau" class="form-control input-sm" required>

                    <label for="descripcionu">Descripción</label>
                    <textarea id="descripcionu" rows="3" class="form-control input-sm"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="actualizadatos">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT CONTROLADOR DE EVENTOS -->
    <script type="text/javascript">
        function cargarTabla() {
            $('#tabla').load('componentes/vista_categorias_insumos.php');
        }

        $(document).ready(function () {
            cargarTabla();

            $('#guardarnuevo').click(function () {
                agregardatos(
                    $('#nombre_categoria').val(),
                    $('#descripcion').val()
                );
            });

            $('#actualizadatos').click(function () {
                modificarCategoria();
            });
        });
    </script>

    <?php include './footer.php'; ?>
</body>
</html>
