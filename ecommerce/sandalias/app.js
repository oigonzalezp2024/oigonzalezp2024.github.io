/**
 * app.js - Script para cargar dinámicamente el catálogo de Rojas Distribuciones
 */

document.addEventListener('DOMContentLoaded', () => {
    // Aquí iría la ruta a tu archivo JSON
    const DATA_URL = './data.json'; 

    fetch(DATA_URL)
        .then(response => {
            if (!response.ok) throw new Error('Error al cargar el archivo JSON');
            return response.json();
        })
        .then(data => {
            renderizarNavegacion(data.sitio.navegacion);
            renderizarCatalogo(data.sitio.productos);
        })
        .catch(error => console.error('Error:', error));
});

/**
 * Genera y rellena las barras de navegación (trust-bar)
 */
function renderizarNavegacion(navItems) {
    const navHTML = navItems.map(item => `
        <a class="trust-item" href="${item.url}">
            <i class="fas fa-shield-alt"></i> ${item.nombre}
        </a>
    `).join('');

    // Inyecta en todas las barras de confianza encontradas
    const trustBars = document.querySelectorAll('.trust-grid');
    trustBars.forEach((bar, index) => {
        // Solo inyectamos en las barras que contienen enlaces (index 0 y 1)
        if (index < 2) {
            bar.innerHTML = navHTML;
        }
    });
}

/**
 * Genera y rellena el catálogo de productos
 */
function renderizarCatalogo(productos) {
    const catalogGrid = document.getElementById('catalog-grid');
    
    const productosHTML = productos.map(prod => `
        <article class="product-card" style="border:#003366 1.5px solid;">
            <div class="card-header">
                <div>
                    <h2 class="product-name">${prod.nombre}</h2>
                    <span class="model-text">${prod.caracteristicas}</span>
                </div>
                <span class="product-volt">${prod.voltaje}</span>
            </div>
            <div class="image-container" style="height: 250px; padding: 20px; background: white; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                <a href="http://localhost/ecommerce/img/${prod.imagen.replace('../img/', '').replace('.avif', '.jpeg')}" style="text-decoration: none; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                    <img src="${prod.imagen}" alt="${prod.nombre}" style="max-height: 145%; max-width: 145%; object-fit: contain;">
                </a>
            </div>
            <div class="specs">
                <p class="specs-label">Especificaciones:</p>
                <p>${prod.especificaciones.map(spec => `✓ ${spec}`).join('<br>')}</p>
            </div>
            <div class="card-footer">
                <div class="action-row">
                    <div class="stock-info">Stock: ${prod.stock} unds.</div>
                    <a target="_blank" 
                       href="https://api.whatsapp.com/send/?phone=573212962876&text=Hola, solicito información de: ${prod.nombre.replace(' ', '+')} http://localhost/ecommerce/img/${prod.imagen.replace('../img/', '').replace('.avif', '.jpeg')}" 
                       class="btn-whatsapp-modern">Cotizar</a>
                </div>
            </div>
        </article>
    `).join('');

    catalogGrid.innerHTML = productosHTML;
}
