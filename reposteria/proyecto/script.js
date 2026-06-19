function togglePhase(phaseNumber) {
    // Obtener el contenedor de contenido específico de la fase cliqueada
    const content = document.getElementById(`phase-1-content`);
    
    // Iterar para cerrar otras fases y abrir la seleccionada
    for (let i = 1; i <= 4; i++) {
        const targetContent = document.getElementById(`phase-${i}-content`);
        if (i === phaseNumber) {
            // Alternar el estado de la fase seleccionada
            targetContent.classList.toggle('show-content');
        } else {
            // Cerrar el resto de las fases abiertas para mantener la interfaz limpia
            targetContent.classList.remove('show-content');
        }
    }
}

// Inicializar abriendo la primera fase de manera predeterminada al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('phase-1-content').classList.add('show-content');
});
