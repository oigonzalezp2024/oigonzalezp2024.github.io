<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asesores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?php include('librerias.php'); ?>
    <script src="../controlador/funciones_asesores.js"></script>
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
                    <h4 class="modal-title">Agregar Asesor</h4>
                </div>
                <div class="modal-body">
                    <label for="documento">Documento</label>
                    <input type="text" id="documento" class="form-control input-sm" required>

                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" class="form-control input-sm" required>

                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" class="form-control input-sm" required>
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
                    <h4 class="modal-title">Actualizar Asesor</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_personau">

                    <label for="documentou">Documento</label>
                    <input type="text" id="documentou" class="form-control input-sm" required>

                    <label for="nombreu">Nombre</label>
                    <input type="text" id="nombreu" class="form-control input-sm" required>

                    <label for="rolu">Rol</label>
                    <select id="rolu" class="form-control input-sm" required>
                        <option value="CLIENTE">CLIENTE</option>
                        <option value="ASESOR">ASESOR</option>
                        <option value="FABRICANTE">FABRICANTE</option>
                        <option value="OPERARIO">OPERARIO</option>
                    </select>

                    <label for="telefonou">Teléfono</label>
                    <input type="text" id="telefonou" class="form-control input-sm" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="actualizadatos">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            cargarTablaAsesores();

            $('#guardarnuevo').click(function () {
                agregardatosAsesor(
                    $('#documento').val(),
                    $('#nombre').val(),
                    $('#telefono').val()
                );
            });

            $('#actualizadatos').click(function () {
                modificarAsesor();
            });
        });
    </script>

    <?php include './footer.php'; ?>
</body>
</html>
