## ⚠️ Las 2 Discrepancias Encontradas

### 1. Nombre del script de IA

* **En el `README.md` dice:** `ai_analyst.php` (y que consulta OpenAI/Gemini).
* **En tu proyecto real tienes:** `ai_evaluator.php` (y está escrito para consumir exclusivamente la API de Google Gemini en el modelo `gemini-2.5-flash`).



### 2. Nombre de la Variable de Entorno para Meta y Gemini

* **En el `README.md` dice:** `META_AD_ACCOUNT_ID` y `AI_API_KEY`.
* **En tu código PHP real:**
* En `meta_ads.php` la variable se llama `META_ACT_ACCOUNT_ID`.


* En `ai_evaluator.php` la variable se llama `GEMINI_API_KEY`.





---

## 🛠️ README.md Corregido y Alineado a tu Código

Aquí tienes el bloque completo del `README.md` corregido con las rutas, nombres de scripts y variables exactas de tu proyecto:

```markdown
# 🚀 Growth Engine Manager (Growth Suite)

**Growth Engine Manager** es una suite ligera y modular diseñada para la gestión estratégica de marketing digital. Permite administrar la **Matriz de Marco Lógico (MML)**, centralizar el análisis de rendimiento de anuncios mediante la importación de reportes (Excel/CSV) o sincronización con **Meta Ads Graph API**, y realizar evaluaciones automatizadas a la matriz con **Google Gemini API**.

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
  * Sincronización en tiempo real con Meta Ads (Graph API `v19.0`).

* **🤖 Asistente de Evaluación e Inteligencia Artificial:**
  * Auditoría estratégica automática cruzando los datos de la MML y el rendimiento real de Meta Ads.
  * Detección de desviaciones operativas y propuestas automáticas de mejora en las Actividades y Componentes.
  * Integración segura vía backend con **Google Gemini API (`gemini-2.5-flash`)**.

* **🔒 Control de Acceso y Modelo Comercial:**
  * Validación de entorno y estado de activación (`unlicensed`).
  * Bloqueo de seguridad a archivos sensibles mediante `.htaccess`.
  * Intercepción elegante de funciones no licenciadas dirigidas a soporte técnico.

---

## 🛠️ Requisitos del Sistema

* **Servidor Web:** Apache 2.4+ (con módulos `mod_rewrite` y `mod_headers` habilitados).
* **PHP:** Versión 7.4 o superior (con extensión `cURL` y `json` activas).
* **Navegador:** Cualquier navegador moderno compatible con ES6 y `localStorage`.

---

## 📁 Estructura del Proyecto

```text
.
├── index.html          # Interfaz principal y estructura SPA
├── styles.css          # Estilos responsivos con CSS Custom Properties
├── app.js              # Lógica del cliente (MML, Excel, Sync)
├── ai_evaluator.js     # Lógica cliente para evaluación IA y versión de MML
├── meta_ads.php        # Conector backend securizado para Meta Graph API
├── ai_evaluator.php    # Endpoint backend para procesamiento de evaluación con Gemini API
├── .htaccess           # Reglas de seguridad HTTP y reescritura
├── .env.example        # Plantilla de variables de entorno (Crear .env)
└── README.md           # Documentación del proyecto

```

---

## ⚙️ Configuración Inicial

### 1. Variables de Entorno (`.env`)

Copia el archivo `.env.example` y renómbralo a `.env`:

```bash
cp .env.example .env

```

Configura tus credenciales exactamente como las requiere el backend PHP:

```env
# Configuración de Meta Ads Graph API
META_APP_ID=1234567890123456
META_APP_SECRET=tu_app_secret_aqui
META_ACT_ACCOUNT_ID=act_123456789012345
META_ACCESS_TOKEN=tu_access_token_aqui

# Configuración de Google Gemini API
GEMINI_API_KEY=tu_gemini_api_key_aqui

```

---

## 🔑 Configuración Paso a Paso: Meta Ads API

Para habilitar la sincronización en tiempo real con Meta Ads, sigue estos pasos en **Meta for Developers**:

1. **Crear una App:**
* Ve a [Meta for Developers](https://developers.facebook.com/) y crea una nueva aplicación de tipo **Business / Negocios**.


2. **Obtener App ID y App Secret:**
* Navega a **Configuración de la app > Básica**. Copia el `Identificador de la app` y la `Clave secreta de la app` en `META_APP_ID` y `META_APP_SECRET`.


3. **Obtener Identificador de Cuenta Publicitaria (`META_ACT_ACCOUNT_ID`):**
* Ingresa a tu **Meta Ads Manager**. En la URL o en el selector de cuentas ubica el ID numérico de tu cuenta y agrégale el prefijo `act_` (ejemplo: `act_102030405060`).


4. **Generar Token de Acceso del Sistema (`META_ACCESS_TOKEN`):**
* Ve a tu **Business Manager (Meta Business Suite) > Configuración del negocio > Usuarios del sistema**.
* Crea o selecciona un *Usuario del sistema* con rol Administrador.
* Asigna la cuenta publicitaria correspondiente con permisos de **Ver rendimiento / Leer anuncios**.
* Haz clic en **Generar nuevo token**, selecciona la app creada y marca los permisos `ads_read` y `read_insights`. Copia el token generado en `META_ACCESS_TOKEN`.



---

## 🤖 Consulta y Evaluación con IA (Gemini API)

El módulo de IA evalúa si las **Actividades y Componentes** de la Matriz de Marco Lógico se alinean con las métricas reales del Dashboard de Meta Ads.

### Flujo de Funcionamiento:

1. **Extracción de Datos:** El cliente envía el objeto JSON de la MML y las métricas filtradas de anuncios (`meta_insights`).
2. **Procesamiento Backend (`ai_evaluator.php`):** El script PHP lee `GEMINI_API_KEY` desde las variables de entorno y ejecuta una petición HTTPS hacia el modelo `gemini-2.5-flash` exigiendo una respuesta estructurada en formato JSON.
3. **Propuesta y Versionado:** El cliente muestra los hallazgos y permite al usuario aplicar los cambios sugeridos. Antes de mutar la MML, se genera un backup automático en `localStorage` para poder revertir cambios en cualquier momento.

---

## 🚀 Guía de Despliegue Rápido

1. Ubica los archivos en el directorio raíz de tu servidor web (ej. `/var/www/html/` o `htdocs`).
2. Configura los valores en el archivo `.env`.
3. Verifica que Apache tenga habilitada la lectura de `.htaccess` (`AllowOverride All`).

---

## 🛡️ Aspectos de Seguridad

1. **Protección de Credenciales:** El archivo `.htaccess` bloquea el acceso público a `.env`, archivos de configuración y cachés locales (`cache_meta_ads.json`).
2. **Sanitización e Aislamiento:** Las consultas a `meta_ads.php` requieren el parámetro explícito `action=get_insights`. Las claves de la API de Meta y Gemini nunca se exponen al navegador cliente.
3. **Cabeceras HTTP Seguras:** Implementación nativa de `X-Frame-Options`, `X-Content-Type-Options` y `X-XSS-Protection`.

---

## 📞 Soporte Técnico y Licenciamiento

Para soporte en el despliegue, activación de entornos no licenciados o integración personalizada de modelos de IA, contacta al administrador del sistema o al equipo de soporte asignado.

```
