# Portafolio Profesional - Óscar Iván González Peña

Este repositorio contiene la implementación de mi portafolio digital 2026.

## Estructura de Contenidos

El sitio se construye a partir de dos archivos principales en el directorio `data/`:

1. `db_list.json`: Índice y metadatos de los módulos del portafolio.
2. `db_details.json`: Contenido detallado de cada bloque según el siguiente esquema:

```json
[
  {
    "id": 3,
    "title": "Título del Artículo",
    "author": "Nombre Autor",
    "date": "YYYY-MM-DD",
    "content": [
      { "type": "paragraph", "data": "Texto del párrafo..." },
      { "type": "image", "url": "ruta/imagen.jpg", "caption": "Pie de foto" },
      { "type": "video", "url": "https://youtube.com/...", "orientation": "horizontal" },
      { "type": "link", "url": "https://...", "text": "Texto del enlace" }
    ]
  }
]

```

### Especificación de Bloques

| Tipo (`type`) | Descripción | Propiedades |
| --- | --- | --- |
| **`paragraph`** | Texto legible | `data` |
| **`image`** | Imagen ilustrativa | `url`, `caption` |
| **`video`** | Multimedia incrustada | `url`, `orientation` (horizontal/vertical) |
| **`link`** | Enlace externo | `url`, `text` |
