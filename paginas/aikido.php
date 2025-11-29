<?php
// Incluir configuración de la base de datos
include_once '../config/database.php';

// Obtener profesores de Aikido
$database = new Database();
$db = $database->getConnection();

$query_profesores = "SELECT * FROM profesores WHERE activo = 1 AND especialidad LIKE '%Aikido%' ORDER BY orden ASC";
$stmt_profesores = $db->prepare($query_profesores);
$stmt_profesores->execute();
$profesores = $stmt_profesores->fetchAll(PDO::FETCH_ASSOC);

// Obtener horarios de Aikido
$query_horarios = "SELECT * FROM horarios WHERE activo = 1 AND disciplina = 'Aikido' 
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
    <title>Aikido - Academia del Lago</title>
    <meta name="description" content="Clases de Aikido en Santa Fe. Arte marcial moderno de armonía y no resistencia. Academia del Lago en Club Náutico El Quilla.">
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

    <!-- ===== HERO AIKIDO ===== -->
    <section class="disciplina-hero" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../images/aikido-hero.jpg');">
        <div class="container">
            <div class="hero-content">
                <div class="disciplina-badge">
                    <span>🥋 Aikido</span>
                </div>
                <h1>El Camino de la Armonía</h1>
                <p class="hero-description">
                    Arte marcial de la no resistencia • Técnicas circulares • Desarrollo espiritual
                </p>
                <a href="#inscripcion" class="cta-button">
                    <i class="fas fa-user-plus"></i>
                    Clase de Prueba Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- ===== SOBRE AIKIDO ===== -->
    <section class="sobre-disciplina">
        <div class="container">
            <div class="disciplina-grid">
                <div class="disciplina-info">
                    <h2>¿Qué es el Aikido?</h2>
                    <p>
                        El <strong>Aikido</strong> (el camino de la energía unificada) es un arte marcial moderno 
                        japonés desarrollado por Morihei Ueshiba. Se caracteriza por su filosofía de no resistencia 
                        y el uso de la fuerza del oponente mediante movimientos circulares y fluidos.
                    </p>
                    
                    <h3>Filosofía del Aikido</h3>
                    <div class="beneficios-grid">
                        <div class="beneficio-card">
                            <i class="fas fa-peace"></i>
                            <h4>No Resistencia</h4>
                            <p>Usar la energía del oponente en su contra</p>
                        </div>
                        <div class="beneficio-card">
                            <i class="fas fa-infinity"></i>
                            <h4>Movimiento Circular</h4>
                            <p>Técnicas fluidas y continuas</p>
                        </div>
                        <div class="beneficio-card">
                            <i class="fas fa-heart"></i>
                            <h4>Desarrollo Espiritual</h4>
                            <p>Armonía entre cuerpo y mente</p>
                        </div>
                        <div class="beneficio-card">
                            <i class="fas fa-hands"></i>
                            <h4>Resolución Pacífica</h4>
                            <p>Neutralizar sin dañar al oponente</p>
                        </div>
                    </div>
                </div>
                
                <div class="disciplina-imagen">
                    <img src="../images/aikido-technique.jpg" alt="Técnica de Aikido">
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PRINCIPIOS AIKIDO ===== -->
    <section class="principios" style="background: var(--gris-fondo); padding: 5rem 0;">
        <div class="container">
            <h2 class="section-title">Principios Fundamentales</h2>
            <div class="principios-grid">
                <div class="principio-card">
                    <div class="principio-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Irimi</h3>
                    <p><strong>Entrada</strong><br>
                    Movimiento de entrada hacia el atacante para neutralizar su ataque desde el inicio.</p>
                </div>
                <div class="principio-card">
                    <div class="principio-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h3>Tenkan</h3>
                    <p><strong>Giro</strong><br>
                    Movimiento de giro que redirige la fuerza del atacante y desequilibra.</p>
                </div>
                <div class="principio-card">
                    <div class="principio-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <h3>Kuzushi</h3>
                    <p><strong>Desequilibrio</strong><br>
                    Técnica para romper el equilibrio del oponente antes de aplicar una técnica.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TÉCNICAS AIKIDO ===== -->
    <section class="tecnicas-aikido">
        <div class="container">
            <h2 class="section-title">Técnicas de Aikido</h2>
            <div class="tecnicas-columnas">
                <div class="columna-tecnicas">
                    <h3><i class="fas fa-hands"></i> Técnicas de Mano</h3>
                    <ul>
                        <li><strong>Ikkyo</strong> - Primera enseñanza</li>
                        <li><strong>Nikyo</strong> - Segunda enseñanza</li>
                        <li><strong>Sankyo</strong> - Tercera enseñanza</li>
                        <li><strong>Yonkyo</strong> - Cuarta enseñanza</li>
                        <li><strong>Gokyo</strong> - Quinta enseñanza</li>
                    </ul>
                </div>
                <div class="columna-tecnicas">
                    <h3><i class="fas fa-user-friends"></i> Técnicas de Proyección</h3>
                    <ul>
                        <li><strong>Irimi Nage</strong> - Proyección por entrada</li>
                        <li><strong>Kaiten Nage</strong> - Proyección por giro</li>
                        <li><strong>Koshi Nage</strong> - Proyección de cadera</li>
                        <li><strong>Kokyu Nage</strong> - Proyección de respiración</li>
                    </ul>
                </div>
                <div class="columna-tecnicas">
                    <h3><i class="fas fa-toolbox"></i> Técnicas con Armas</h3>
                    <ul>
                        <li><strong>Bokken</strong> - Espada de madera</li>
                        <li><strong>Jo</strong> - Bastón medio</li>
                        <li><strong>Tanto</strong> - Cuchillo de madera</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== HORARIOS AIKIDO ===== -->
    <section class="horarios-disciplina">
        <div class="container">
            <h2 class="section-title">Horarios de Aikido</h2>
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

    <!-- ===== INSTRUCTORES AIKIDO ===== -->
    <section class="instructores-disciplina">
        <div class="container">
            <h2 class="section-title">Nuestros Instructores de Aikido</h2>
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
                        <h3>Instructores de Aikido Tradicional</h3>
                        <p>Contamos con instructores formados en la tradición del Aikido del Fundador Morihei Ueshiba.</p>
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
                    <h2>Comienza tu Camino en el Aikido</h2>
                    <p>Ofrecemos <strong>clase de prueba totalmente gratis</strong> para que experimentes la armonía del Aikido.</p>
                    <div class="inscripcion-beneficios">
                        <div class="beneficio">
                            <i class="fas fa-check-circle"></i>
                            <span>Keikogi de préstamo incluido</span>
                        </div>
                        <div class="beneficio">
                            <i class="fas fa-check-circle"></i>
                            <span>Enfoque en desarrollo personal</span>
                        </div>
                        <div class="beneficio">
                            <i class="fas fa-check-circle"></i>
                            <span>Para todas las edades y condiciones</span>
                        </div>
                    </div>
                </div>
                <div class="inscripcion-form">
                    <h3>Solicita tu clase de prueba</h3>
                    <form action="../php/procesar_contacto.php" method="POST">
                        <input type="hidden" name="disciplina" value="Aikido">
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
                            <textarea name="motivacion" placeholder="¿Qué te motiva a practicar Aikido?" rows="3"></textarea>
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
                    <p>Aikido tradicional en Santa Fe Capital.</p>
                </div>
                <div class="footer-section">
                    <h3>Otras Disciplinas</h3>
                    <ul>
                        <li><a href="jiujitsu.php">Jiu Jitsu</a></li>
                        <li><a href="judo.php">Judo</a></li>
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
