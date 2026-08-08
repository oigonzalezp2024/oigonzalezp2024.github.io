document.addEventListener('DOMContentLoaded', async () => {
  try {
    const response = await fetch('data.json');
    const data = await response.json();

    const urlWa = `https://wa.me/${data.contacto.whatsappNumber}?text=${encodeURIComponent(data.contacto.whatsappMensajeDefault)}`;

    // 1. Header Hero
    document.getElementById('empresa-logo').src = data.empresa.logoUrl;
    document.getElementById('empresa-tag').textContent = data.empresa.tagline;
    document.getElementById('empresa-nombre').textContent = data.empresa.nombre;
    document.getElementById('empresa-slogan').textContent = `"${data.empresa.slogan}"`;
    document.getElementById('empresa-subtitulo').textContent = data.empresa.subtitulo;
    document.getElementById('empresa-certificado').src = data.empresa.certificadoUrl;
    document.getElementById('btn-hero-wa').href = urlWa;

    // 2. Menú de Navegación
    const navList = document.getElementById('nav-list');
    let navHTML = data.menuNavegacion.map(item => `<li><a href="${item.url}">${item.label}</a></li>`).join('');
    navHTML += `<li><a href="${urlWa}" class="btn-nav-wa" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>`;
    navList.innerHTML = navHTML;

    // 3. Barra de Valor (5 Pilares)
    const pilaresContainer = document.getElementById('pilares-container');
    pilaresContainer.innerHTML = data.bloqueNecesidad.pilaresValor.map(pilar => `
      <div class="value-item">
        <i class="${pilar.icono}"></i>
        <h4>${pilar.id}. ${pilar.titulo}</h4>
        <p>${pilar.texto}</p>
      </div>
    `).join('');

    // 4. Bloque Necesidad
    document.getElementById('necesidad-subtitulo').textContent = data.bloqueNecesidad.subtitulo;
    document.getElementById('necesidad-titulo').textContent = data.bloqueNecesidad.titulo;
    document.getElementById('necesidad-desc').textContent = data.bloqueNecesidad.descripcion;
    document.getElementById('necesidad-diferencial').textContent = data.bloqueNecesidad.diferencial;
    document.getElementById('stat-valor').textContent = data.bloqueNecesidad.estadisticaClave.valor;
    document.getElementById('stat-etiqueta').textContent = data.bloqueNecesidad.estadisticaClave.etiqueta;

    // 5. Grilla Solución (3x2)
    const gridSoluciones = document.getElementById('grid-soluciones');
    gridSoluciones.innerHTML = data.bloqueSolucion.itemsGrid.map(item => `
      <div class="grid-card ${item.tipo === 'destacado' ? 'highlight-card' : ''}">
        ${item.imagen ? `<img src="${item.imagen}" alt="${item.nombre}"><div class="card-overlay"><span>${item.nombre}</span></div>` : `<span>${item.nombre}</span>`}
      </div>
    `).join('');
    document.getElementById('btn-soluciones-wa').href = urlWa;

    // 6. Bloque Cómo
    document.getElementById('como-subtitulo').textContent = data.bloqueComo.subtitulo;
    document.getElementById('como-desc').textContent = data.bloqueComo.descripcion;
    document.getElementById('como-poster').src = data.bloqueComo.video.poster;
    document.getElementById('como-pie').textContent = `"${data.bloqueComo.video.pieDeFoto}"`;

    // 7. Grilla Posicionamiento (3x2)
    const gridPos = document.getElementById('grid-posicionamiento');
    gridPos.innerHTML = data.bloquePosicionamiento.itemsGrid.map(item => `
      <div class="grid-card ${item.tipo === 'media' ? 'media-card' : 'event-card'}">
        <i class="${item.icono}"></i>
        <span>${item.nombre}</span>
      </div>
    `).join('');

    // 8. Catálogo
    document.getElementById('cat-titulo').textContent = data.bloqueCatalogo.titulo;
    document.getElementById('cat-subtitulo').textContent = data.bloqueCatalogo.subtitulo;
    document.getElementById('btn-cat-wa').href = urlWa;
    const catFeats = document.getElementById('cat-features');
    catFeats.innerHTML = data.bloqueCatalogo.caracteristicas.map(feat => `
      <div class="feat-box"><i class="fas fa-check text-green"></i> ${feat}</div>
    `).join('');

    // 9. Contacto / Footer
    document.getElementById('contacto-email').textContent = data.contacto.email;
    document.getElementById('contacto-tel').textContent = data.contacto.telefono;
    document.getElementById('contacto-ubicacion').textContent = data.contacto.ubicacion;
    const socialContainer = document.getElementById('social-container');
    socialContainer.innerHTML = data.contacto.redesSociales.map(red => `
      <a href="${red.url}" target="_blank"><i class="${red.icono}"></i></a>
    `).join('');

  } catch (error) {
    console.error('Error al cargar data.json:', error);
  }
});
