Para lograr que la clase **MetaPixelTracker** envíe el evento a Meta cada vez que un usuario haga clic para seleccionar un producto, debes realizar estas tres modificaciones exactas en tu archivo [index.html](https://github.com/oigonzalezp2024/oigonzalezp2024.github.io/blob/feature/meta-pixel-tracker/ecommerce/sandalias/index.html#L1472):

---

### 1. Incluir el script de la clase

Agrega la etiqueta `<script>` justo antes del cierre del `</body>` (después de las librerías o al final de los scripts):

```html
<!-- Importación de la clase MetaPixelTracker -->
<script src="../../shared/meta-pixel-tracker/MetaPixelTracker.js"></script>

```

---

### 2. Instanciar la clase globalmente

En la sección donde inicializas las variables globales de tu script principal (por ejemplo, arriba cerca de las primeras líneas del bloque `<script>`), crea la instancia del tracker:

```javascript
// Instancia global del rastreador de Meta Pixel
const pixelTracker = new MetaPixelTracker('2953487184993036');

```

---

### 3. Modificar la función `toggleSeleccion`

Ubica la función `toggleSeleccion(prodId)` en el código e integra la llamada al método de rastreo cuando un producto es seleccionado (en el bloque `else` donde el producto pasa a estar activo):

```javascript
function toggleSeleccion(prodId) {
  const prod = CATALOGO_COMPLETO.find(p => p.id === prodId);
  if (!prod) return;

  const index = estadoPedido.items.findIndex(item => item.id === prodId);

  if (index > -1) {
    // Si ya está seleccionado, se quita del pedido
    estadoPedido.items.splice(index, 1);
  } else {
    // Se agrega al pedido
    const tallaDefault = prod.tallasDisponibles ? prod.tallasDisponibles[0] : 36;
    estadoPedido.items.push({
      id: prod.id,
      nombre: prod.nombre,
      precioPlena: prod.precioPlena,
      talla: tallaDefault,
      cantidad: 1,
      imagen: prod.imagen
    });

    // =========================================================
    // ENVIAR EVENTO A META PIXEL AL DAR CLIC EN EL PRODUCTO
    // =========================================================
    if (typeof pixelTracker !== 'undefined') {
      pixelTracker.trackPurchase({
        items: [{
          id: prod.id,
          nombre: prod.nombre,
          precioPlena: prod.precioPlena,
          cantidad: 1
        }],
        total: prod.precioPlena,
        currency: 'COP'
      });
    }
  }

  // Actualiza la interfaz de usuario
  renderizarCatalogo();
  actualizarResumenUI();
}

```