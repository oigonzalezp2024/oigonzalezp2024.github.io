## 🚀 Guía de Inicio Rápido (Get Started)

### 1. Clonar o descargar el repositorio

Ubica los archivos del proyecto en el directorio raíz de tu servidor web (p. ej., `/var/www/html/` o `htdocs`).

### 2. Configurar Variables de Entorno (Opcional para Sync)

Copia el archivo de plantilla `.env.example` y renómbralo a `.env`:

```bash
cp .env.example .env

```

Define las credenciales correspondientes otorgadas por Meta for Developers:

```env
META_APP_ID=tu_app_id
META_APP_SECRET=tu_app_secret
META_AD_ACCOUNT_ID=act_123456789012345
META_ACCESS_TOKEN=tu_access_token_aqui

```

> ⚠️ **Nota:** Si las credenciales no están configuradas en el servidor, la aplicación funcionará de manera local (MML y lecturas de Excel). Al hacer clic en **"Sincronizar Meta"**, la plataforma notificará que se requiere activación técnica.

### 3. Verificar Permisos en Apache

Asegúrate de que el servidor permita la lectura de reglas en `.htaccess` (`AllowOverride All` en la configuración de Apache).

---

## 🛡️ Aspectos de Seguridad

1. **Bloqueo de `.env` y Caché:** El archivo `.htaccess` deniega de forma estricta cualquier intento de acceso vía HTTP a archivos de entorno o archivos de caché internos (`cache_meta_ads.json`).
2. **Restricción de Endpoints:** La consulta a `meta_ads.php` solo se procesa si incluye el parámetro explícito `action=get_insights`. Cualesquiera otras peticiones directas son rechazadas con un error `403 Forbidden`.
3. **Cabeceras HTTP Seguras:** Incorpora protección nativa contra Clickjacking (`X-Frame-Options`), Mime Sniffing (`X-Content-Type-Options`) y XSS Cross-site (`X-XSS-Protection`).

---

## 📞 Soporte Técnico y Licenciamiento

Para activar la sincronización automática en tiempo real con Meta Ads o solicitar soporte personalizado en el despliegue, contacta al administrador de la instancia o al equipo de soporte técnico asignado.
"""

with open("README.md", "w", encoding="utf-8") as f:
f.write(readme_content)

print("FILE_CREATED: README.md")

```

```text?code_stdout&code_event_index=1
FILE_CREATED: README.md


```

Your Markdown file is ready
[file-tag: code-generated-file-e0186938-1bf8-4663-9ee8-1f63dc8a6a67]

```markdown
# 🚀 Growth Engine Manager (Growth Suite)

**Growth Engine Manager** es una suite ligera y modular diseñada para la gestión estratégica de marketing digital. Permite administrar la **Matriz de Marco Lógico (MML)** y centralizar el análisis de rendimiento de anuncios mediante la importación de reportes (Excel/CSV) y la sincronización en tiempo real con **Meta Ads Graph API**.

---

## 📌 Características Principales

* **📌 Matriz de Marco Lógico (MML):**
  * CRUD interactivo con interfaz modular.
  * Persistencia local mediante `localStorage`.
  * Importación y exportación de matrices en formato JSON.
  * Sanitización nativa contra ataques XSS.

* **📊 Dashboard de Campañas & Anuncios:**
  * Importación masiva de reportes en Excel (`.xlsx`, `.xls`) y `.csv`.
  * Exportación de datos analíticos a CSV plano.
  * Integración con la API de Meta Ads (Graph API `v19.0`).

* **🔒 Control de Acceso y Modelo Comercial:**
  * Validación de entorno y estado de activación (`unlicensed`).
  * Bloqueo de seguridad a archivos sensibles mediante `.htaccess`.
  * Intercepción elegante de funciones no licenciadas dirigidas a soporte técnico.

---

## 🛠️ Requisitos del Sistema

* **Servidor Web:** Apache 2.4+ (con módulos `mod_rewrite` y `mod_headers` habilitados).
* **PHP:** Versión 7.4 o superior (con extensión `cURL` activa).
* **Navegador:** Cualquier navegador moderno compatible con ES6 y `localStorage`.

---

## 📁 Estructura del Proyecto

```text
.
├── index.html          # Interfaz principal y estructura SPA
├── styles.css          # Estilos responsivos con CSS Custom Properties
├── app.js              # Lógica del cliente (MML, Excel, Sync)
├── meta_ads.php        # Conector backend securizado para Meta Graph API
├── .htaccess           # Reglas de seguridad HTTP y reescritura
├── .env.example        # Plantilla de variables de entorno (Crear .env)
└── README.md           # Documentación del proyecto

```

---

## 🚀 Guía de Inicio Rápido (Get Started)

### 1. Clonar o descargar el repositorio

Ubica los archivos del proyecto en el directorio raíz de tu servidor web (p. ej., `/var/www/html/` o `htdocs`).

### 2. Configurar Variables de Entorno (Opcional para Sync)

Copia el archivo de plantilla `.env.example` y renómbralo a `.env`:

```bash
cp .env.example .env

```

Define las credenciales correspondientes otorgadas por Meta for Developers:

```env
META_APP_ID=tu_app_id
META_APP_SECRET=tu_app_secret
META_AD_ACCOUNT_ID=act_123456789012345
META_ACCESS_TOKEN=tu_access_token_aqui

```

> ⚠️ **Nota:** Si las credenciales no están configuradas en el servidor, la aplicación funcionará de manera local (MML y lecturas de Excel). Al hacer clic en **"Sincronizar Meta"**, la plataforma notificará que se requiere activación técnica.

### 3. Verificar Permisos en Apache

Asegúrate de que el servidor permita la lectura de reglas en `.htaccess` (`AllowOverride All` en la configuración de Apache).

---

## 🛡️ Aspectos de Seguridad

1. **Bloqueo de `.env` y Caché:** El archivo `.htaccess` deniega de forma estricta cualquier intento de acceso vía HTTP a archivos de entorno o archivos de caché internos (`cache_meta_ads.json`).
2. **Restricción de Endpoints:** La consulta a `meta_ads.php` solo se procesa si incluye el parámetro explícito `action=get_insights`. Cualesquiera otras peticiones directas son rechazadas con un error `403 Forbidden`.
3. **Cabeceras HTTP Seguras:** Incorpora protección nativa contra Clickjacking (`X-Frame-Options`), Mime Sniffing (`X-Content-Type-Options`) y XSS Cross-site (`X-XSS-Protection`).

---

## 📞 Soporte Técnico y Licenciamiento

Para activar la sincronización automática en tiempo real con Meta Ads o solicitar soporte personalizado en el despliegue, contacta al administrador de la instancia o al equipo de soporte técnico asignado.

```

```
