document.addEventListener('DOMContentLoaded', () => {
    // Escuchador dinámico para envíos de formularios AJAX
    document.addEventListener('submit', async (e) => {
        if (e.target.matches('.form-ajax-crear')) {
            e.preventDefault();
            await enviarFormularioModal(e.target, 'controllers/crear_orden.php');
        } else if (e.target.matches('.form-ajax-editar')) {
            e.preventDefault();
            await enviarFormularioModal(e.target, 'controllers/editar_orden.php');
        }
    });
});

function agregarFilaMaterial(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const templateSelect = document.getElementById('select-materiales-template');
    if (!templateSelect) return;

    const nuevaFila = document.createElement('div');
    nuevaFila.className = 'material-item-row';
    nuevaFila.innerHTML = `
        <select class="input-control select-material" required>
            ${templateSelect.innerHTML}
        </select>
        <input type="text" class="input-control input-medidas" placeholder="Medidas (ej: 2.40x1.20m)">
        <input type="number" step="0.0001" min="0.0001" class="input-control input-cantidad" placeholder="Cantidad x unidad" required>
        <button type="button" class="btn-action-danger" onclick="this.closest('.material-item-row').remove()">×</button>
    `;
    container.appendChild(nuevaFila);
}

async function enviarFormularioModal(form, url) {
    const formData = new FormData(form);
    const data = {};

    formData.forEach((value, key) => {
        if (!key.startsWith('materiales_lineas')) {
            if (['id_producto', 'id_cliente', 'id_asesor', 'id_fabricante', 'id_operario', 'unidades', 'id_orden'].includes(key)) {
                data[key] = value ? parseInt(value, 10) : null;
            } else {
                data[key] = value;
            }
        }
    });

    const materialesLineas = [];
    const container = form.querySelector('.materials-list');
    if (container) {
        const rows = container.querySelectorAll('.material-item-row');
        rows.forEach(row => {
            const idMat = row.querySelector('.select-material')?.value;
            const cant = row.querySelector('.input-cantidad')?.value;
            const med = row.querySelector('.input-medidas')?.value;

            if (idMat && cant) {
                materialesLineas.push({ 
                    id_material: parseInt(idMat, 10), 
                    cantidad: parseFloat(cant),
                    medidas: med ? med.trim() : null
                });
            }
        });
    }

    data['materiales_lineas'] = materialesLineas;

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json; charset=utf-8' },
            body: JSON.stringify(data)
        });
        const result = await res.json();

        if (result.success) {
            alert(result.message);
            window.location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (err) {
        alert('Error en la solicitud al servidor');
    }
}

async function cambiarEstado(idOrden, nuevoEstado) {
    try {
        const res = await fetch('controllers/cambiar_estado_orden.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json; charset=utf-8' },
            body: JSON.stringify({ id_orden: parseInt(idOrden, 10), nuevo_estado: nuevoEstado })
        });
        const result = await res.json();
        if (result.success) {
            window.location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (err) {
        alert('Error al actualizar el estado');
    }
}

async function eliminarOrden(idOrden) {
    if (!confirm('¿Confirma la eliminación permanente de esta orden?')) return;
    try {
        const res = await fetch('controllers/eliminar_orden.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json; charset=utf-8' },
            body: JSON.stringify({ id_orden: parseInt(idOrden, 10) })
        });
        const result = await res.json();
        if (result.success) {
            window.location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (err) {
        alert('Error al eliminar la orden');
    }
}

function abrirModal(id) { 
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'flex'; 
}

function cerrarModal(id) { 
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'none'; 
}
