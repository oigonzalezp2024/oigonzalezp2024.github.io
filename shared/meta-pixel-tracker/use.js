// 1. Instancias la clase
const tracker = new MetaPixelTracker({ currency: 'COP' });

// 2. Preparas todos los datos recopilados de tu carrito o formulario
const orderData = {
  customer: {
    name: "María Gómez",
    phone: "+573001234567"
  },
  products: [
    {
      id: "SAND-001",
      name: "Sandalia Plataforma Confort",
      category: "Sandalias",
      price: 85000,
      quantity: 2,
      size: "37",
      color: "Beige",
      imageUrl: "https://oigonzalezp2024.github.io/ecommerce/img/sandalias/sandalia1.jpeg",
      url: "https://oigonzalezp2024.github.io/ecommerce/sandalias/index.html"
    }
  ],
  total: 170000
};

// 3. Ejecutas el rastreo en el momento de confirmar o enviar a WhatsApp
tracker.trackPurchase(orderData);
