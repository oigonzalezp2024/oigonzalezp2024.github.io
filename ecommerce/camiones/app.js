document.addEventListener('DOMContentLoaded', () => {
  const WHATSAPP_NUMBER = "573000000000"; // Reemplazar con el número de ventas
  let catalogData = [];

  // Referencias DOM
  const introBox = document.getElementById('introBox');
  const vehiclesGrid = document.getElementById('vehiclesGrid');
  const modalOverlay = document.getElementById('modalOverlay');
  const btnCloseModal = document.getElementById('btnCloseModal');
  const sheetHandle = document.getElementById('sheetHandle');
  
  const modalTitle = document.getElementById('modalTitle');
  const modalPrice = document.getElementById('modalPrice');
  const modalCarousel = document.getElementById('modalCarousel');
  
  const contentPilar1 = document.getElementById('contentPilar1');
  const contentPilar2 = document.getElementById('contentPilar2');
  const contentPilar3 = document.getElementById('contentPilar3');
  const contentPilar4 = document.getElementById('contentPilar4');
  const btnWhatsappLink = document.getElementById('btnWhatsappLink');

  const tabButtons = document.querySelectorAll('.tab-btn-mobile');
  const tabContents = document.querySelectorAll('.tab-content-mobile');

  // Utility para formatear el slug del producto
  const formatTitle = (slug) => {
    return slug.replace(/-/g, ' ').toUpperCase();
  };

  // Inicialización: Fetching de Datos
  const initApp = async () => {
    try {
      const response = await fetch('data.json');
      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
      
      const rawData = await response.json();
      
      if (rawData.introduccion_cliente) {
        introBox.textContent = rawData.introduccion_cliente;
      }

      catalogData = rawData.modelos || [];
      renderCatalog(catalogData);
    } catch (error) {
      console.error("Error al cargar el catálogo de vehículos:", error);
      introBox.textContent = "Error al obtener la información comercial.";
      vehiclesGrid.innerHTML = `<p style="color: var(--jmc-red); font-weight: 700;">No se pudo cargar el catálogo de vehículos.</p>`;
    }
  };

  // Renderizar Tarjetas principales
  const renderCatalog = (modelos) => {
    vehiclesGrid.innerHTML = modelos.map(m => {
      const titleFormatted = formatTitle(m.producto);
      
      return `
        <article class="vehicle-card">
          <div class="card-carousel">
            ${m.fotos.map((imgUrl, i) => `
              <div class="carousel-slide">
                <img src="${imgUrl}" alt="${titleFormatted}" loading="lazy">
                ${i === 0 && m.fotos.length > 1 ? '<span class="swipe-hint">Desliza &rarr;</span>' : ''}
              </div>
            `).join('')}
          </div>

          <div class="card-body">
            <div class="card-header-info">
              <h2 class="vehicle-title">${titleFormatted}</h2>
              <span class="price-tag">${m.precio_sugerido}</span>
            </div>

            <p class="snippet-text">${m.pilar_1}</p>

            <button 
              class="btn-action btn-open-modal"
              data-producto="${m.producto}"
            >
              Ver Detalles y Cotizar
            </button>
          </div>
        </article>
      `;
    }).join('');

    bindCatalogEvents();
  };

  // Asignar Eventos a las tarjetas
  const bindCatalogEvents = () => {
    const actionButtons = document.querySelectorAll('.btn-open-modal');
    actionButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const idProducto = btn.getAttribute('data-producto');
        const modelo = catalogData.find(item => item.producto === idProducto);
        if (modelo) openModal(modelo);
      });
    });
  };

  // Abrir Modal e inyectar datos
  const openModal = (modelo) => {
    const titleFormatted = formatTitle(modelo.producto);

    modalTitle.textContent = titleFormatted;
    modalPrice.textContent = modelo.precio_sugerido;

    // Renderizar Galería Compacta
    if (modelo.fotos && modelo.fotos.length > 0) {
      modalCarousel.innerHTML = modelo.fotos.map((imgUrl, i) => `
        <div class="carousel-slide">
          <img src="${imgUrl}" alt="${titleFormatted}" loading="lazy">
          ${i === 0 && modelo.fotos.length > 1 ? '<span class="swipe-hint">Desliza &rarr;</span>' : ''}
        </div>
      `).join('');
      modalCarousel.scrollLeft = 0;
    } else {
      modalCarousel.innerHTML = '';
    }

    contentPilar1.textContent = modelo.pilar_1;
    contentPilar2.textContent = modelo.pilar_2;
    contentPilar3.textContent = modelo.pilar_3;
    contentPilar4.textContent = modelo.pilar_4;

    const waMessage = encodeURIComponent(
      `Hola, me interesa obtener información y cotización del camión JMC ${titleFormatted} (${modelo.precio_sugerido}). Quisiera recibir asesoría sobre opciones de financiación y entrega.`
    );
    btnWhatsappLink.href = `https://wa.me/${WHATSAPP_NUMBER}?text=${waMessage}`;

    resetTabs();
    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  // Cerrar Modal
  const closeModal = () => {
    modalOverlay.classList.remove('active');
    document.body.style.overflow = '';
  };

  btnCloseModal.addEventListener('click', closeModal);
  sheetHandle.addEventListener('click', closeModal);
  modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) closeModal();
  });

  // Manejo de Pestañas
  tabButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetTab = btn.getAttribute('data-tab');
      tabButtons.forEach(b => b.classList.remove('active'));
      tabContents.forEach(c => c.classList.remove('active'));

      btn.classList.add('active');
      document.getElementById(targetTab).classList.add('active');
    });
  });

  const resetTabs = () => {
    tabButtons.forEach(b => b.classList.remove('active'));
    tabContents.forEach(c => c.classList.remove('active'));
    if (tabButtons.length > 0 && tabContents.length > 0) {
      tabButtons[0].classList.add('active');
      tabContents[0].classList.add('active');
    }
  };

  initApp();
});
