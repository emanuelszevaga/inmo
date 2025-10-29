<?php
// =================================================================
// 💡 BLOQUE PHP COMPLETO
// =================================================================

// Incluir el archivo de conexión
include('includes/conexion.php'); 
conectar(); // Llamar a la función de conexión (establece la variable global $conexion)

// --------------------------------------------------------------------------------
// 1. Datos de Ejemplo (Fallback - Si la DB no funciona o no hay resultados)
// --------------------------------------------------------------------------------
$propiedades_ejemplo = [
    ['titulo' => 'Chalet Acogedor en el Centro', 'precio' => '$150,000', 'descripcion_corta' => 'Perfecto para gatos amantes del sol.', 'imagen_url' => 'img/centro.jpeg'],
    ['titulo' => 'Piso Minimalista con Vistas', 'precio' => '$90,000', 'descripcion_corta' => 'Ideal para dormir sin interrupciones.', 'imagen_url' => 'img/lujo.jpeg'],
    ['titulo' => 'Estudio Loft para Artistas', 'precio' => '$75,000', 'descripcion_corta' => 'Ventanas grandes para cazar moscas.', 'imagen_url' => 'img/centro.jpeg']
];

$resultado_propiedades = null; // Inicializar por defecto

// --------------------------------------------------------------------------------
// 2. Procesamiento del Formulario de Contacto (Implementación PHP/DB - Escritura)
// --------------------------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre']) && isset($_POST['email'])) {
    
    // Usamos $conexion ya que fue inicializada por conectar()
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $email = $conexion->real_escape_string($_POST['email']);
    $mensaje = $conexion->real_escape_string($_POST['mensaje']);
    
    // Consulta de inserción (Asegúrate de tener la tabla 'consultas')
    $sql_insert_consulta = "INSERT INTO consultas (nombre, email, mensaje) VALUES ('$nombre', '$email', '$mensaje')";
    
    if ($conexion->query($sql_insert_consulta) === TRUE) {
        $mensaje_estado = "✅ ¡Consulta enviada con éxito! Te contactaremos pronto.";
    } else {
        $mensaje_estado = "❌ Error al enviar la consulta: " . $conexion->error;
    }
}

// --------------------------------------------------------------------------------
// 3. Consulta para Obtener Propiedades Destacadas (Implementación PHP/DB - Lectura)
// --------------------------------------------------------------------------------
$sql_select = "SELECT titulo, descripcion_corta, precio, imagen_url FROM propiedades LIMIT 3";

// Solo consultamos si la conexión fue exitosa
if (isset($conexion) && !$conexion->connect_errno) {
    $resultado_propiedades = $conexion->query($sql_select);
}

// --------------------------------------------------------------------------------
// 4. Preparar la data final para JS (usando el resultado de DB o el fallback)
// --------------------------------------------------------------------------------
if ($resultado_propiedades && $resultado_propiedades->num_rows > 0) {
    // Si la DB tiene datos, usa esos datos
    $propiedades_a_usar = $resultado_propiedades->fetch_all(MYSQLI_ASSOC);
} else {
    // Si la DB falla o está vacía, usa los datos de ejemplo
    $propiedades_a_usar = $propiedades_ejemplo;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏡 Agencia Felina</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <header>
        <h1>Agencia Inmobiliaria "El Cojín Perfecto" 🛋️</h1>
        <p>Encuentra tu rincón ideal para la siesta.</p>
        <nav>
            <a href="#inicio">Inicio</a>
            <a href="#destacadas">Destacadas</a>
            <a href="#contacto">Contacto</a>
        </nav>
    </header>

    <main id="inicio">
        
        <h2>✨ Propiedades Destacadas</h2>
        
        <section id="rotador-propiedades">
            
            <?php 
            // 💡 Comentario breve para el parcial: La lógica PHP/DB carga un array 
            // de propiedades desde MySQL para que el rotador JS pueda usar los datos.
            
            // Convertimos el array de PHP a una variable JavaScript para que el rotador.js pueda usarla
            echo "<script>const DATOS_PROPIEDADES = " . json_encode($propiedades_a_usar) . ";</script>";

            // Usamos la primera propiedad del array para llenar el HTML estático inicial (Fallback visual)
            $primera_propiedad = $propiedades_a_usar[0];
            ?>
            
            <div class="propiedad-item" data-index="0">
                <img id="rotador-imagen" src="<?php echo $primera_propiedad['imagen_url']; ?>" alt="Propiedad Destacada">
                <div class="propiedad-info">
                    <h3 id="rotador-titulo"><?php echo $primera_propiedad['titulo']; ?></h3>
                    <p id="rotador-descripcion"><?php echo $primera_propiedad['descripcion_corta']; ?></p>
                    <p class="precio" id="rotador-precio"><?php echo $primera_propiedad['precio']; ?></p>
                    <button>Ver Detalles</button>
                </div>
            </div>
            
            <div class="controles">
                <button id="anterior">← Anterior</button>
                <button id="siguiente">Siguiente →</button>
            </div>
            
        </section>

    <section id="contacto">
        <h2>📞 Contáctanos para más ofertas</h2>
        
        <?php 
        // Muestra el estado del formulario después de enviar
        if (isset($mensaje_estado)) {
            echo "<p style='padding: 10px; border: 1px dashed #007bff; font-weight: bold;'>$mensaje_estado</p>";
        }
        ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"> 
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="mensaje">Mensaje (¿Qué buscas?):</label>
            <textarea id="mensaje" name="mensaje" required></textarea>
            
            <button type="submit">Enviar Consulta</button>
        </form>
    </section>
        
    </main>

    <footer>
        <p>&copy; 2025 El Cojín Perfecto. Haciendo feliz a los michis.</p>
    </footer>
    
    <?php 
    // 5. Cerrar la conexión usando la función
    desconectar();
    ?>
    
    <script src="js/rotador.js"></script>
</body>
</html>