document.addEventListener('DOMContentLoaded', () => {
  // Datos extraídos directamente de db_details.json
  const dbDetails = [
    {
      "id": 1,
      "author": "Oscar Gonzalez",
      "date": "2026-06-27",
      "content": [
        { "type": "header", "data": "Soluciones Digitales" },
        { "type": "paragraph", "data": "Desarrollo de ecosistemas web orientados a la optimización de procesos operativos, gestión de inventarios y digitalización de catálogos comerciales." },

        { "type": "image", "url": "../../colnews/assets/img/ecobullbolsas-dispositivos.avif", "caption": "Ecobullbolsas: Fábrica de bolsas ecológicas para tiendas de ropa, calzado, accesorios, perfumería, droguerias y eventos." },
        { "type": "paragraph", "data": "Fábrica de bolsas de lujo para boutiques. Crea una experiencia memorable en la experiencia de compra y crea productos amigables con el medio ambiente. Sus bolsas son biodegradables y se transforman en abono. Ofrece bolsas para su negocio avaladas por Corponor." },
        { "type": "link", "url": "https://ecobullbolsas.com", "text": "Visitar plataforma: ecobullbolsas.com" },
        
        { "type": "image", "url": "../../colnews/assets/img/rojas-distribuciones-dispositivos.avif", "caption": "Rojas Distribuciones: Fábrica de máquinas y aprovisionamiento industrial." },
        { "type": "paragraph", "data": "Desarrollo de una plataforma web para Rojas Distribuciones optimizada para alto rendimiento (Lighthouse 95%+). La solución integra catálogos inteligentes con generación dinámica de PDF, garantizando una sincronización real entre el stock y la experiencia del usuario." },
        { "type": "link", "url": "https://rojasdistribuciones.com", "text": "Visitar plataforma: rojasdistribuciones.com" },
        
        { "type": "image", "url": "../../colnews/assets/img/sandalias.avif", "caption": "Fábrica de Sandalias: Venta al por Mayor y Detal" },
        { "type": "paragraph", "data": "La fábrica tiene su sede principal en Cúcuta. Ventas al mayor y Detal. Envíos gratis a todo Colombia. Pago contra entrega. Arma tu pedido como un mensaje de WhatsApp personalizado. Al mayor, pagas el flete." },
        { "type": "link", "url": "https://oigonzalezp2024.github.io/ecommerce/sandalias/", "text": "Visita la demo de Ecommerce de venta de sandalias." }

      ]
    },
    {
      "id": 2,
      "author": "Oscar Gonzalez",
      "date": "2026-06-27",
      "content": [
        { "type": "header", "data": "Demostración ETL Python" },
        { "type": "paragraph", "data": "Integración y Automatización de Servicios Web (SIPSA DANE): Diseño y desarrollo de un script modular en Python para la extracción, transformación y consumo de datos desde servicios web (SOAP/WSDL) de la plataforma SIPSA DANE." }
      ]
    }
  ];

  buildMall(dbDetails);
});

function buildMall(data) {
  const directoryContainer = document.getElementById('directoryFilters');
  const plazaContainer = document.getElementById('mallPlaza');

  plazaContainer.innerHTML = '';
  directoryContainer.innerHTML = '';

  const clientStores = [];
  let localCounter = 101;

  // Filtrar el portafolio comercial (ID: 1)
  const portafolioSection = data.find(item => item.id === 1);

  if (portafolioSection) {
    let currentStore = null;

    portafolioSection.content.forEach(item => {
      if (item.type === 'image') {
        if (currentStore) clientStores.push(currentStore);

        // Nombre simplificado para la barra de navegación del centro comercial
        let shortName = item.caption.split(':')[0].replace('Demo de Landing Page para venta de ', '');

        currentStore = {
          localCode: `L-${localCounter++}`,
          title: item.caption,
          shortName: shortName,
          imgUrl: item.url,
          description: '',
          linkUrl: '',
          linkText: '',
          category: 'E-Commerce & Solución Web'
        };
      } else if (item.type === 'paragraph' && currentStore && !currentStore.description) {
        currentStore.description = item.data;
      } else if (item.type === 'link' && currentStore) {
        currentStore.linkUrl = item.url;
        currentStore.linkText = item.text;
      }
    });

    if (currentStore) clientStores.push(currentStore);
  }

  // 1. Botón "Ver Todos"
  const btnAll = document.createElement('button');
  btnAll.className = 'directory-btn active';
  btnAll.innerText = 'Ver Todos los Locales';
  btnAll.onclick = (e) => filterMallStores('all', e.target);
  directoryContainer.appendChild(btnAll);

  // 2. Construir cada cliente como un LOCAL COMERCIAL individual
  clientStores.forEach(client => {
    // Botón para el directorio de navegación
    const btnStore = document.createElement('button');
    btnStore.className = 'directory-btn';
    btnStore.innerText = `${client.localCode} • ${client.shortName}`;
    btnStore.onclick = (e) => filterMallStores(client.localCode, e.target);
    directoryContainer.appendChild(btnStore);

    // Fachada e Interior
    const storeArticle = document.createElement('article');
    storeArticle.className = 'store-building';
    storeArticle.setAttribute('data-local', client.localCode);

    storeArticle.innerHTML = `
      <div class="store-façade">
        <span class="store-number">${client.localCode} • ${client.category}</span>
        <h2 class="store-brand">${client.title}</h2>
      </div>

      <div class="store-interior">
        <div class="store-display-window">
          <img src="${client.imgUrl}" alt="${client.title}" loading="lazy">
        </div>

        <div class="store-info">
          <div>
            <h3>Propuesta del Local</h3>
            <p>${client.description}</p>
          </div>

          <div class="store-actions">
            <a href="${client.linkUrl}" target="_blank" rel="noopener noreferrer" class="btn-enter-store">
              ${client.linkText || 'Entrar al Local'} ➔
            </a>
          </div>
        </div>
      </div>
    `;

    plazaContainer.appendChild(storeArticle);
  });
}

function filterMallStores(localCode, targetBtn) {
  const allStores = document.querySelectorAll('.store-building');
  const allButtons = document.querySelectorAll('.directory-btn');

  allButtons.forEach(btn => btn.classList.remove('active'));
  targetBtn.classList.add('active');

  allStores.forEach(store => {
    if (localCode === 'all' || store.getAttribute('data-local') === localCode) {
      store.style.display = 'block';
    } else {
      store.style.display = 'none';
    }
  });
}
