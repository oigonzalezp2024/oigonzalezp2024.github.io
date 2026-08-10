document.addEventListener("DOMContentLoaded", () => {
    // Carga de la data desde el archivo externo data.json
    fetch('data.json')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP! estado: ${response.status}`);
            }
            return response.json();
        })
        .then(heroData => {
            // Imagen de fondo
            document.getElementById("hero-banner").style.backgroundImage = `url('${heroData.backgroundImage}')`;

            // Logo principal
            const logoImg = document.getElementById("logo-img");
            logoImg.src = heroData.logo.src;
            logoImg.alt = heroData.logo.alt;
            document.getElementById("logo-link").href = heroData.logo.link;

            // Menú de Navegación
            const navMenu = document.getElementById("nav-menu");
            navMenu.innerHTML = heroData.navigation
                .map(item => `<li><a href="${item.url}" class="nav-link">${item.label}</a></li>`)
                .join("");

            // Botón de WhatsApp superior
            const btnWhatsapp = document.getElementById("btn-whatsapp");
            btnWhatsapp.href = heroData.whatsappNav.url;
            document.getElementById("whatsapp-text").textContent = heroData.whatsappNav.label;

            // Textos principales
            document.getElementById("tagline-text").innerHTML = heroData.heroText.tagline;
            document.getElementById("sub-heading-text").textContent = heroData.heroText.subHeading;
            document.getElementById("main-heading-text").textContent = heroData.heroText.mainHeading;

            // Botones CTA
            const btnCatalog = document.getElementById("btn-catalog");
            btnCatalog.href = heroData.buttons.catalog.url;
            btnCatalog.textContent = heroData.buttons.catalog.label;

            const btnQuote = document.getElementById("btn-quote");
            btnQuote.href = heroData.buttons.quote.url;
            document.getElementById("btn-quote-text").textContent = heroData.buttons.quote.label;

            // Badge lateral
            const badgeImg = document.getElementById("badge-img");
            badgeImg.src = heroData.badgeRight.src;
            badgeImg.alt = heroData.badgeRight.alt;

            // INTERACCIÓN ROBUSTA PARA EL MENÚ HAMBURGUESA
            const menuToggle = document.getElementById("menu-toggle");
            const navMenuWrapper = document.getElementById("nav-menu-wrapper");

            if (menuToggle && navMenuWrapper) {
                menuToggle.addEventListener("click", (e) => {
                    e.stopPropagation();
                    navMenuWrapper.classList.toggle("show-menu");

                    const icon = menuToggle.querySelector("i");
                    if (icon) {
                        if (navMenuWrapper.classList.contains("show-menu")) {
                            icon.className = "fa-solid fa-xmark";
                        } else {
                            icon.className = "fa-solid fa-bars";
                        }
                    }
                });

                // Cierra el menú al hacer clic en un enlace
                navMenuWrapper.addEventListener("click", (e) => {
                    if (e.target.classList.contains("nav-link")) {
                        navMenuWrapper.classList.remove("show-menu");
                        const icon = menuToggle.querySelector("i");
                        if (icon) icon.className = "fa-solid fa-bars";
                    }
                });
            }
        })
        .catch(error => {
            console.error("Error al cargar la data desde el archivo JSON:", error);
        });
});
