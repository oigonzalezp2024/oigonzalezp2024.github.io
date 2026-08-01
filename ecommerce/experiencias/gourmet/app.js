let appData = null;
let currentPlato = null;

document.addEventListener('DOMContentLoaded', () => {
  fetch('data.json')
    .then(res => res.json())
    .then(data => {
      appData = data;
      renderApp();
    })
    .catch(err => console.error('Error cargando data.json:', err));
});

function renderApp() {
  const matrix = document.getElementById('desktop-matrix');
  matrix.innerHTML = '';

  // 1. Renderizar Platos con fotos
  appData.platillos.forEach(plato => {
    matrix.innerHTML += `
      <div class="photo-card col-3" onclick="openRecipe('${plato.id}')">
        <img src="${plato.imagen}" alt="${plato.titulo}">
        <div class="card-overlay">
          <span class="card-tag">Receta Gourmet</span>
          <h3>${plato.titulo}</h3>
          <p>⏱️ ${plato.tiempoEstimado} • ${plato.dificultad}</p>
        </div>
      </div>
    `;
  });

  // 2. Renderizar Utensilios con fotos
  appData.utensilios.forEach(u => {
    matrix.innerHTML += `
      <div class="photo-card col-3">
        <img src="${u.imagen}" alt="${u.nombre}">
        <div class="card-overlay">
          <span class="card-tag">Utensilio Premium</span>
          <h3>${u.nombre}</h3>
          <p>$${u.precio} USD</p>
        </div>
      </div>
    `;
  });

  // 3. Renderizar Servicios con fotos
  appData.servicios.forEach(s => {
    matrix.innerHTML += `
      <div class="photo-card col-2" onclick="openService('${s.id}')">
        <img src="${s.imagen}" alt="${s.titulo}">
        <div class="card-overlay">
          <span class="card-tag">Servicio Exclusivo</span>
          <h3>${s.titulo}</h3>
          <p>${s.descripcion}</p>
        </div>
      </div>
    `;
  });
}

function openRecipe(platoId) {
  currentPlato = appData.platillos.find(p => p.id === platoId);
  const container = document.getElementById('step-receta');

  const utensiliosLista = currentPlato.utensiliosIds.map(id => {
    const u = appData.utensilios.find(item => item.id === id);
    return `
      <div class="utensil-row">
        <img src="${u.imagen}" alt="${u.nombre}">
        <div style="flex-grow:1;">
          <strong style="font-size:0.9rem;">${u.nombre}</strong>
          <p style="font-size:0.75rem; color:var(--text-muted);">$${u.precio} USD</p>
        </div>
      </div>
    `;
  }).join('');

  container.innerHTML = `
    <img src="${currentPlato.imagen}" class="modal-header-img" alt="${currentPlato.titulo}">
    <h2 style="font-family:'Playfair Display'; color:#fff;">${currentPlato.titulo}</h2>
    <p style="color:var(--accent-gold); font-size:0.85rem; margin:5px 0 15px 0;">
      ⏱️ ${currentPlato.tiempoEstimado} | 📊 Dificultad: ${currentPlato.dificultad}
    </p>

    <h4 style="font-family:'Playfair Display'; color:#fff;">Ingredientes:</h4>
    <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:15px;">${currentPlato.ingredientes.join(', ')}.</p>

    <h4 style="font-family:'Playfair Display'; color:#fff;">Preparación:</h4>
    <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:15px;">${currentPlato.preparacion}</p>

    <h4 style="font-family:'Playfair Display'; color:#fff; margin-top:10px;">Utensilios Recomendados:</h4>
    ${utensiliosLista}

    <div class="action-buttons">
      <button class="btn-action btn-buy" onclick="openUtensilCheckout()">🛒 Comprar Utensilios</button>
      <button class="btn-action btn-book" onclick="openService('servicio-asistencia')">👨‍🍳 Agendar Asistencia</button>
    </div>
  `;

  openStep('step-receta');
}

