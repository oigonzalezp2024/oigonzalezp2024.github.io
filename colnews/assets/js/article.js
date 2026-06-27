function getYouTubeEmbedUrl(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = url.match(regExp);
    if (match && match[2].length === 11) {
        return `https://www.youtube.com/embed/${match[2]}`;
    }
    return url;
}

async function loadArticle() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    const titleEl = document.getElementById('art-title');
    const container = document.getElementById('art-container');
    const metaEl = document.getElementById('art-meta');

    if (!id || !titleEl) return;

    try {
        const [listRes, detailsRes] = await Promise.all([
            fetch('data/db_list.json?id=3'),
            fetch('data/db_details.json?id=3')
            //fetch('data/db_details.php?id=3')
        ]);

        const list = await listRes.json();
        const details = await detailsRes.json();

        const item = list.find(x => x.id == id);
        const detail = details.find(x => x.id == id);

        if (item && detail) {
            // Actualizar título de la página y encabezado
            document.title = `${item.title} | NEWS_CORE`;
            titleEl.innerText = item.title;

            // Renderizado de bloques modulares (el núcleo de la flexibilidad)
            // Si 'detail.content' es un array, lo procesamos bloque por bloque
            if (Array.isArray(detail.content)) {
                container.innerHTML = detail.content.map(block => {
                    switch (block.type) {
                        case 'paragraph':
                            return `<p class="mb-4 text-slate-300 leading-relaxed">${block.data}</p><br>`;
                        case 'image':
                            return `<figure class="my-6">
                                        <img src="${block.url}" width=600px class="rounded-2xl w-full" alt="${block.caption || ''}">
                                        ${block.caption ? `<figcaption class="text-xs text-slate-500 mt-2 italic">${block.caption}</figcaption>` : ''}
                                    </figure><br>`;
                        case 'video': {
                            const isYouTube = block.url.includes('youtube.com') || block.url.includes('youtu.be');
                            const orientationClass = block.orientation === 'vertical' ? 'max-w-[400px]' : 'w-full';

                            if (isYouTube) {
                                return `
                                    <div class="video-wrapper ${orientationClass}">
                                        <iframe 
                                            src="${getYouTubeEmbedUrl(block.url)}?autoplay=0&rel=0" 
                                            frameborder="0" 
                                            allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen>
                                        </iframe>
                                    </div>`;
                            } else {
                                // Video Nativo
                                return `
                                    <div class="video-wrapper ${orientationClass}">
                                        <video 
                                            controls 
                                            preload="metadata" 
                                            poster="${block.poster || ''}"
                                            class="w-full h-full">
                                            <source src="${block.url}" type="video/mp4">
                                            Tu navegador no soporta el formato de video.
                                        </video>
                                    </div>`;
                            }
                        }
                        case 'link':
                            return `<div class="my-6 p-4 border-l-4 border-blue-500 bg-slate-900">
                                        <a target="_black" href="${block.url}" class="text-blue-400 hover:underline font-bold">${block.text}</a>
                                    </div><br><br><br>`;
                        default: return '';
                    }
                }).join('');
            } else {
                // Fallback si el JSON aún usa el formato antiguo de texto plano
                container.innerHTML = `<p class="text-slate-300 leading-relaxed">${detail.body || ''}</p>`;
            }

            // Metadatos finales
            metaEl.innerText = `Por ${detail.author} | Publicado el ${detail.date}`;

        } else {
            throw new Error('Contenido no encontrado');
        }
    } catch (e) {
        document.body.innerHTML = `<h1 class='p-10 text-center text-red-500'>${e.message}</h1>`;
    }
}

document.addEventListener('DOMContentLoaded', loadArticle);
