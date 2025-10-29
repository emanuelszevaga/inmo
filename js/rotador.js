document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Datos de Propiedades - Usamos la variable global DATOS_PROPIEDADES cargada por PHP
    // Si PHP no la cargó, usamos el array vacío para evitar errores.
    const propiedades = window.DATOS_PROPIEDADES || []; 

    if (propiedades.length === 0) {
        // En caso de fallo de DB/PHP, puedes forzar los datos de ejemplo aquí
        console.error("No se pudieron cargar las propiedades de PHP. Usando Fallback.");
        return; 
    }
    
    let indiceActual = 0;
    const totalPropiedades = propiedades.length;

    // 2. Elementos del DOM
    const imgElement = document.getElementById('rotador-imagen');
    const tituloElement = document.getElementById('rotador-titulo');
    const descElement = document.getElementById('rotador-descripcion');
    const precioElement = document.getElementById('rotador-precio');
    
    // **¡PUNTO CRÍTICO!** Asegúrate de que estos IDs existan en tu HTML
    const btnAnterior = document.getElementById('anterior');
    const btnSiguiente = document.getElementById('siguiente');

    // 3. Función para actualizar el contenido del rotador
    function actualizarRotador(indice) {
        const prop = propiedades[indice];
        
        // El nombre de las propiedades debe coincidir con los campos de tu DB:
        imgElement.src = prop.imagen_url; 
        tituloElement.textContent = prop.titulo;
        descElement.textContent = prop.descripcion_corta;
        precioElement.textContent = prop.precio; 
        
        // Animación simple para que el cambio sea visible
        imgElement.style.opacity = 0.5;
        setTimeout(() => {
            imgElement.style.opacity = 1;
        }, 100); 
    }
    
    // 4. Lógica de Navegación
    function irSiguiente() {
        indiceActual = (indiceActual + 1) % totalPropiedades;
        actualizarRotador(indiceActual);
    }
    
    function irAnterior() {
        // Lógica para que al ir 'anterior' desde 0, vaya al final
        indiceActual = (indiceActual - 1 + totalPropiedades) % totalPropiedades;
        actualizarRotador(indiceActual);
    }

    // 5. Asignación de Eventos **(La solución al problema)**
    
    // 💡 Aquí se adjunta la función irAnterior a un evento click
    if (btnAnterior) {
        btnAnterior.addEventListener('click', irAnterior);
    } else {
        console.warn("Elemento 'anterior' no encontrado.");
    }
    
    // 💡 Aquí se adjunta la función irSiguiente a un evento click
    if (btnSiguiente) {
        btnSiguiente.addEventListener('click', irSiguiente);
    } else {
        console.warn("Elemento 'siguiente' no encontrado.");
    }

    // 6. Inicialización y Rotación Automática
    actualizarRotador(indiceActual); 

    // Rotación Automática (Funcionalidad principal del parcial)
    setInterval(irSiguiente, 5000); // Cambia cada 5 segundos
});