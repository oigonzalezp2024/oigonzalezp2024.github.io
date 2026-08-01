async function loadHome() {
    const grid = document.getElementById('news-grid');
    if (!grid) return; // Protección anti-error
    
    try {
        const res = await fetch('colnews/data/db_list.json');
        if (!res.ok) throw new Error('No se pudo cargar la lista');
        const data = await res.json();
        
        grid.innerHTML = `
            <a href="ecommerce/directorio-de-aliados/index.html" class="news-card">
                <span class="category">Desarrollo Web</span>
                <h2>Mis Clientes</h2>
                <p>Conoce mis aliados, empresarios serios que aman su tierra.</p>
            </a>`;
        
        grid.innerHTML += data.map(item => `
            <a href="colnews/article.html?id=${item.id}" class="news-card">
                <span class="category">${item.category}</span>
                <h2>${item.title}</h2>
                <p>${item.excerpt}</p>
            </a>
        `).join('');
        
    } catch (e) { 
        grid.innerHTML = `<p class="p-4 text-red-500">Error al cargar noticias: ${e.message}</p>`;
    }
}
document.addEventListener('DOMContentLoaded', loadHome);
