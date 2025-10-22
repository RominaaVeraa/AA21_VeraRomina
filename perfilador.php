<?php

// AA21 - Primer Aplicativo con PHP: "Perfilador PHP" - Vera Romina

// PASO 1: Primero configuré el reporte de errores para facilitar el debugging durante el desarrollo
// Esto me permite ver cualquier error de PHP mientras trabajo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// PASO 2: Luego declaré las variables iniciales que necesitaré reutilizar en el formulario
// Esto es crucial para mantener los valores cuando hay errores de validación (re-render)
$nombre = '';
$edad = '';
$hobby = '';
$errores = [];
$perfil = '';
$mensaje = '';
$hayResultado = false;

// PASO 3: Ahora proceso el formulario cuando se envía por POST
// Primero verifico que sea una petición POST para evitar procesar en la carga inicial
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // PASO 4: Apliqué sanitización con htmlspecialchars() para prevenir ataques XSS
    // Esto convierte caracteres especiales como <, >, & en entidades HTML seguras
    // El trim() elimina espacios en blanco al inicio y final
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
    $edad   = htmlspecialchars(trim($_POST['edad']   ?? ''), ENT_QUOTES, 'UTF-8');
    $hobby  = htmlspecialchars(trim($_POST['hobby']  ?? ''), ENT_QUOTES, 'UTF-8');

    // PASO 5: Implementé validación del lado del servidor (¡NUNCA confiar solo en el cliente!)
    // Primero valido el nombre: debe existir y tener al menos 2 caracteres
    if ($nombre === '' || mb_strlen($nombre) < 2) {
        $errores['nombre'] = 'Nombre obligatorio (mínimo 2 caracteres).';
    }
    
    // Luego valido la edad: debe ser un número entero válido
    if ($edad === '' || !ctype_digit($edad)) {
        $errores['edad'] = 'Ingresá una edad válida (entero).';
    } else {
        // Si es un número, verifico que esté en un rango lógico
        $n = (int)$edad;
        if ($n < 0 || $n > 120) {
            $errores['edad'] = 'La edad debe estar entre 0 y 120.';
        }
    }
    
    // Finalmente valido el hobby: debe tener al menos 3 caracteres
    if ($hobby === '' || mb_strlen($hobby) < 3) {
        $errores['hobby'] = 'Hobby obligatorio (mínimo 3 caracteres).';
    }

    // PASO 6: Si no hay errores, aplico la lógica de negocio con estructuras if/else
    // Aquí personalizo el mensaje según el rango de edad del usuario
    if (!$errores) {
        $hayResultado = true;
        $n = (int)$edad;
        
        // Primero evaluó si es menor de 18 años
        if ($n < 18) {
            $perfil = 'Perfil en Desarrollo';
            $mensaje = '¡Tenés mucho por descubrir! Explorá y aprendé con tu hobby.';
        } 
        // Luego si está entre 18 y 29 años
        elseif ($n < 30) {
            $perfil = 'Perfil Joven Pro';
            $mensaje = 'Energía y crecimiento: hacé de tu hobby un proyecto desafiante.';
        } 
        // Después si está entre 30 y 59 años
        elseif ($n < 60) {
            $perfil = 'Perfil Profesional';
            $mensaje = 'Experiencia en marcha: equilibrá objetivos y pasión por tu hobby.';
        } 
        // Finalmente, 60 años o más
        else {
            $perfil = 'Perfil Senior';
            $mensaje = 'Sabiduría activa: compartí experiencia y disfrutá a tu ritmo.';
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>AA21 — Perfilador PHP</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    /* DISEÑO CSS: Primero definí las variables de color con paleta rosada elegante */
    /* Elegí tonos suaves de rosa, coral y lavanda para crear una interfaz cálida y moderna */
    :root {
      --bg: linear-gradient(135deg, #ffeef8 0%, #fff5f7 50%, #ffe8f0 100%);
      --card: #ffffff;
      --text: #2d1b2e;
      --muted: #8b6f8e;
      --border: #f3d4e5;
      --danger: #d63384;
      --ok: #198754;
      --primary: #d63384;
      --primary-light: #f8c3dc;
      --secondary: #e685b5;
      --accent: #9d4edd;
      --shadow: rgba(214, 51, 132, 0.1);
    }
    
    /* Luego apliqué el reset básico para consistencia entre navegadores */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    /* Después configuré el body con el fondo gradiente y tipografía */
    body {
      margin: 0;
      padding: 32px 24px;
      background: var(--bg);
      background-attachment: fixed;
      color: var(--text);
      font-family: 'Segoe UI', system-ui, -apple-system, Roboto, Arial, sans-serif;
      line-height: 1.6;
      min-height: 100vh;
    }
    
    /* Apliqué estilos a las tarjetas principales con sombras suaves rosadas */
    header, footer, .card {
      background: var(--card);
      border: 2px solid var(--border);
      border-radius: 20px;
      padding: 28px;
      box-shadow: 0 8px 32px var(--shadow), 0 2px 8px rgba(0,0,0,0.05);
      backdrop-filter: blur(10px);
    }
    
    /* Después estilicé los encabezados con degradado de texto rosado */
    h1 {
      margin: 0 0 12px;
      font-size: 2.2em;
      font-weight: 800;
      background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    h2 {
      margin: 0 0 20px;
      font-size: 1.5em;
      color: var(--primary);
      border-bottom: 3px solid var(--primary-light);
      padding-bottom: 10px;
    }
    
    h3 {
      color: var(--accent);
      font-size: 1.3em;
    }
    
    /* Luego diseñé el texto secundario con color suave */
    .lead {
      color: var(--muted);
      margin: 0;
      font-size: 1.1em;
    }
    
    /* Apliqué un grid responsivo de dos columnas para el layout principal */
    .grid {
      display: grid;
      gap: 24px;
      grid-template-columns: 1fr 1fr;
      margin-top: 28px;
    }
    
    /* Después estilicé los labels con peso medio y espaciado */
    label {
      display: block;
      margin-bottom: 18px;
      font-weight: 600;
      color: var(--text);
      font-size: 0.95em;
    }
    
    /* Luego diseñé los inputs con bordes rosados y efectos de focus */
    input {
      width: 100%;
      padding: 12px 16px;
      margin-top: 6px;
      border: 2px solid var(--border);
      border-radius: 12px;
      font: inherit;
      font-size: 0.95em;
      transition: all 0.3s ease;
      background: #fefefe;
    }
    
    /* Apliqué efectos interactivos en focus para mejor UX */
    input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(214, 51, 132, 0.1);
      background: white;
    }
    
    /* Después creé el contenedor de acciones con flexbox */
    .actions {
      display: flex;
      gap: 12px;
      margin-top: 24px;
    }
    
    /* Luego diseñé los botones con gradientes y sombras sutiles */
    button {
      padding: 12px 24px;
      border: 0;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      color: white;
      font-weight: 700;
      cursor: pointer;
      font-size: 0.95em;
      box-shadow: 0 4px 12px rgba(214, 51, 132, 0.3);
      transition: all 0.3s ease;
    }
    
    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(214, 51, 132, 0.4);
    }
    
    /* Apliqué un estilo diferente al botón reset con tonos grises rosados */
    button[type=reset] {
      background: linear-gradient(135deg, #f3d4e5 0%, #e8c4d8 100%);
      color: var(--text);
      box-shadow: 0 4px 12px rgba(139, 111, 142, 0.2);
    }
    
    button[type=reset]:hover {
      box-shadow: 0 6px 20px rgba(139, 111, 142, 0.3);
    }
    
    /* Después estilicé los mensajes de error en rojo rosado */
    .error {
      color: var(--danger);
      font-size: 0.85em;
      display: block;
      margin-top: 6px;
      font-weight: 500;
    }
    
    /* Luego creé badges decorativos con fondo rosado claro */
    .badge {
      display: inline-block;
      margin-left: 12px;
      font-size: 0.8em;
      padding: 6px 14px;
      border: 2px solid var(--primary-light);
      border-radius: 20px;
      background: linear-gradient(135deg, #fff0f6 0%, #ffe8f0 100%);
      color: var(--primary);
      font-weight: 600;
    }
    
    /* Apliqué un borde lateral decorativo a la sección de perfil */
    .perfil {
      border-left: 5px solid var(--primary);
      padding-left: 16px;
      margin-bottom: 16px;
      background: linear-gradient(90deg, rgba(214, 51, 132, 0.05) 0%, transparent 100%);
      padding-top: 8px;
      padding-bottom: 8px;
      border-radius: 0 12px 12px 0;
    }
    
    /* Después definí clases de utilidad para colores y tamaños */
    .ok {
      color: var(--ok);
      background: #f0fdf4;
      padding: 12px;
      border-radius: 10px;
      border-left: 4px solid var(--ok);
    }
    
    .muted {
      color: var(--muted);
    }
    
    .small {
      font-size: 0.85em;
    }
    
    /* Luego estilicé las listas quitando bullets por defecto */
    .list {
      list-style: none;
      margin: 0 0 16px 0;
      padding: 0;
    }
    
    .list li {
      padding: 8px 0;
      border-bottom: 1px solid var(--border);
    }
    
    .list li:last-child {
      border-bottom: none;
    }
    
    /* Apliqué estilos al código inline con fondo rosado */
    code {
      background: #fff0f6;
      padding: 3px 8px;
      border-radius: 6px;
      font-family: 'Courier New', monospace;
      font-size: 0.9em;
      color: var(--primary);
      border: 1px solid var(--border);
    }
    
    /* Después estilicé el footer con lista de conceptos */
    footer ul {
      margin-top: 16px;
      padding-left: 24px;
    }
    
    footer li {
      margin: 8px 0;
      color: var(--muted);
    }
    
    /* Finalmente apliqué media query para diseño responsive en móviles */
    @media (max-width: 900px) {
      .grid {
        grid-template-columns: 1fr;
      }
      
      h1 {
        font-size: 1.8em;
      }
      
      body {
        padding: 20px 16px;
      }
    }
  </style>
</head>
<body>
  <!-- ESTRUCTURA HTML: Primero creé el header con título y descripción -->
  <header>
    <h1>✨ Perfilador PHP</h1>
    <p class="lead">Completá el formulario y generá tu tarjeta de presentación personalizada.</p>
  </header>

  <main class="grid">
    <!-- FORMULARIO: Luego construí el formulario que envía datos por POST a este mismo archivo -->
    <section class="card">
      <h2>Formulario</h2>
      <!-- Uso PHP_SELF con htmlspecialchars para prevenir inyección de código en la URL -->
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post" novalidate>
        
        <!-- Campo Nombre: Apliqué el atributo value con PHP para mantener el dato ingresado -->
        <label>Nombre
          <input type="text" name="nombre" value="<?php echo $nombre; ?>" required minlength="2" autocomplete="name">
          <!-- Si hay error, lo muestro debajo del campo usando condicional de PHP -->
          <?php if(isset($errores['nombre'])): ?>
            <small class="error"> <?php echo $errores['nombre']; ?></small>
          <?php endif; ?>
        </label>

        <!-- Campo Edad: Similar al anterior pero para números -->
        <label>Edad
          <input type="number" name="edad" value="<?php echo $edad; ?>" min="0" max="120" step="1" required>
          <?php if(isset($errores['edad'])): ?>
            <small class="error"> <?php echo $errores['edad']; ?></small>
          <?php endif; ?>
        </label>

        <!-- Campo Hobby: Último campo del formulario con su validación -->
        <label>Hobby
          <input type="text" name="hobby" value="<?php echo $hobby; ?>" required minlength="3" autocomplete="off">
          <?php if(isset($errores['hobby'])): ?>
            <small class="error"> <?php echo $errores['hobby']; ?></small>
          <?php endif; ?>
        </label>

        <!-- Botones de acción: Enviar y Limpiar -->
        <div class="actions">
          <button type="submit" id="btnEnviar">Generar Tarjeta</button>
          <button type="reset" onclick="location.href='<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>'">Limpiar</button>
        </div>
      </form>
    </section>

    <!-- RESULTADO: Después creé la sección que muestra el perfil generado -->
    <section class="card">
      <h2>Resultado</h2>
      
      <!-- Uso condicional PHP para mostrar resultado solo si existe -->
      <?php if ($hayResultado): ?>
        <!-- Primero muestro el nombre y el tipo de perfil asignado -->
        <div class="perfil">
          <h3 style="margin:0 0 8px 0;">
            <?php echo $nombre; ?> 
            <span class="badge"><?php echo $perfil; ?></span>
          </h3>
        </div>
        
        <!-- Luego listo los datos ingresados por el usuario -->
        <ul class="list">
          <li><strong>Edad:</strong> <?php echo (int)$edad; ?> años</li>
          <li><strong>Hobby:</strong> <?php echo $hobby; ?></li>
        </ul>
        
        <!-- Después muestro el mensaje personalizado según la edad -->
        <p class="ok"><strong>Mensaje:</strong> <?php echo $mensaje; ?></p>
        
        <!-- Finalmente agrego una nota aclaratoria sobre el origen del contenido -->
        <p class="muted small">Contenido generado dinámicamente por PHP del lado del servidor.</p>
        
      <?php else: ?>
        <!-- Si no hay resultado, muestro instrucciones iniciales -->
        <p class="muted">Completá el formulario y presioná <strong>Generar Tarjeta</strong> para ver tu perfil personalizado.</p>
      <?php endif; ?>
    </section>
  </main>

  <!-- FOOTER: Finalmente agregué el footer con conceptos clave del proyecto -->
  <footer style="margin-top: 28px">
    <strong style="color: var(--primary); font-size: 1.1em;">Conceptos clave aprendidos</strong>
    <ul>
      <li><strong>Cliente-Servidor:</strong> PHP procesa en el servidor y devuelve HTML al navegador.</li>
      <li><strong>Superglobales:</strong> Usamos <code>$_POST</code> para recibir datos del formulario (vs <code>$_GET</code> en URL).</li>
      <li><strong>PHP embebido:</strong> Insertamos código PHP con <code>&lt;?php ... ?&gt;</code> dentro del HTML.</li>
      <li><strong>Seguridad XSS:</strong> Aplicamos <code>htmlspecialchars()</code> para prevenir inyección de código malicioso.</li>
      <li><strong>Validación servidor:</strong> Nunca confiar en validación del cliente, siempre validar en PHP.</li>
      <li><strong>Lógica condicional:</strong> Usamos <code>if/elseif/else</code> para personalizar el mensaje según edad.</li>
    </ul>
  </footer>

  <!-- JAVASCRIPT: Por último agregué JS para mejorar la experiencia de usuario -->
  <script>
    // MEJORA UX: Primero esperé a que el DOM esté listo para manipularlo
    document.addEventListener('DOMContentLoaded', () => {
      // Luego obtuve referencias al formulario y botón de envío
      const form = document.querySelector('form');
      const btn = document.getElementById('btnEnviar');
      
      // Verifico que existan antes de agregar listeners
      if (!form || !btn) return;
      
      // Después agregué un listener para prevenir dobles envíos
      // Esto mejora la UX deshabilitando temporalmente el botón
      form.addEventListener('submit', () => {
        btn.disabled = true;
        const textoOriginal = btn.textContent;
        btn.textContent = 'Enviando...';
        
        // Finalmente reactivo el botón después de 1.2 segundos
        // Esto da tiempo para que el servidor procese y recargue la página
        setTimeout(() => { 
          btn.disabled = false; 
          btn.textContent = textoOriginal; 
        }, 1200);
      });
    });
  </script>
</body>
</html>