function openUtensilCheckout() {
  const container = document.getElementById('step-comprar-utensilios');
  const kit = currentPlato.ofertaKit;

  let itemsHTML = currentPlato.utensiliosIds.map(id => {
    const u = appData.utensilios.find(item => item.id === id);
    return `
      <div class="utensil-row">
        <input type="checkbox" class="utensil-calc" value="${u.precio}" onchange="calculateTotal()" checked>
        <img src="${u.imagen}" alt="${u.nombre}">
        <div style="flex-grow:1;">
          <strong style="font-size:0.85rem;">${u.nombre}</strong>
        </div>
        <span>$${u.precio} USD</span>
      </div>
    `;
  }).join('');

  container.innerHTML = `
    <h2 style="font-family:'Playfair Display'; color:#fff;">Adquirir Utensilios</h2>
    <div style="background:#232a27; border:1px dashed var(--accent-gold); padding:15px; border-radius:8px; margin:15px 0; text-align:center;">
      <p style="font-weight:600; font-size:0.9rem; color:var(--accent-gold);">✨ ${kit.nombre}</p>
      <p style="font-size:0.8rem; color:var(--text-muted); margin-top:4px;">
        Llévalos todos por: <del>$${kit.precioRegular} USD</del> &rarr; <strong style="color:#fff; font-size:1rem;">$${kit.precioDescuento} USD</strong>
      </p>
    </div>

    ${itemsHTML}

    <div style="font-size:1.1rem; font-weight:bold; text-align:right; margin-top:15px; color:var(--accent-gold);">
      Total: <span id="total-price">$${kit.precioDescuento.toFixed(2)} USD</span>
    </div>

    <form onsubmit="processPayment(event)">
      <div class="form-group"><label>Nombre Completo:</label><input type="text" required placeholder="Tu nombre"></div>
      <div class="form-group"><label>Dirección de Entrega:</label><input type="text" required placeholder="Dirección completa"></div>
      <button type="submit" class="btn-action btn-buy" style="width:100%; margin-top:15px;">Pagar con QR</button>
    </form>
  `;

  openStep('step-comprar-utensilios');
  calculateTotal();
}

function openService(serviceId) {
  const servicio = appData.servicios.find(s => s.id === serviceId) || appData.servicios[0];
  const container = document.getElementById('step-asistencia');

  container.innerHTML = `
    <img src="${servicio.imagen}" class="modal-header-img" alt="${servicio.titulo}">
    <h2 style="font-family:'Playfair Display'; color:#fff;">${servicio.titulo}</h2>
    <p style="font-size:0.85rem; color:var(--text-muted); margin-top:5px;">${servicio.descripcion}</p>
    
    <form onsubmit="processBooking(event)">
      <div class="form-group"><label>Nombre Completo:</label><input type="text" required placeholder="Tu nombre"></div>
      <div class="form-group"><label>Correo Electrónico:</label><input type="email" required placeholder="correo@ejemplo.com"></div>
      <div class="form-group"><label>Fecha Deseada:</label><input type="date" required></div>
      <button type="submit" class="btn-action btn-buy" style="width:100%; margin-top:15px;">Confirmar Agendamiento</button>
    </form>
  `;

  openStep('step-asistencia');
}

function calculateTotal() {
  const checkboxes = document.querySelectorAll('.utensil-calc');
  let total = 0;
  let count = 0;

  checkboxes.forEach(cb => {
    if (cb.checked) {
      total += parseFloat(cb.value);
      count++;
    }
  });

  if (currentPlato && count === currentPlato.utensiliosIds.length) {
    total = currentPlato.ofertaKit.precioDescuento;
  }

  const totalEl = document.getElementById('total-price');
  if (totalEl) totalEl.innerText = `$${total.toFixed(2)} USD`;
}

function openStep(stepId) {
  document.getElementById('modal-overlay').style.display = 'flex';
  document.querySelectorAll('.modal-step').forEach(s => s.classList.remove('active'));
  document.getElementById(stepId).classList.add('active');
}

function closeModal() {
  document.getElementById('modal-overlay').style.display = 'none';
}

function processPayment(event) {
  event.preventDefault();
  openStep('step-qr');
}

function processBooking(event) {
  event.preventDefault();
  openStep('step-confirmacion');
}
