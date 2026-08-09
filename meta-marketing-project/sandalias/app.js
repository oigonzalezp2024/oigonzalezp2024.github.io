// --- CLAVES PARA LOCALSTORAGE ---
const STORAGE_KEY_MML = "growth_suite_mml_v1";
const STORAGE_KEY_ADS = "growth_suite_ads_v1";

// --- DATOS POR DEFECTO ---
const defaultMML = {
  matriz_marco_logico: {
    titulo: "Plan de Acción y Optimización de Pauta Publicitaria Digital",
    periodo_ejecucion: "Agosto 2026",
    niveles: [
      {
        nivel: "FIN",
        resumen_narrativo: "Incrementar la rentabilidad global del e-commerce y maximizar el retorno de la inversión publicitaria (ROAS).",
        indicadores_verificables: ["ROAS >= 4.0x", "Incremento del 25% en la facturación mensual por canal digital"],
        medios_de_verificacion: ["Reportes de facturación e-commerce", "Administrador de Anuncios de Meta"],
        supuestos: ["Demanda del mercado de calzado se mantiene estable", "Stock suficiente en inventario"]
      },
      {
        nivel: "PROPOSITO",
        resumen_narrativo: "Consolidar una estrategia de pauta digital altamente eficiente centrada en conversión D2C.",
        indicadores_verificables: ["Costo por Adquisición (CPA) < $15,000 COP", "Tasa de conversión de la web >= 2.5%"],
        medios_de_verificacion: ["Métricas del Píxel de Meta / API", "Google Analytics"],
        supuestos: ["Tiempo de carga web < 2 segundos en móviles", "Pasarela de pago sin caídas"]
      },
      {
        nivel: "COMPONENTES",
        resumen_narrativo: [
          "C1. Campañas publicitarias reestructuradas hacia objetivo de Ventas/Conversión.",
          "C2. Creativos optimizados enfocados en productos de alto rendimiento (Línea Confort).",
          "C3. Embudo de medición e implementación de Píxel y API activo."
        ],
        indicadores_verificables: ["100% del presupuesto asignado a Conversión", "CTR por enlace >= 4.0%", "Medición del 100% de eventos"],
        medios_de_verificacion: ["Meta Ads Manager", "Meta Events Manager"],
        supuestos: ["Meta aprueba los anuncios sin restricciones", "Integración técnica fluida"]
      },
      {
        nivel: "ACTIVIDADES",
        resumen_narrativo: [
          "A1.1. Desactivar campañas de Reconocimiento.",
          "A1.2. Reasignar presupuesto a Conversión.",
          "A2.1. Escalar pauta de calzado Confort (Argollas y Prada).",
          "A3.1. Validar trazabilidad del embudo de ventas."
        ],
        indicadores_verificables: ["Ajuste ejecutado en < 24 horas", "3 a 5 variantes creativas por semana"],
        medios_de_verificacion: ["Logs de Meta Ads Manager", "Reportes semanales"],
        supuestos: ["Disponibilidad técnica inmediata para pruebas web"]
      }
    ]
  }
};

const defaultAds = [
  { nombre: "Sandalia Confort Argollas Negro", estado: "inactive", gastado: 466, impresiones: 120, clics: 6, visitas: 5 },
  { nombre: "Sandalia Confort Triple Tira Prada", estado: "inactive", gastado: 430, impresiones: 113, clics: 5, visitas: 5 },
  { nombre: "Sandalia plana tiras cruzadas", estado: "inactive", gastado: 439, impresiones: 96, clics: 4, visitas: 4 },
  { nombre: "Prueba: Reconocimiento", estado: "archived", gastado: 523, impresiones: 340, clics: 3, visitas: 0 }
];

// --- ESTADO INICIAL (Recuperando de localStorage o asignando defaults) ---
let mmlData = loadFromStorage(STORAGE_KEY_MML, defaultMML);
let adsData = loadFromStorage(STORAGE_KEY_ADS, defaultAds);

// --- FUNCIONES DE PERSISTENCIA ---
function loadFromStorage(key, fallback) {
  try {
    const saved = localStorage.getItem(key);
    return saved ? JSON.parse(saved) : JSON.parse(JSON.stringify(fallback));
  } catch (e) {
    console.error(`Error al cargar ${key} desde localStorage:`, e);
    return JSON.parse(JSON.stringify(fallback));
  }
}

