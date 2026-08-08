document.addEventListener('DOMContentLoaded', () => {
  // Desplazamiento suave para la navegación
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth'
        });
      }
    });
  });

  // Interacción en videos / miniaturas
  const playButtons = document.querySelectorAll('.play-btn, .play-icon');
  playButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      alert('Aquí se activaría el reproductor de video con el proceso ecológico.');
    });
  });
});
