class MetaPixelTracker {
  /**
   * Instancia el rastreador de Meta Pixel.
   * @param {Object} options Configuración inicial opcional.
   */
  constructor(options = {}) {
    this.currency = options.currency || 'COP';
  }

  /**
   * Verifica si la función de Meta Pixel (fbq) está disponible.
   * @returns {boolean}
   */
  isPixelLoaded() {
    return typeof window.fbq === 'function';
  }

  /**
   * Mapea y normaliza la información completa de un producto.
   * @param {Object} product Objeto con los datos del producto.
   * @returns {Object} Objeto estructurado para el parámetro 'contents' de Meta Pixel.
   */
  formatProductItem(product) {
    return {
      id: String(product.id || product.sku || ''),
      content_name: product.name || product.title || '',
      content_category: product.category || '',
      quantity: Number(product.quantity || product.qty || 1),
      item_price: Number(product.price || 0),
      size: product.size || product.talla || '',
      color: product.color || '',
      image_url: product.imageUrl || product.image || product.img || '',
      product_url: product.url || window.location.href
    };
  }

  /**
   * Registra el evento Purchase recopilando toda la información de la compra.
   * @param {Object} orderData Datos del pedido y del cliente.
   * @param {Array<Object>} orderData.products Lista de productos seleccionados.
   * @param {number} [orderData.total] Monto total (se calcula automáticamente si no se pasa).
   * @param {Object} [orderData.customer] Datos opcionales del cliente (nombre, teléfono, etc.).
   */
  trackPurchase(orderData = {}) {
    if (!this.isPixelLoaded()) {
      console.warn('Meta Pixel (fbq) no está cargado en esta página.');
      return;
    }

    const products = orderData.products || [];
    
    // Mapea y estructura toda la información detallada de cada producto
    const formattedContents = products.map(prod => this.formatProductItem(prod));

    // Calcula el valor total si no se envía explícitamente
    const totalValue = orderData.total !== undefined
      ? Number(orderData.total)
      : formattedContents.reduce((sum, item) => sum + (item.item_price * item.quantity), 0);

    // Cantidad total de ítems comprados
    const totalNumItems = formattedContents.reduce((sum, item) => sum + item.quantity, 0);

    // Payload completo para Meta
    const pixelPayload = {
      content_type: 'product',
      contents: formattedContents,
      value: totalValue,
      currency: this.currency,
      num_items: totalNumItems,
      // Información adicional del cliente si está disponible
      customer_info: orderData.customer ? {
        name: orderData.customer.name || '',
        phone: orderData.customer.phone || ''
      } : undefined
    };

    // Envía el evento a Meta Pixel
    window.fbq('track', 'Purchase', pixelPayload);
    console.log('Evento Purchase enviado a Meta Pixel:', pixelPayload);
  }
}
