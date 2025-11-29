<?php
// Incluir configuración de la base de datos
include_once '../config/database.php';

// Obtener profesores de Jiu Jitsu
$database = new Database();
$db = $database->getConnection();

$query_profesores = "SELECT * FROM profesores WHERE activo = 1 AND especialidad LIKE '%Jiu Jitsu%' ORDER BY orden ASC";
$stmt_profesores = $db->prepare($query_profesores);
$stmt_profesores->execute();
$profesores = $stmt_profesores->fetchAll(PDO::FETCH_ASSOC);

// Obtener horarios de Jiu Jitsu
$query_horarios = "SELECT * FROM horarios WHERE activo = 1 AND disciplina = 'Jiu Jitsu' 
                   ORDER BY 
                     CASE dia_semana 
                       WHEN 'Lunes' THEN 1
                       WHEN 'Martes' THEN 2
                       WHEN 'Miércoles' THEN 3
                       WHEN 'Jueves' THEN 4
                       WHEN 'Viernes' THEN 5
                       WHEN 'Sábado' THEN 6
                       WHEN 'Domingo' THEN 7
                     END,
                     hora_inicio";
$stmt_horarios = $db->prepare($query_horarios);
$stmt_horarios->execute();
$horarios = $stmt_horarios->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jiu Jitsu - Academia del Lago</title>
    <meta name="description" content="Clases de Jiu Jitsu en Santa Fe. Arte marcial japonés focalizado en defensa personal. Academia del Lago en Club Náutico El Quilla.">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- ===== HEADER ===== -->
    <header class="header">
        <div class="container">
            <a href="../index.php" class="logo-container">
                <img src="../images/logo.jpg" alt="Academia del Lago" class="logo-img">
            </a>
            <nav>
                <ul class="nav-links">
                    <li><a href="../index.php">Inicio</a></li>
                    <li><a href="../index.php#disciplinas">Disciplinas</a></li>
                    <li><a href="../index.php#profesores">Instructores</a></li>
                    <li><a href="../index.php#horarios">Horarios</a></li>
                    <li><a href="../index.php#galeria">Galería</a></li>
                    <li><a href="../index.php#contacto">Contacto</a></li>
                    <li>
                        <a href="https://instagram.com/academiadelago" target="_blank" class="instagram-link">
                            <i class="fab fa-instagram instagram-icon"></i>
                            Instagram
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- ===== HERO JIU JITSU ===== -->
    <section class="disciplina-hero" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../images/jiujitsu-hero.jpg');">
        <div class="container">
            <div class="hero-content">
                <div class="disciplina-badge">
                    <span>🥋 Jiu Jitsu</span>
                </div>
                <h1>El Arte Suave</h1>
                <p class="hero-description">
                    Defensa personal sin armas • Técnicas de sumisión • Arte marcial tradicional
                </p>
                <a href="#inscripcion" class="cta-button">
                    <i class="fas fa-user-plus"></i>
                    Clase de Prueba Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- ===== SOBRE JIU JITSU ===== -->
    <section class="sobre-disciplina">
        <div class="container">
            <div class="disciplina-grid">
                <div class="disciplina-info">
                    <h2>¿Qué es el Jiu Jitsu?</h2>
                    <p>
                        El <strong>Jiu Jitsu</strong> (arte suave) es un arte marcial japonés tradicional 
                        que se centra en la defensa personal sin armas. Utiliza técnicas de luxación articular, 
                        lanzamientos, estrangulaciones y sumisiones para neutralizar a un oponente.
                    </p>
                    
                    <h3>Beneficios del Jiu Jitsu</h3>
                    <div class="beneficios-grid">
                        <div class="beneficio-card">
                            <i class="fas fa-shield-alt"></i>
                            <h4>Defensa Personal</h4>
                            <p>Técnicas efectivas para situaciones reales</p>
                        </div>
                        <div class="beneficio-card">
                            <i class="fas fa-brain"></i>
                            <h4>Desarrollo Mental</h4>
                            <p>Mejora la concentración y toma de decisiones</p>
                        </div>
                        <div class="beneficio-card">
                            <i class="fas fa-heartbeat"></i>
                            <h4>Condición Física</h4>
                            <p>Fuerza, flexibilidad y resistencia cardiovascular</p>
                        </div>
                        <div class="beneficio-card">
                            <i class="fas fa-users"></i>
                            <h4>Comunidad</h4>
                            <p>Ambiente de respeto y camaradería</p>
                        </div>
                    </div>
                </div>
                
                <div class="disciplina-imagen">
                    <img src="../images/jiujitsu-technique.jpg" alt="Técnica de Jiu Jitsu">
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TÉCNICAS Y NIVELES ===== -->
    <section class="tecnicas" style="background: var(--gris-fondo); padding: 5rem 0;">
        <div class="container">
            <h2 class="section-title">Técnicas y Niveles</h2>
            <div class="tecnicas-grid">
                <div class="tecnica-categoria">
                    <h3><i class="fas fa-graduation-cap"></i> Principiante</h3>
                    <ul>
                        <li>Caídas básicas (Ukemi)</li>
                        <li>Posiciones fundamentales</li>
                        <li>Luxaciones básicas</li>
                        <li>Defensas contra agarres</li>
                    </ul>
                </div>
                <div class="tecnica-categoria">
                    <h3><i class="fas fa-medal"></i> Intermedio</h3>
                    <ul>
                        <li>Técnicas de sumisión</li>
                        <li>Transiciones entre posiciones</li>
                        <li>Estrangulaciones</li>
                        <li>Defensa contra golpes</li>
                    </ul>
                </div>
                <div class="tecnica-categoria">
                    <h3><i class="fas fa-trophy"></i> Avanzado</h3>
                    <ul>
                        <li>Técnicas combinadas</li>
                        <li>Defensa contra múltiples atacantes</li>
                        <li>Jiu Jitsu tradicional y moderno</li>
                        <li>Preparación para competencias</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== HORARIOS JIU JITSU ===== -->
    <section class="horarios-disciplina">
        <div class="container">
            <h2 class="section-title">Horarios de Jiu Jitsu</h2>
            <div class="horarios-grid">
                <?php if (count($horarios) > 0): ?>
                    <?php 
                    $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                    foreach ($dias_semana as $dia): 
                        $clases_dia = array_filter($horarios, function($horario) use ($dia) {
                            return $horario['dia_semana'] === $dia;
                        });
                        if (count($clases_dia) > 0): 
                    ?>
                    <div class="dia-card">
                        <h3><?php echo $dia; ?></h3>
                        <div class="clases-list">
                            <?php foreach ($clases_dia as $clase): ?>
                            <div class="clase-item">
                                <span class="hora"><?php echo date('H:i', strtotime($clase['hora_inicio'])); ?> - <?php echo date('H:i', strtotime($clase['hora_fin'])); ?></span>
                                <span class="nivel"><?php echo htmlspecialchars($clase['nivel']); ?></span>
                                <?php if (!empty($clase['profesor_id'])): ?>
                                <span class="profesor">Con instructor</span>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                <?php else: ?>
                    <div class="no-horarios">
                        <h3>Horarios en actualización</h3>
                        <p>Contactanos para conocer los horarios disponibles</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ===== INSTRUCTORES JIU JITSU ===== -->
    <section class="instructores-disciplina">
        <div class="container">
            <h2 class="section-title">Nuestros Instructores de Jiu Jitsu</h2>
            <div class="instructores-grid">
                <?php if (count($profesores) > 0): ?>
                    <?php foreach ($profesores as $profesor): ?>
                    <div class="instructor-card">
                        <div class="instructor-imagen">
                            <?php if (!empty($profesor['imagen'])): ?>
                                <img src="../uploads/<?php echo $profesor['imagen']; ?>" alt="<?php echo htmlspecialchars($profesor['nombre']); ?>">
                            <?php else: ?>
                                <div class="placeholder-imagen"><?php echo substr($profesor['nombre'], 0, 1); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="instructor-info">
                            <h3><?php echo htmlspecialchars($profesor['nombre']); ?></h3>
                            <p class="instructor-titulo"><?php echo htmlspecialchars($profesor['titulo']); ?></p>
                            <p class="instructor-experiencia"><?php echo htmlspecialchars($profesor['descripcion']); ?></p>
                            <div class="instructor-logros">
                                <span class="logro"><?php echo htmlspecialchars($profesor['especialidad']); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-instructores">
                        <h3>Instructores Calificados</h3>
                        <p>Contamos con instructores certificados y con amplia experiencia en Jiu Jitsu tradicional y moderno.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ===== INSCRIPCIÓN ===== -->
    <section id="inscripcion" class="inscripcion-disciplina" style="background: var(--azul-principal); color: white; padding: 5rem 0;">
        <div class="container">
            <div class="inscripcion-content">
                <div class="inscripcion-info">
                    <h2>¿Listo para comenzar?</h2>
                    <p>Ofrecemos <strong>clase de prueba totalmente gratis</strong> para que experimentes el Jiu Jitsu sin compromiso.</p>
                    <div class="inscripcion-beneficios">
                        <div class="beneficio">
                            <i class="fas fa-check-circle"></i>
                            <span>Equipo de préstamo incluido</span>
                        </div>
                        <div class="beneficio">
                            <i class="fas fa-check-circle"></i>
                            <span>No se necesita experiencia</span>
                        </div>
                        <div class="beneficio">
                            <i class="fas fa-check-circle"></i>
                            <span>Para todas las edades</span>
                        </div>
                    </div>
                </div>
                <div class="inscripcion-form">
                    <h3>Solicita tu clase de prueba</h3>
                    <form action="../php/procesar_contacto.php" method="POST">
                        <input type="hidden" name="disciplina" value="Jiu Jitsu">
                        <div class="form-group">
                            <input type="text" name="nombre" placeholder="Nombre completo" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" name="telefono" placeholder="Teléfono">
                        </div>
                        <div class="form-group">
                            <select name="nivel_interes">
                                <option value="">Nivel de interés</option>
                                <option value="Principiante">Principiante</option>
                                <option value="Intermedio">Intermedio</option>
                                <option value="Avanzado">Avanzado</option>
                            </select>
                        </div>
                        <button type="submit" class="cta-button" style="background: var(--amarillo); color: var(--negro);">
                            <i class="fas fa-paper-plane"></i>
                            Solicitar Clase de Prueba
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Academia del Lago</h3>
                    <p>Jiu Jitsu tradicional y moderno en Santa Fe Capital.</p>
                </div>
                <div class="footer-section">
                    <h3>Otras Disciplinas</h3>
                    <ul>
                        <li><a href="judo.php">Judo</a></li>
                        <li><a href="aikido.php">Aikido</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contacto</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Club Náutico El Quilla</li>
                        <li><i class="fas fa-phone"></i> +54 342 123-4567</li>
                        <li><i class="fas fa-envelope"></i> info@academiadelago.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Academia del Lago. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
