let dataAgenda = [];

// Elementos DOM
const taskGrid = document.getElementById('taskGrid');
const searchInput = document.getElementById('searchInput');
const priorityFilter = document.getElementById('priorityFilter');
const statusFilter = document.getElementById('statusFilter');
const categoryFilter = document.getElementById('categoryFilter');
const exportBtn = document.getElementById('exportBtn');
const fileInput = document.getElementById('fileInput');

// Elementos Modal
const taskModal = document.getElementById('taskModal');
const openModalBtn = document.getElementById('openModalBtn');
const closeModalBtn = document.getElementById('closeModalBtn');
const cancelModalBtn = document.getElementById('cancelModalBtn');
const newTaskForm = document.getElementById('newTaskForm');

// Cargar Inicial
async function initApp() {
  const localData = localStorage.getItem('workpulse_data');
  
  if (localData) {
    dataAgenda = JSON.parse(localData);
    setupCategories();
    render();
  } else {
    try {
      const res = await fetch('data.json');
      if (!res.ok) throw new Error('No se halló data.json');
      const rawData = await res.json();
      
      dataAgenda = rawData.map(item => ({
        ...item,
        estado: item.estado || 'pendiente',
        seguimiento: item.seguimiento || ''
      }));
      
      saveLocal();
      setupCategories();
      render();
    } catch (err) {
      console.error(err);
      taskGrid.innerHTML = `<p style="grid-column: 1/-1; text-align: center; color: var(--status-urgent);">
        Error al cargar data.json. Si abres el HTML directamente sin servidor local, usa "Importar JSON".
      </p>`;
    }
  }
}

function saveLocal() {
  localStorage.setItem('workpulse_data', JSON.stringify(dataAgenda));
}

function setupCategories() {
  const categories = [...new Set(dataAgenda.map(item => item.categoria))];
  const currentSelection = categoryFilter.value;
  categoryFilter.innerHTML = '<option value="todos">Todas las Categorías</option>';
  categories.forEach(cat => {
    const opt = document.createElement('option');
    opt.value = cat;
    opt.textContent = cat;
    categoryFilter.appendChild(opt);
  });
  categoryFilter.value = currentSelection;
}

function updateKPIs() {
  document.getElementById('kpiTotal').textContent = dataAgenda.length;
  
  // Métrica correcta: Urgentes no completadas
  document.getElementById('kpiUrgent').textContent = dataAgenda.filter(
    i => i.prioridad === 'urgente importante' && i.estado !== 'completado'
  ).length;

  document.getElementById('kpiProgress').textContent = dataAgenda.filter(i => i.estado === 'en_proceso').length;
  document.getElementById('kpiCompleted').textContent = dataAgenda.filter(i => i.estado === 'completado').length;
}

function render() {
  updateKPIs();
  
  const q = searchInput.value.toLowerCase();
  const prio = priorityFilter.value;
  const stat = statusFilter.value;
  const cat = categoryFilter.value;

  taskGrid.innerHTML = '';

  const filtered = dataAgenda.filter(item => {
    const matchSearch = item.tarea.toLowerCase().includes(q) || item.categoria.toLowerCase().includes(q);
    const matchPrio = prio === 'todos' || item.prioridad === prio;
    const matchStat = stat === 'todos' || item.estado === stat;
    const matchCat = cat === 'todos' || item.categoria === cat;
    return matchSearch && matchPrio && matchStat && matchCat;
  });

  if (filtered.length === 0) {
    taskGrid.innerHTML = `<p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 3rem 0;">
      No se encontraron tareas con los filtros aplicados.
    </p>`;
    return;
  }

  filtered.forEach((item) => {
    const originalIndex = dataAgenda.indexOf(item);
    const isUrgent = item.prioridad === "urgente importante";

    const card = document.createElement('article');
    card.className = 'card';
    
    card.innerHTML = `
      <div class="card-top">
        <span class="badge badge-cat">${item.categoria}</span>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <span class="badge badge-prio ${isUrgent ? 'urgente' : 'normal'}">
            ${isUrgent ? 'Urgente' : 'No Urgente'}
          </span>
          <button class="btn-delete" data-index="${originalIndex}" title="Eliminar tarea">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
          </button>
        </div>
      </div>

      <h3 class="card-title">${item.tarea}</h3>

      <div class="card-controls">
        <span class="control-label">Estado</span>
        <select class="select-status" data-index="${originalIndex}">
          <option value="pendiente" ${item.estado === 'pendiente' ? 'selected' : ''}>⏳ Pendiente</option>
          <option value="en_proceso" ${item.estado === 'en_proceso' ? 'selected' : ''}>⚡ En Proceso</option>
          <option value="completado" ${item.estado === 'completado' ? 'selected' : ''}>✅ Completado</option>
        </select>

        <span class="control-label" style="margin-top: 0.4rem;">Seguimiento / Log</span>
        <textarea class="tracking-input" data-index="${originalIndex}" placeholder="Añadir observaciones...">${item.seguimiento}</textarea>
      </div>
    `;

    // Listeners de Cambios
    card.querySelector('.select-status').addEventListener('change', (e) => {
      dataAgenda[e.target.dataset.index].estado = e.target.value;
      saveLocal();
      updateKPIs();
    });

    card.querySelector('.tracking-input').addEventListener('input', (e) => {
      dataAgenda[e.target.dataset.index].seguimiento = e.target.value;
      saveLocal();
    });

    // Eliminar Tarea
    card.querySelector('.btn-delete').addEventListener('click', (e) => {
      const idx = e.currentTarget.dataset.index;
      if (confirm('¿Deseas eliminar esta tarea permanentemente?')) {
        dataAgenda.splice(idx, 1);
        saveLocal();
        setupCategories();
        render();
      }
    });

    taskGrid.appendChild(card);
  });
}

// Modal Toggle
openModalBtn.addEventListener('click', () => taskModal.classList.add('active'));
const closeModal = () => taskModal.classList.remove('active');
closeModalBtn.addEventListener('click', closeModal);
cancelModalBtn.addEventListener('click', closeModal);

// Guardar Nueva Actividad
newTaskForm.addEventListener('submit', (e) => {
  e.preventDefault();
  
  const newCat = document.getElementById('newCategory').value.trim();
  const newPrio = document.getElementById('newPriority').value;
  const newDesc = document.getElementById('newTaskDescription').value.trim();

  if (!newCat || !newDesc) return;

  dataAgenda.unshift({
    prioridad: newPrio,
    categoria: newCat,
    tarea: newDesc,
    estado: 'pendiente',
    seguimiento: ''
  });

  saveLocal();
  setupCategories();
  render();
  
  newTaskForm.reset();
  closeModal();
});

// Exportar JSON
exportBtn.addEventListener('click', () => {
  const blob = new Blob([JSON.stringify(dataAgenda, null, 2)], { type: 'application/json' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'data.json';
  a.click();
  URL.revokeObjectURL(url);
});

// Importar JSON
fileInput.addEventListener('change', (e) => {
  const file = e.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = (event) => {
    try {
      const parsed = JSON.parse(event.target.result);
      dataAgenda = parsed.map(item => ({
        ...item,
        estado: item.estado || 'pendiente',
        seguimiento: item.seguimiento || ''
      }));
      saveLocal();
      setupCategories();
      render();
    } catch (err) {
      alert('Error en la estructura del archivo JSON.');
    }
  };
  reader.readAsText(file);
});

// Listeners de Filtro
searchInput.addEventListener('input', render);
priorityFilter.addEventListener('change', render);
statusFilter.addEventListener('change', render);
categoryFilter.addEventListener('change', render);

// Inicializar
initApp();
