document.addEventListener('DOMContentLoaded', () => {
    
    // CONTROL DEL MENÚ HAMBURGUESA
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const navMenu = document.getElementById('nav-menu');
    const navbar = document.querySelector('.navbar');
    const navItems = document.querySelectorAll('.nav-item, .btn-primary');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = navMenu.classList.contains('active');
            toggleMenu(!isOpen);
        });

        navItems.forEach(item => {
            item.addEventListener('click', () => toggleMenu(false));
        });

        document.addEventListener('click', (e) => {
            if (!navMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                toggleMenu(false);
            }
        });
    }

    function toggleMenu(open) {
        if (open) {
            navMenu.classList.add('active');
            navbar.classList.add('mobile-active');
            menuToggle.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            menuToggle.setAttribute('aria-expanded', 'true');
        } else {
            navMenu.classList.remove('active');
            navbar.classList.remove('mobile-active');
            menuToggle.innerHTML = '<i class="fa-solid fa-bars"></i>';
            menuToggle.setAttribute('aria-expanded', 'false');
        }
    }

    // TRAZABILIDAD DE CLICS EN CONVERSIÓN
    const trackingButtons = document.querySelectorAll('.btn-accent, .btn-outline, .btn-card-brown, .btn-primary');
    trackingButtons.forEach(button => {
        button.addEventListener('click', () => {
            const label = button.textContent.trim();
            const destination = button.getAttribute('href');
            console.log(`[Track]: Click en "${label}" -> ${destination}`);
        });
    });
});
