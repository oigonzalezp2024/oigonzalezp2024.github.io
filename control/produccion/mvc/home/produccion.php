<?php
$id_orden = $_GET['id_orden'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arquitectura Hexagonal - Cadena de Valor Operativa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="ui-header">
        <h1>Modelo de Operación Empresarial</h1>
        <p>Arquitectura Hexagonal &bull; Mapeo de Cadena de Valor</p>
    </header>

    <canvas id="space-canvas"></canvas>

    <!-- VISTA ESCRITORIO (DIAGRAMA 3D) -->
    <div class="info-card">
        <h3 id="info-title">Cadena de Valor</h3>
        <p id="info-desc">Mueve el ratón suavemente para explorar el diagrama en 3D. Pasa el cursor sobre cualquiera de las 13 etapas para ver detalles.</p>
        
        <div class="business-rule-box" id="business-box">
            <span class="business-title" id="business-title">ACUERDO OPERATIVO</span>
            <div class="business-text" id="business-text"></div>
        </div>

        <div class="author-box">
            <span class="author-tag">Desarrollo de Software a Medida</span>
            <div class="author-name">Óscar Iván González Peña</div>
            <div class="author-contact">
                ✉️ <a href="mailto:oigonzalezp2024@gmail.com">oigonzalezp2024@gmail.com</a><br>
                📱 <a href="https://wa.me/573212962876?text=Hola,%20estoy%20interesado%20en%20desarrollo%20de%20software%20a%20medida" target="_blank">3212962876 - WhatsApp</a>
            </div>
            <a href="https://wa.me/573212962876?text=Hola,%20estoy%20interesado%20en%20desarrollo%20de%20software%20a%20medida" target="_blank" class="whatsapp-btn">
                💬 Abrir Chat de WhatsApp
            </a>
        </div>
    </div>

    <main class="stage">
        <div class="viewport" id="viewport">
            <div class="badge-label badge-adapters">Procesos & Recursos Externos</div>

            <div class="system-container">
                <svg class="svg-hex-container" viewBox="0 0 500 500">
                    <defs>
                        <radialGradient id="coreGradient" cx="50%" cy="50%" r="50%">
                            <stop offset="0%" stop-color="#ffffff" />
                            <stop offset="100%" stop-color="#f7d100" />
                        </radialGradient>
                    </defs>
                    <polygon class="hex-path-adapters" points="250,25 433,130 433,370 250,475 67,370 67,130" />
                    <polygon class="hex-path-ports" points="250,95 375,167 375,333 250,405 125,333 125,167" />
                    <circle class="core-circle-path" cx="250" cy="250" r="65" />
                </svg>

                <div class="core-node" id="layer-core" 
                     data-title="Núcleo de Gestión y Reglas Comerciales" 
                     data-desc="El 'cerebro' estratégico que conecta las 13 etapas asegurando que funcionen como un solo sistema."
                     data-rule="🛡️ El Núcleo de la Empresa: Los proveedores de transporte, los locales o el software pueden cambiar, pero las reglas de negocio de la empresa permanecen intactas.">
                    <span class="core-title">SISTEMA CENTRAL</span>
                    <span class="core-subtitle">Gestión & Reglas de Negocio</span>
                </div>

                <div class="node-wrapper pos-3">
                    <a href="../control_produccion/vista/ordenes_fabricacion.php">
                        <div class="node" data-title="4. Obtener Herramientas" data-desc="Adquisición de maquinaria, software especializado y equipo operativo." data-rule="📌 Gestión: Otorga al personal la tecnología homologada para cumplir las metas de producción.">
                            <svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                            <span class="node-tag">Producción</span>
                        </div>
                    </a>
                </div>

                <div class="node-wrapper pos-5">
                    <a href="../../operario/ver_pdf_taller.php?id_orden=<?php echo $id_orden; ?>">
                        <div class="node" data-title="4. Obtener Herramientas" data-desc="Adquisición de maquinaria, software especializado y equipo operativo." data-rule="📌 Gestión: Otorga al personal la tecnología homologada para cumplir las metas de producción.">
                            <svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                            <span class="node-tag">Hoja taller</span>
                        </div>
                    </a>
                </div>

                <div class="node-wrapper pos-7">
                    <a href="../../control_costos/ver_costos_orden.php?id_orden=<?php echo $id_orden; ?>">
                        <div class="node" data-title="4. Obtener Herramientas" data-desc="Adquisición de maquinaria, software especializado y equipo operativo." data-rule="📌 Gestión: Otorga al personal la tecnología homologada para cumplir las metas de producción.">
                            <svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                            <span class="node-tag">Costos</span>
                        </div>
                    </a>
                </div>

                <div class="node-wrapper pos-11">
                    <a href="./index.html">
                        <div class="node" data-title="11. Distribución"
                            data-desc="Organización de rutas logísticas y despacho desde centros de acopio."
                            data-rule="📌 Logística: Asigna la ruta óptima utilizando los transportes contratados en el paso 3.">
                            <svg viewBox="0 0 24 24">
                                <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                <line x1="8" y1="2" x2="8" y2="18"></line>
                                <line x1="16" y1="6" x2="16" y2="22"></line>
                            </svg>
                            <span class="node-tag">Volver</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <div class="footer-credits">
        Desarrollo de Software a Medida &bull; <strong>Óscar Iván González Peña</strong> &bull; <a href="https://wa.me/573212962876?text=Hola,%20estoy%20interesado%20en%20desarrollo%20de%20software%20a%20medida" target="_blank" style="color:inherit; text-decoration:underline;">3212962876 (WhatsApp)</a>
    </div>

    <div class="legend">
        <div class="legend-item"><div class="dot" style="background: #f7d100; border:1px solid #0b4da1;"></div>Núcleo / Reglas</div>
        <div class="legend-item"><div class="dot" style="background: #0b4da1"></div>Control & Estándares</div>
        <div class="legend-item"><div class="dot" style="background: #f7d100"></div>Operación / Recursos</div>
    </div>

    <!-- VISTA MÓVIL -->
    <section class="mobile-view">
        <div class="mobile-card" style="border-left: 3px solid #0b4da1;">
            <span class="author-tag">Desarrollo de Software a Medida</span>
            <div class="author-name">Óscar Iván González Peña</div>
            <div class="author-contact" style="margin-top:6px;">
                ✉️ <a href="mailto:oigonzalezp2024@gmail.com" style="color:#0b4da1;">oigonzalezp2024@gmail.com</a><br>
                📱 <a href="https://wa.me/573212962876?text=Hola,%20estoy%20interesado%20en%20desarrollo%20de%20software%20a%20medida" target="_blank" style="color:var(--whatsapp-color); font-weight:700;">3212962876 - WhatsApp</a>
            </div>
            <a href="https://wa.me/573212962876?text=Hola,%20estoy%20interesado%20en%20desarrollo%20de%20software%20a%20medida" target="_blank" class="whatsapp-btn">
                💬 Abrir Chat de WhatsApp
            </a>
        </div>

        <div class="mobile-card" style="border: 1px solid #0b4da1;">
            <div class="mobile-card-header">
                <span class="mobile-badge">NÚCLEO</span>
                <span class="mobile-title">Sistema Central de Gestión</span>
            </div>
            <div class="mobile-desc">El "cerebro" estratégico que conecta las 13 etapas asegurando que funcionen como un solo sistema integral.</div>
            <div class="mobile-rule">🛡️ El Núcleo de la Empresa: Los proveedores de transporte, los locales o el software pueden cambiar, pero las reglas de negocio permanecen intactas.</div>
        </div>

        <div class="mobile-card">
            <div class="mobile-card-header"><span class="mobile-badge">01</span><span class="mobile-title">Diseñar Producto</span></div>
            <div class="mobile-desc">Investigación, prototipado y definición de especificaciones técnicas.</div>
            <div class="mobile-rule">📌 Gestión: Establece los planos y especificaciones estándar que todo proveedor o fábrica debe cumplir.</div>
        </div>

        <div class="mobile-card">
            <div class="mobile-card-header"><span class="mobile-badge">02</span><span class="mobile-title">Espacios de Trabajo</span></div>
            <div class="mobile-desc">Arrendamiento y gestión de oficinas, plantas de producción y almacenes.</div>
            <div class="mobile-rule">📌 Gestión: Define el presupuesto y capacidades físicas requeridas para operar sin cuellos de botella.</div>
        </div>

        <div class="mobile-card">
            <div class="mobile-card-header"><span class="mobile-badge">03</span><span class="mobile-title">Servicios de Transportes</span></div>
            <div class="mobile-desc">Alianzas logísticas con flotas terrestres, marítimas o aéreas.</div>
            <div class="mobile-rule">📌 Gestión: Si se cambia de proveedor logístico, las reglas de tiempo de entrega y tarifas se mantienen unificadas.</div>
        </div>

        <div class="mobile-card">
            <div class="mobile-card-header"><span class="mobile-badge">04</span><span class="mobile-title">Obtener Herramientas</span></div>
            <div class="mobile-desc">Adquisición de maquinaria, software especializado y equipo operativo.</div>
            <div class="mobile-rule">📌 Gestión: Otorga al personal la tecnología homologada para cumplir las metas de producción.</div>
        </div>

        <div class="mobile-card">
            <div class="mobile-card-header"><span class="mobile-badge">05</span><span class="mobile-title">Obtener Materia Prima</span></div>
            <div class="mobile-desc">Abastecimiento con proveedores de insumos e ingredientes básicos.</div>
            <div class="mobile-rule">📌 Gestión: Regla estricta de inventario mínimo y homologación de calidad en insumos comprados.</div>
        </div>

        <div class="mobile-card">
            <div class="mobile-card-header"><span class="mobile-badge">06</span><span class="mobile-title">Ventas de Productos y Servicios</span></div>
            <div class="mobile-desc">Comercialización activa mediante canales e-commerce, tiendas físicas o fuerza de ventas.</div>
            <div class="mobile-rule">📌 Gestión: Recibe las órdenes de compra y aplica listas de precios y promociones oficiales.</div>
        </div>

        <div class="mobile-card">
            <div class="mobile-card-header"><span class="mobile-badge">07</span><span class="mobile-title">Contratar Personal</span></div>
            <div class="mobile-desc">Atracción de talento, contratación y asignación de perfiles idóneos.</div>
            <div class="mobile-rule">📌 Gestión: Garantiza que cada área cuente con el número de colaboradores calificados según la demanda.</div>
        </div>

        <div class="mobile-card">
            <div class="mobile-card-header"><span class="mobile-badge">08</span><span class="mobile-title">Fabricar Productos</span></div>
            <div class="mobile-desc">Línea de ensamble y transformación de materia prima en producto terminado.</div>
            <div class="mobile-rule">📌 Gestión: Transforma insumos siguiendo rigurosamente las fichas técnicas diseñadas en el paso 1.</div>
        </div>

        <div class="mobile-card">
            <div class="mobile-card-header"><span class="mobile-badge">09</span><span class="mobile-title">Control de Calidad</span></div>
            <div class="mobile-desc">Inspección y pruebas de cumplimiento previo al empaque.</div>
            <div class="mobile-rule">📌 Filtro Obligatorio: Si un producto no aprueba los estándares, no puede continuar al empaque ni distribución.</div>
        </div>

        <div class="mobile-card">
            <div class="mobile-card-header"><span class="mobile-badge">10</span><span class="mobile-title">Embalaje</span></div>
            <div class="mobile-desc">Empaquetado, etiquetado y preparación de lotes seguros para transporte.</div>
            <div class="mobile-rule">📌 Protección: Asegura la integridad del producto durante el transporte e incluye códigos de rastreo.</div>
        </div>

        <div class="mobile-card">
            <div class="mobile-card-header"><span class="mobile-badge">11</span><span class="mobile-title">Distribución</span></div>
            <div class="mobile-desc">Organización de rutas logísticas y despacho desde centros de acopio.</div>
            <div class="mobile-rule">📌 Logística: Asigna la ruta óptima utilizando los transportes contratados en el paso 3.</div>
        </div>

        <div class="mobile-card">
            <div class="mobile-card-header"><span class="mobile-badge">12-13</span><span class="mobile-title">Entrega y Validación</span></div>
            <div class="mobile-desc">Entrega física en manos del cliente y recolección de firmas / satisfacción.</div>
            <div class="mobile-rule">📌 Cierre de Ciclo: Confirma la recepción conforme y alimenta al sistema central con valoraciones del usuario.</div>
        </div>
    </section>

    <script>
        const spaceCanvas = document.getElementById('space-canvas');
        const ctxSpace = spaceCanvas.getContext('2d');
        let width, height, stars = [];
        let mouse = { x: 0, y: 0, targetX: 0, targetY: 0 };

        function resize() {
            width = spaceCanvas.width = window.innerWidth;
            height = spaceCanvas.height = window.innerHeight;
        }

        class Star {
            constructor() { this.reset(); }
            reset() {
                this.x = (Math.random() - 0.5) * width * 1.5;
                this.y = (Math.random() - 0.5) * height * 1.5;
                this.z = Math.random() * 1000 + 1;
                this.size = Math.random() * 1.5 + 0.3;
                this.alpha = Math.random() * 0.5 + 0.2;
            }
            update() {
                this.z -= 0.4;
                if (this.z <= 0) this.reset();
            }
            draw() {
                const k = 256 / this.z;
                const px = this.x * k + width / 2 + (mouse.x * (1000 - this.z) * 0.00001);
                const py = this.y * k + height / 2 + (mouse.y * (1000 - this.z) * 0.00001);

                if (px < 0 || px >= width || py < 0 || py >= height) return;

                ctxSpace.beginPath();
                ctxSpace.arc(px, py, this.size * k * 0.25, 0, Math.PI * 2);
                ctxSpace.fillStyle = `rgba(11, 77, 161, ${this.alpha})`;
                ctxSpace.fill();
            }
        }

        function initStars() {
            resize();
            stars = [];
            for (let i = 0; i < 160; i++) stars.push(new Star());
        }

        window.addEventListener('resize', resize);
        window.addEventListener('mousemove', (e) => {
            mouse.targetX = (e.clientX - window.innerWidth / 2) / (window.innerWidth / 2);
            mouse.targetY = (e.clientY - window.innerHeight / 2) / (window.innerHeight / 2);
        });

        const infoTitle = document.getElementById('info-title');
        const infoDesc = document.getElementById('info-desc');
        const businessBox = document.getElementById('business-box');
        const businessText = document.getElementById('business-text');
        const nodes = document.querySelectorAll('.node, #layer-core');

        nodes.forEach(node => {
            node.addEventListener('mouseenter', (e) => {
                const target = e.currentTarget;
                const title = target.getAttribute('data-title');
                const desc = target.getAttribute('data-desc');
                const rule = target.getAttribute('data-rule');

                if (title && desc) {
                    infoTitle.textContent = title;
                    infoDesc.textContent = desc;
                    if (rule) {
                        businessBox.style.display = 'block';
                        businessText.textContent = rule;
                    }
                }
            });

            node.addEventListener('mouseleave', () => {
                infoTitle.textContent = "Cadena de Valor";
                infoDesc.textContent = "Mueve el ratón suavemente para explorar el diagrama en 3D. Pasa el cursor sobre cualquiera de las 13 etapas para ver detalles.";
                businessBox.style.display = 'none';
            });
        });

        const viewport = document.getElementById('viewport');
        let currentRx = 8, currentRy = 0;

        function animate() {
            mouse.x += (mouse.targetX - mouse.x) * 0.08;
            mouse.y += (mouse.targetY - mouse.y) * 0.08;

            ctxSpace.fillStyle = '#f4f4f4';
            ctxSpace.fillRect(0, 0, width, height);

            stars.forEach(s => { s.update(); s.draw(); });

            if (window.innerWidth > 768 && viewport) {
                const targetRx = 8 + (-mouse.y * 6);
                const targetRy = (mouse.x * 8);
                
                currentRx += (targetRx - currentRx) * 0.1;
                currentRy += (targetRy - currentRy) * 0.1;

                viewport.style.transform = `rotateX(${currentRx}deg) rotateY(${currentRy}deg)`;
            }

            requestAnimationFrame(animate);
        }

        initStars();
        animate();
    </script>
</body>
</html>