function saveToStorage(key, data) {
  try {
    localStorage.setItem(key, JSON.stringify(data));
  } catch (e) {
    console.error(`Error al guardar ${key} en localStorage:`, e);
  }
}

function resetAllData() {
  if (confirm("¿Estás seguro de restablecer todos los datos a su estado inicial? Se borrarán los cambios locales.")) {
    localStorage.removeItem(STORAGE_KEY_MML);
    localStorage.removeItem(STORAGE_KEY_ADS);
    mmlData = JSON.parse(JSON.stringify(defaultMML));
    adsData = JSON.parse(JSON.stringify(defaultAds));
    renderMML();
    renderAdsTable();
  }
}

// --- INICIALIZACIÓN ---
document.addEventListener("DOMContentLoaded", () => {
  renderMML();
  renderAdsTable();
});

// NAVEGACIÓN ENTRE TABS
function switchTab(tabId) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

  document.getElementById(tabId).classList.add('active');
  event.currentTarget.classList.add('active');
}

// --- RENDERING MML ---
function renderMML() {
  const root = mmlData.matriz_marco_logico;
  document.getElementById("mml-titulo").innerText = root.titulo;
  document.getElementById("mml-periodo").innerText = `Período: ${root.periodo_ejecucion}`;

  const container = document.getElementById("mml-container");
  container.innerHTML = "";

  root.niveles.forEach((item, index) => {
    const card = document.createElement("div");
    card.className = "mml-card";

    const narrativoText = Array.isArray(item.resumen_narrativo) 
      ? item.resumen_narrativo.join("<br>") 
      : item.resumen_narrativo;

    card.innerHTML = `
      <div class="mml-card-header">
        <span class="tag-level">${item.nivel}</span>
        <button class="btn btn-secondary" onclick="openEditModal(${index})">✏️ Editar</button>
      </div>
      <div class="mml-block">
        <h4>Resumen Narrativo</h4>
        <p>${narrativoText}</p>
      </div>
      <div class="mml-block">
        <h4>Indicadores (KPIs)</h4>
        <ul>${item.indicadores_verificables.map(i => `<li>${i}</li>`).join('')}</ul>
      </div>
      <div class="mml-block">
        <h4>Medios de Verificación</h4>
        <ul>${item.medios_de_verificacion.map(m => `<li>${m}</li>`).join('')}</ul>
      </div>
      <div class="mml-block">
        <h4>Supuestos / Riesgos</h4>
        <ul>${item.supuestos.map(s => `<li>${s}</li>`).join('')}</ul>
      </div>
    `;
    container.appendChild(card);
  });
}

// --- MODAL DE EDICIÓN MML ---
function openEditModal(index) {
  const levelData = mmlData.matriz_marco_logico.niveles[index];
  document.getElementById("edit-level-idx").value = index;
  document.getElementById("modal-title").innerText = `Editar Nivel: ${levelData.nivel}`;
  
  document.getElementById("edit-narrativo").value = Array.isArray(levelData.resumen_narrativo) 
    ? levelData.resumen_narrativo.join("\n") 
    : levelData.resumen_narrativo;

  document.getElementById("edit-indicadores").value = levelData.indicadores_verificables.join("\n");
  document.getElementById("edit-medios").value = levelData.medios_de_verificacion.join("\n");
  document.getElementById("edit-supuestos").value = levelData.supuestos.join("\n");

  document.getElementById("modal-mml").style.display = "flex";
}

function closeModal() {
  document.getElementById("modal-mml").style.display = "none";
}

function saveMMLElement() {
  const idx = document.getElementById("edit-level-idx").value;
  const target = mmlData.matriz_marco_logico.niveles[idx];

  const narrativoRaw = document.getElementById("edit-narrativo").value.trim();
  target.resumen_narrativo = narrativoRaw.includes("\n") ? narrativoRaw.split("\n") : narrativoRaw;

  target.indicadores_verificables = document.getElementById("edit-indicadores").value.trim().split("\n");
  target.medios_de_verificacion = document.getElementById("edit-medios").value.trim().split("\n");
  target.supuestos = document.getElementById("edit-supuestos").value.trim().split("\n");

  saveToStorage(STORAGE_KEY_MML, mmlData); // Persistir cambios en localStorage
  closeModal();
  renderMML();
}

// --- IMPORTAR / EXPORTAR JSON ---
function exportJSON() {
  const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(mmlData, null, 2));
  const dlAnchor = document.createElement('a');
  dlAnchor.setAttribute("href", dataStr);
  dlAnchor.setAttribute("download", `Matriz_Marco_Logico_${Date.now()}.json`);
  document.body.appendChild(dlAnchor);
  dlAnchor.click();
  dlAnchor.remove();
}

