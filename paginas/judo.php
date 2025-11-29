<?php
// Incluir configuración de la base de datos
include_once '../config/database.php';

// Obtener profesores de Judo
$database = new Database();
$db = $database->getConnection();

$query_profesores = "SELECT * FROM profesores WHERE activo = 1 AND especialidad LIKE '%Judo%' ORDER BY orden ASC";
$stmt_profesores = $db->prepare($query_profesores);
$stmt_profesores->execute();
$profesores = $stmt_profesores->fetchAll(PDO::FETCH_ASSOC);

// Obtener horarios de Judo
$query_horarios = "SELECT * FROM horarios WHERE activo = 1 AND disciplina = 'Judo' 
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
    <title>Judo - Academia del Lago</title>
    <meta name="description" content="Clases de Judo en Santa Fe. Deporte olímpico y arte marcial tradicional. Academia del Lago en Club Náutico El Quilla.">
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

    <!-- ===== HERO JUDO ===== -->
    <section class="disciplina-hero" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../images/judo-hero.jpg');">
        <div class="container">
            <div class="hero-content">
                <div class="disciplina-badge">
                    <span>🥋 Judo</span>
                </div>
                <h1>El Camino de la Suavidad</h1>
                <p class="hero-description">
                    Deporte olímpico • Proyecciones y inmovilizaciones • Máxima eficiencia
                </p>
                <a href="#inscripcion" class="cta-button">
                    <i class="fas fa-user-plus"></i>
                    Clase de Prueba Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- ===== SOBRE JUDO ===== -->
    <section class="sobre-disciplina">
        <div class="container">
            <div class="disciplina-grid">
                <div class="disciplina-info">
                    <h2>¿Qué es el Judo?</h2>
                    <p>
                        El <strong>Judo</strong> (camino de la suavidad) es un arte marcial y deporte olímpico 
                        creado en Japón. Se basa en utilizar la fuerza del oponente en su contra mediante 
                        proyecciones, inmovilizaciones y sumisiones.
                    </p>
                    
                    <h3>Beneficios del Judo</h3>
                    <div class="beneficios-grid">
                        <div class="beneficio-card">
                            <i class="fas fa-medal"></i>
                            <h4>Deporte Olímpico</h4>
                            <p>Disciplina reconocida internacionalmente</p>
                        </div>
                        <div class="beneficio-card">
                            <i class="fas fa-balance-scale"></i>
                            <h4>Equilibrio Mental</h4>
                            <p>Desarrollo de estrategia y autocontrol</p>
                        </div>
                        <div class="beneficio-card">
                            <i class="fas fa-running"></i>
                            <h4>Condición Física</h4>
                            <p>Fuerza, agilidad y coordinación</p>
                        </div>
                        <div class="beneficio-card">
                            <i class="fas fa-hands-helping"></i>
                            <h4>Respeto Mutuo</h4>
                            <p>Valores de cortesía y ayuda mutua</p>
                        </div>
                    </div>
                </div>
                
                <div class="disciplina-imagen">
                    <img src="../images/judo-technique.jpg" alt="Técnica de Judo">
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TÉCNICAS JUDO ===== -->
    <section class="tecnicas" style="background: var(--gris-fondo); padding: 5rem 0;">
        <div class="container">
            <h2 class="section-title">Técnicas de Judo</h2>
            <div class="tecnicas-grid">
                <div class="tecnica-categoria">
                    <h3><i class="fas fa-user-friends"></i> Nage Waza</h3>
                    <p><strong>Técnicas de proyección</strong></p>
                    <ul>
                        <li>O Soto Gari (Gran cosecha exterior)</li>
                        <li>Seoi Nage (Proyección de espalda)</li>
                        <li>Tai Otoshi (Cadera corporal)</li>
                        <li>Uchi Mata (Muslo interior)</li>
                    </ul>
                </div>
                <div class="tecnica-categoria">
                    <h3><i class="fas fa-lock"></i> Katame Waza</h3>
                    <p><strong>Técnicas de control</strong></p>
                    <ul>
                        <li>Osaekomi Waza (Inmovilizaciones)</li>
                        <li>Shime Waza (Estrangulaciones)</li>
                        <li>Kansetsu Waza (Luxaciones)</li>
                    </ul>
                </div>
                <div class="tecnica-categoria">
                    <h3><i class="fas fa-shield-alt"></i> Ukemi</h3>
                    <p><strong>Técnicas de caída</strong></p>
                    <ul>
                        <li>Mae Ukemi (Caída frontal)</li>
                        <li>Ushiro Ukemi (Caída posterior)</li>
                        <li>Yoko Ukemi (Caída lateral)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== HORARIOS JUDO ===== -->
    <section class="horarios-disciplina">
        <div class="container">
            <h2 class="section-title">Horarios de Judo</h2>
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

    <!-- ===== INSTRUCTORES JUDO ===== -->
    <section class="instructores-disciplina">
        <div class="container">
            <h2 class="section-title">Nuestros Instructores de Judo</h2>
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
                        <h3>Instructores Certificados</h3>
                        <p>Contamos con instructores de Judo con certificación internacional y experiencia en competencias.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ===== GRADOS Y CINTURONES ===== -->
    <section class="grados" style="background: var(--azul-oscuro); color: white; padding: 5rem 0;">
        <div class="container">
            <h2 class="section-title" style="color: white;">Sistema de Grados</h2>
            <div class="grados-grid">
                <div class="grado-categoria">
                    <h3>Kyu (Alumnos)</h3>
                    <div class="cinturones">
                        <div class="cinturon blanco">6° Kyu</div>
                        <div class="cinturon amarillo">5° Kyu</div>
                        <div class="cinturon naranja">4° Kyu</div>
                        <div class="cinturon verde">3° Kyu</div>
                        <div class="cinturon azul">2° Kyu</div>
                        <div class="cinturon marron">1° Kyu</div>
                    </div>
                </div>
                <div class="grado-categoria">
                    <h3>Dan (Maestros)</h3>
                    <div class="cinturones">
                        <div class="cinturon negro">1° - 5° Dan</div>
                        <div class="cinturon rojiblanco">6° - 8° Dan</div>
                        <div class="cinturon rojo">9° - 10° Dan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== INSCRIPCIÓN ===== -->
    <section id="inscripcion" class="inscripcion-disciplina" style="background: var(--azul-principal); color: white; padding: 5rem 0;">
        <div class="container">
            <div class="inscripcion-content">
                <div class="inscripcion-info">
                    <h2>¿Listo para el Tatami?</h2>
                    <p>Ofrecemos <strong>clase de prueba totalmente gratis</strong> para que experimentes el Judo.</p>
                    <div class="inscripcion-beneficios">
                        <div class="beneficio">
                            <i class="fas fa-check-circle"></i>
                            <span>Judogi de préstamo incluido</span>
                        </div>
                        <div class="beneficio">
                            <i class="fas fa-check-circle"></i>
                            <span>Para niños, jóvenes y adultos</span>
                        </div>
                        <div class="beneficio">
                            <i class="fas fa-check-circle"></i>
                            <span>Preparación para competencias</span>
                        </div>
                    </div>
                </div>
                <div class="inscripcion-form">
                    <h3>Solicita tu clase de prueba</h3>
                    <form action="../php/procesar_contacto.php" method="POST">
                        <input type="hidden" name="disciplina" value="Judo">
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
                            <select name="edad">
                                <option value="">Selecciona tu edad</option>
                                <option value="Niño (6-12)">Niño (6-12 años)</option>
                                <option value="Joven (13-17)">Joven (13-17 años)</option>
                                <option value="Adulto (18+)">Adulto (18+ años)</option>
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
                    <p>Judo tradicional y deportivo en Santa Fe Capital.</p>
                </div>
                <div class="footer-section">
                    <h3>Otras Disciplinas</h3>
                    <ul>
                        <li><a href="jiujitsu.php">Jiu Jitsu</a></li>
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
