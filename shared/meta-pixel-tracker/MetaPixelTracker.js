/**
 * Clase para gestionar eventos de Meta Pixel
 */
class MetaPixelTracker {
  /**
   * @param {string} pixelId - ID de seguimiento de Meta Pixel
   */
  constructor(pixelId) {
    this.pixelId = pixelId;
    this.currencyDefault = 'COP';
  }

  /**
   * Registra cuando un usuario agrega o selecciona un producto para el carrito.
   * 
   * @param {Object} producto - Datos del producto agregado
   * @param {string|number} producto.id - Identificador único del producto
   * @param {string} producto.nombre - Nombre o título del producto
   * @param {number} producto.precio - Precio unitario del producto
   * @param {number} [producto.cantidad=1] - Cantidad agregada
   * @param {string} [producto.currency='COP'] - Moneda
   */
  trackAddToCart({ id, nombre, precio, cantidad = 1, currency = this.currencyDefault }) {
    if (typeof fbq === 'function') {
      fbq('track', 'AddToCart', {
        content_ids: [String(id)],
        content_name: nombre,
        value: precio * cantidad,
        currency: currency,
        content_type: 'product',
        num_items: cantidad
      });
    } else {
      console.warn('Meta Pixel (fbq) no está cargado en el documento.');
    }
  }
}
