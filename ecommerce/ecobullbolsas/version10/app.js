document.addEventListener('DOMContentLoaded', async () => {
  const safeDOM = (id, prop, value) => {
    const el = document.getElementById(id);
    if (el) {
      el[prop] = value;
    } else {
      console.warn(`[DOM SafeGuard] Elemento no encontrado: #${id}`);
    }
  };

  try {
    const response = await fetch('data.json');
    if (!response.ok) throw new Error(`HTTP status ${response.status}`);

    const data = await response.json();
    const urlWa = `https://wa.me/${data.contacto.whatsappNumber}?text=${encodeURIComponent(data.contacto.whatsappMensajeDefault)}`;

    const renderCard = (item) => {
      const isVideo = item.tipoMedia === 'video';
      const action = item.mediaUrl ? `onclick="window.open('${item.mediaUrl}', '_blank')"` : '';

      return `
        <div class="grid-card ${isVideo ? 'media-card' : ''}" ${action}>
          <img src="${item.imagenUrl}" alt="${item.nombre}">
          <div class="card-overlay ${isVideo ? 'video-overlay' : ''}">
            ${isVideo ? `<i class="fas fa-play-circle play-icon"></i>` : ''}
            <span>${item.nombre}</span>
          </div>
        </div>
      `;
    };

    // 1. Header Hero
    safeDOM('empresa-logo', 'src', data.empresa.logoUrl);
    safeDOM('empresa-tag', 'textContent', data.empresa.tagline);
    safeDOM('empresa-nombre', 'textContent', data.empresa.nombre);
    safeDOM('empresa-slogan', 'textContent', `"${data.empresa.slogan}"`);
    safeDOM('empresa-subtitulo', 'textContent', data.empresa.subtitulo);
    safeDOM('empresa-certificado', 'src', data.empresa.certificadoUrl);
    safeDOM('btn-hero-wa', 'href', urlWa);

    // 2. Navegación
    let navHTML = data.menuNavegacion.map(item => `<li><a href="${item.url}">${item.label}</a></li>`).join('');
    navHTML += `<li><a href="${urlWa}" class="btn-nav-wa" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>`;
    safeDOM('nav-list', 'innerHTML', navHTML);

    // 3. Barra de Valor (5 Pilares)
    const pilaresHTML = data.bloqueNecesidad.pilaresValor.map(pilar => `
      <div class="value-item">
        <i class="${pilar.icono}"></i>
        <h4>${pilar.id}. ${pilar.titulo}</h4>
        <p>${pilar.texto}</p>
      </div>
    `).join('');
    safeDOM('sostenibilidad', 'innerHTML', pilaresHTML);

    // 4. Bloque Necesidad
    safeDOM('necesidad-subtitulo', 'textContent', data.bloqueNecesidad.subtitulo);
    safeDOM('necesidad-titulo', 'textContent', data.bloqueNecesidad.titulo);

    const descElement = document.getElementById('necesidad-desc');
    if (descElement) {
      const descData = data.bloqueNecesidad.descripcion;
      descElement.innerHTML = Array.isArray(descData)
        ? descData.map(p => `<p class="body-paragraph">${p}</p>`).join('')
        : `<p class="body-paragraph">${descData}</p>`;
    }

    safeDOM('necesidad-diferencial-lead', 'textContent', data.bloqueNecesidad.diferencialLead);

    const diffList = document.getElementById('necesidad-diferenciales-list');
    if (diffList && data.bloqueNecesidad.diferenciales) {
      diffList.innerHTML = data.bloqueNecesidad.diferenciales.map(item => `
        <div class="diff-item">
          <i class="${item.icono}"></i>
          <div class="diff-item-content">
            <h5>${item.titulo}</h5>
            <p>${item.texto}</p>
          </div>
        </div>
      `).join('');
    }

    safeDOM('stat-valor', 'textContent', data.bloqueNecesidad.estadisticaClave.valor);
    safeDOM('stat-etiqueta', 'textContent', data.bloqueNecesidad.estadisticaClave.etiqueta);

    // 5. Bloque Solución
    safeDOM('solucion-subtitulo', 'textContent', data.bloqueSolucion.subtitulo);
    safeDOM('solucion-titulo', 'textContent', data.bloqueSolucion.titulo);
    safeDOM('grid-soluciones', 'innerHTML', data.bloqueSolucion.itemsGrid.map(renderCard).join(''));
    safeDOM('btn-soluciones-wa', 'href', urlWa);

    // 6. Bloque Cómo
    safeDOM('como-subtitulo', 'textContent', data.bloqueComo.subtitulo);
    safeDOM('como-titulo', 'textContent', data.bloqueComo.titulo);
    safeDOM('como-desc', 'textContent', data.bloqueComo.descripcion);
    safeDOM('como-poster', 'src', data.bloqueComo.media.posterUrl);
    safeDOM('como-pie', 'textContent', `"${data.bloqueComo.media.pieDeFoto}"`);

    const comoMedia = document.getElementById('como-media-container');
    if (comoMedia && data.bloqueComo.media.videoUrl) {
      comoMedia.onclick = () => window.open(data.bloqueComo.media.videoUrl, '_blank');
    }

    // 7. Bloque Posicionamiento
    safeDOM('posicionamiento-subtitulo', 'textContent', data.bloquePosicionamiento.subtitulo);
    safeDOM('posicionamiento-titulo', 'textContent', data.bloquePosicionamiento.titulo);
    safeDOM('grid-posicionamiento', 'innerHTML', data.bloquePosicionamiento.itemsGrid.map(renderCard).join(''));

    // 8. Catálogo
    safeDOM('cat-titulo', 'textContent', data.bloqueCatalogo.titulo);
    safeDOM('cat-subtitulo', 'textContent', data.bloqueCatalogo.subtitulo);
    safeDOM('btn-cat-wa', 'href', urlWa);

    const catFeatsHTML = data.bloqueCatalogo.caracteristicas.map(feat => `
      <div class="feat-box"><i class="fas fa-check text-green"></i> ${feat}</div>
    `).join('');
    safeDOM('cat-features', 'innerHTML', catFeatsHTML);

    // 9. Contacto
    safeDOM('contacto-email', 'textContent', data.contacto.email);
    safeDOM('contacto-tel', 'textContent', data.contacto.telefono);
    safeDOM('contacto-ubicacion', 'textContent', data.contacto.ubicacion);
    safeDOM('contacto-qr', 'src', data.contacto.qrCodeUrl);

    const socialHTML = data.contacto.redesSociales.map(red => `
      <a href="${red.url}" target="_blank" title="${red.red}"><i class="${red.icono}"></i></a>
    `).join('');
    safeDOM('social-container', 'innerHTML', socialHTML);

  } catch (error) {
    console.error('[App Init Error]:', error);
  }
});