function importJSONTrigger() { document.getElementById('json-input').click(); }

function loadJSONFile(e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = function(evt) {
    try {
      mmlData = JSON.parse(evt.target.result);
      saveToStorage(STORAGE_KEY_MML, mmlData); // Persistir al cargar un nuevo archivo
      renderMML();
    } catch (err) {
      alert("El archivo JSON seleccionado no es válido.");
    }
  };
  reader.readAsText(file);
}

// --- TABLA DE ANUNCIOS (TAB 2) ---
function renderAdsTable() {
  const tbody = document.getElementById("ads-table-body");
  tbody.innerHTML = "";

  adsData.forEach((row, idx) => {
    const ctr = row.impresiones > 0 ? ((row.clics / row.impresiones) * 100).toFixed(2) : "0.00";
    const tr = document.createElement("tr");

    tr.innerHTML = `
      <td><input type="text" value="${row.nombre}" onchange="updateAd(${idx}, 'nombre', this.value)"></td>
      <td>
        <select onchange="updateAd(${idx}, 'estado', this.value)">
          <option value="active" ${row.estado === 'active' ? 'selected' : ''}>Active</option>
          <option value="inactive" ${row.estado === 'inactive' ? 'selected' : ''}>Inactive</option>
          <option value="archived" ${row.estado === 'archived' ? 'selected' : ''}>Archived</option>
        </select>
      </td>
      <td><input type="number" value="${row.gastado}" onchange="updateAd(${idx}, 'gastado', parseFloat(this.value))"></td>
      <td><input type="number" value="${row.impresiones}" onchange="updateAd(${idx}, 'impresiones', parseInt(this.value))"></td>
      <td><input type="number" value="${row.clics}" onchange="updateAd(${idx}, 'clics', parseInt(this.value))"></td>
      <td><strong>${ctr}%</strong></td>
      <td><input type="number" value="${row.visitas}" onchange="updateAd(${idx}, 'visitas', parseInt(this.value))"></td>
      <td><button class="btn btn-danger" onclick="deleteAd(${idx})">🗑️</button></td>
    `;
    tbody.appendChild(tr);
  });
}

function updateAd(idx, field, value) {
  adsData[idx][field] = value;
  saveToStorage(STORAGE_KEY_ADS, adsData); // Persistir cambios en localStorage
  renderAdsTable();
}

function addRowAds() {
  adsData.push({ nombre: "Nuevo Anuncio", estado: "active", gastado: 0, impresiones: 0, clics: 0, visitas: 0 });
  saveToStorage(STORAGE_KEY_ADS, adsData);
  renderAdsTable();
}

function deleteAd(idx) {
  adsData.splice(idx, 1);
  saveToStorage(STORAGE_KEY_ADS, adsData);
  renderAdsTable();
}

// --- IMPORTAR EXCEL CON SHEETJS ---
function importExcelTrigger() { document.getElementById('excel-input').click(); }

function loadExcelFile(e) {
  const file = e.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = function(evt) {
    const data = new Uint8Array(evt.target.result);
    const workbook = XLSX.read(data, { type: 'array' });
    const firstSheetName = workbook.SheetNames[0];
    const worksheet = workbook.Sheets[firstSheetName];
    const jsonRows = XLSX.utils.sheet_to_json(worksheet);

    adsData = jsonRows.map(r => ({
      nombre: r['Nombre del anuncio'] || r['nombre'] || 'Sin nombre',
      estado: r['Entrega del anuncio'] || 'inactive',
      gastado: parseFloat(r['Importe gastado (COP)']) || 0,
      impresiones: parseInt(r['Impresiones']) || 0,
      clics: parseInt(r['Clics en el enlace']) || parseInt(r['Clics (todos)']) || 0,
      visitas: parseInt(r['Visitas a la página de destino']) || 0
    }));

    saveToStorage(STORAGE_KEY_ADS, adsData); // Persistir al importar Excel
    renderAdsTable();
  };
  reader.readAsArrayBuffer(file);
}

function exportAdsCSV() {
  const ws = XLSX.utils.json_to_sheet(adsData);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, "ReporteAnuncios");
  XLSX.writeFile(wb, `Reporte_Anuncios_${Date.now()}.csv`);
}
