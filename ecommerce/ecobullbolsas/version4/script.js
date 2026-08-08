// app.js - Carga dinámica desacoplada de la vista
document.addEventListener('DOMContentLoaded', async () => {
  try {
    const response = await fetch('data.json');
    const data = await response.json();

    // Inyectar datos dinámicamente en los elementos del DOM
    document.querySelector('.brand-highlight').textContent = data.empresa.nombre;
    document.querySelector('.quote').textContent = `"${data.empresa.slogan}"`;
    document.querySelector('.subquote').textContent = data.empresa.subtitulo;

    // Renderizar grilla de Soluciones (Sirve para cualquier layout)
    const gridSoluciones = document.querySelector('.sub-grid-3x2');
    if (gridSoluciones) {
      gridSoluciones.innerHTML = data.bloqueSolucion.itemsGrid.map(item => `
        <div class="grid-card">
          <img src="${item.imagen}" alt="${item.nombre}">
          <div class="card-overlay"><span>${item.nombre}</span></div>
        </div>
      `).join('');
    }

  } catch (error) {
    console.error('Error cargando el archivo JSON:', error);
  }
});
