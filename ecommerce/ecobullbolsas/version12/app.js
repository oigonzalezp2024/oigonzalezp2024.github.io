document.addEventListener('DOMContentLoaded', async () => {
  // Guardián seguro para inyectar propiedades en el DOM de forma limpia
  const safeDOM = (id, prop, value) => {
    const el = document.getElementById(id);
    if (el) {
      el[prop] = value;
    } else {
      console.warn(`[DOM SafeGuard] Elemento no encontrado: #${id}`);
    }
  };

  try {
    // 1. Obtener datos del archivo JSON
    const response = await fetch('data.json');
    if (!response.ok) throw new Error(`HTTP status ${response.status}`);

    const data = await response.json();
    const baseWaNumber = data.contacto.whatsappNumber;
    const defaultWaMessage = data.contacto.whatsappMensajeDefault;
    const urlWaDefault = `https://wa.me/${baseWaNumber}?text=${encodeURIComponent(defaultWaMessage)}`;

    // --- LÓGICA DE MODAL FULLSCREEN GLASSMORPHISM ---
    const modal = document.getElementById('modal-fullscreen');
    const modalMedia = document.getElementById('modal-media-container');
    const modalTag = document.getElementById('modal-tag');
    const modalTitulo = document.getElementById('modal-titulo');
    const modalDesc = document.getElementById('modal-descripcion');
    const modalBtnWa = document.getElementById('modal-btn-wa');
    const modalCloseBtn = document.getElementById('modal-close-btn');

    const openModal = (item) => {
      if (!modal) return;

      // Inyección de Contenido Multimedia (Foto o Video)
      if (item.tipoMedia === 'video' && item.mediaUrl) {
        modalMedia.innerHTML = `
          <video controls autoplay style="width:100%; height:100%; object-fit:contain;">
            <source src="${item.mediaUrl}" type="video/mp4">
            Tu navegador no soporta reproducción de video.
          </video>`;
      } else {
        const imgSrc = item.imagenUrl || item.posterUrl || 'assets/img/placeholder.jpg';
        modalMedia.innerHTML = `<img src="${imgSrc}" alt="${item.nombre || item.titulo || 'Ecobull'}">`;
      }

      // Inyección de Textos Informativos
      const categoryTag = (item.categoria || item.tagline || 'ECOBULL').toUpperCase();
      if (modalTag) modalTag.textContent = categoryTag;
      if (modalTitulo) modalTitulo.textContent = item.nombre || item.titulo || 'Detalle del Producto';
      if (modalDesc) {
        modalDesc.textContent = item.descripcion || item.texto || `Consulta sobre nuestra línea de soluciones sostenibles: ${item.nombre || item.titulo || 'Ecobull'}.`;
      }

      // Lógica dinámicamente contextualizada para el botón de WhatsApp
      const isEvent = categoryTag.includes('EVENTO') || categoryTag.includes('PRENSA') || categoryTag.includes('CERTIFICACION');
      const itemName = item.nombre || item.titulo || 'este producto';
      
      let customMessage = `Hola Ecobull, estoy viendo "${itemName}" en su sitio web y me gustaría recibir información detallada.`;
      let btnLabel = 'Cotizar este producto por WhatsApp';

      if (isEvent) {
        customMessage = `Hola Ecobull, vi la publicación sobre "${itemName}" en su sitio web y me gustaría recibir más información al respecto.`;
        btnLabel = 'Más información sobre este evento';
      }

      if (modalBtnWa) {
        modalBtnWa.innerHTML = `<i class="fab fa-whatsapp"></i> ${btnLabel}`;
        modalBtnWa.href = `https://wa.me/${baseWaNumber}?text=${encodeURIComponent(customMessage)}`;
      }

      // Mostrar Modal
      modal.classList.remove('hidden');
    };

    const closeModal = () => {
      if (!modal) return;
      modal.classList.add('hidden');
      if (modalMedia) modalMedia.innerHTML = ''; // Detiene reproducciones activas de video/audio
    };

    if (modalCloseBtn) modalCloseBtn.onclick = closeModal;
    if (modal) {
      modal.onclick = (e) => {
        if (e.target === modal) closeModal();
      };
    }

    // Escuchar tecla ESC para cerrar modal
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
        closeModal();
      }
    });

    // --- FUNCIÓN RENDERIZADORA DE TARJETAS (GRILLAS) ---
    const renderCard = (item) => {
      const isVideo = item.tipoMedia === 'video';
      const itemDataEscaped = encodeURIComponent(JSON.stringify(item));

      return `
        <div class="grid-card ${isVideo ? 'media-card' : ''}" data-item="${itemDataEscaped}">
          <img src="${item.imagenUrl}" alt="${item.nombre}">
          <div class="card-overlay ${isVideo ? 'video-overlay' : ''}">
            ${isVideo ? `<i class="fas fa-play-circle play-icon"></i>` : ''}
            <span>${item.nombre}</span>
          </div>
        </div>
      `;
    };

    // --- INYECCIÓN DE DATOS AL DOM ---

    // 1. Header Hero
    safeDOM('empresa-logo', 'src', data.empresa.logoUrl);
    safeDOM('empresa-tag', 'textContent', data.empresa.tagline);
    safeDOM('empresa-nombre', 'textContent', data.empresa.nombre);
    safeDOM('empresa-slogan', 'textContent', `"${data.empresa.slogan}"`);
    safeDOM('empresa-subtitulo', 'textContent', data.empresa.subtitulo);
    safeDOM('empresa-certificado', 'src', data.empresa.certificadoUrl);
    safeDOM('btn-hero-wa', 'href', urlWaDefault);

    // 2. Navegación
    let navHTML = data.menuNavegacion.map(item => `<li><a href="${item.url}">${item.label}</a></li>`).join('');
    navHTML += `<li><a href="${urlWaDefault}" class="btn-nav-wa" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>`;
    safeDOM('nav-list', 'innerHTML', navHTML);

    // 3. Barra de Valor (Sostenibilidad - 5 Pilares)
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
    safeDOM('btn-soluciones-wa', 'href', urlWaDefault);

    // 6. Bloque Cómo
    safeDOM('como-subtitulo', 'textContent', data.bloqueComo.subtitulo);
    safeDOM('como-titulo', 'textContent', data.bloqueComo.titulo);
    safeDOM('como-desc', 'textContent', data.bloqueComo.descripcion);
    safeDOM('como-poster', 'src', data.bloqueComo.media.posterUrl);
    safeDOM('como-pie', 'textContent', `"${data.bloqueComo.media.pieDeFoto}"`);

    // Evento de clic para el video del Proceso ("Cómo")
    const comoMedia = document.getElementById('como-media-container');
    if (comoMedia) {
      comoMedia.onclick = () => {
        openModal({
          nombre: data.bloqueComo.titulo,
          tipoMedia: data.bloqueComo.media.tipoMedia,
          mediaUrl: data.bloqueComo.media.videoUrl,
          imagenUrl: data.bloqueComo.media.posterUrl,
          categoria: data.bloqueComo.subtitulo,
          descripcion: data.bloqueComo.descripcion
        });
      };
    }

    // 7. Bloque Posicionamiento
    safeDOM('posicionamiento-subtitulo', 'textContent', data.bloquePosicionamiento.subtitulo);
    safeDOM('posicionamiento-titulo', 'textContent', data.bloquePosicionamiento.titulo);
    safeDOM('grid-posicionamiento', 'innerHTML', data.bloquePosicionamiento.itemsGrid.map(renderCard).join(''));

    // 8. Catálogo Banner
    safeDOM('cat-titulo', 'textContent', data.bloqueCatalogo.titulo);
    safeDOM('cat-subtitulo', 'textContent', data.bloqueCatalogo.subtitulo);
    safeDOM('btn-cat-wa', 'href', urlWaDefault);

    const catFeatsHTML = data.bloqueCatalogo.caracteristicas.map(feat => `
      <div class="feat-box"><i class="fas fa-check text-green"></i> ${feat}</div>
    `).join('');
    safeDOM('cat-features', 'innerHTML', catFeatsHTML);

    // 9. Contacto / Footer Profesional
    safeDOM('contacto-email', 'textContent', data.contacto.email);
    safeDOM('contacto-email', 'href', `mailto:${data.contacto.email}`);
    safeDOM('contacto-tel', 'textContent', data.contacto.telefono);
    safeDOM('contacto-tel', 'href', `tel:${data.contacto.telefono.replace(/\s+/g, '')}`);
    safeDOM('contacto-ubicacion', 'textContent', data.contacto.ubicacion);
    safeDOM('contacto-qr', 'src', data.contacto.qrCodeUrl);
    safeDOM('current-year', 'textContent', new Date().getFullYear());

    const socialHTML = data.contacto.redesSociales.map(red => `
      <a href="${red.url}" target="_blank" title="${red.red}"><i class="${red.icono}"></i></a>
    `).join('');
    safeDOM('social-container', 'innerHTML', socialHTML);

    // --- CONTROLADOR DE FORMULARIO DE CONTACTO EXPRESS ---
    const fastForm = document.getElementById('contact-fast-form');
    if (fastForm) {
      fastForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const nombre = document.getElementById('form-nombre')?.value.trim() || 'Cliente';
        const asunto = document.getElementById('form-asunto')?.value || 'Consulta General';
        const mensaje = document.getElementById('form-mensaje')?.value.trim() || '';

        const messageFormatted = `Hola Ecobull, mi nombre/empresa es *${nombre}*.\n*Motivo:* ${asunto}\n*Mensaje:* ${mensaje}`;
        const targetWaUrl = `https://wa.me/${baseWaNumber}?text=${encodeURIComponent(messageFormatted)}`;

        window.open(targetWaUrl, '_blank');
      });
    }

    // --- DELEGACIÓN DE EVENTOS GLOBAL PARA TARJETAS ---
    document.addEventListener('click', (e) => {
      const card = e.target.closest('.grid-card');
      if (card && card.dataset.item) {
        const item = JSON.parse(decodeURIComponent(card.dataset.item));
        openModal(item);
      }
    });

  } catch (error) {
    console.error('[App Init Error]:', error);
  }
});
