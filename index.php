<?php
// Incluir configuración de la base de datos
include_once 'config/database.php';

// Obtener profesores desde la base de datos
$database = new Database();
$db = $database->getConnection();

$query_profesores = "SELECT * FROM profesores WHERE activo = 1 ORDER BY orden ASC, nombre ASC LIMIT 3";
$stmt_profesores = $db->prepare($query_profesores);
$stmt_profesores->execute();
$profesores = $stmt_profesores->fetchAll(PDO::FETCH_ASSOC);

// Obtener galería
$query_galeria = "SELECT * FROM galeria WHERE activo = 1 ORDER BY fecha_subida DESC LIMIT 6";
$stmt_galeria = $db->prepare($query_galeria);
$stmt_galeria->execute();
$galeria = $stmt_galeria->fetchAll(PDO::FETCH_ASSOC);

// Obtener horarios - CONSULTA CORREGIDA PARA SQLite
$query_horarios = "SELECT * FROM horarios h 
                   LEFT JOIN profesores p ON h.profesor_id = p.id 
                   WHERE h.activo = 1 
                   ORDER BY 
                     CASE h.dia_semana 
                       WHEN 'Lunes' THEN 1
                       WHEN 'Martes' THEN 2
                       WHEN 'Miércoles' THEN 3
                       WHEN 'Jueves' THEN 4
                       WHEN 'Viernes' THEN 5
                       WHEN 'Sábado' THEN 6
                       WHEN 'Domingo' THEN 7
                     END,
                     h.hora_inicio";
$stmt_horarios = $db->prepare($query_horarios);
$stmt_horarios->execute();
$horarios = $stmt_horarios->fetchAll(PDO::FETCH_ASSOC);

// Agrupar horarios por día
$horarios_por_dia = [];
foreach ($horarios as $horario) {
    $horarios_por_dia[$horario['dia_semana']][] = $horario;
}

// Definir disciplinas con sus imágenes
$disciplinas = [
    [
        'nombre' => 'Jiu Jitsu',
        'descripcion' => 'Arte marcial japonés focalizado en la defensa personal sin armas. Técnicas de luxación, lanzamiento y estrangulación.',
        'imagen' => 'images/jiujitsu.jpg',
        'link' => 'jiujitsu.php'
    ],
    [
        'nombre' => 'Judo',
        'descripcion' => 'Deporte olímpico y arte marcial tradicional. Enfocado en proyecciones, inmovilizaciones y sumisiones.',
        'imagen' => 'images/judo.jpg',
        'link' => 'judo.php'
    ],
    [
        'nombre' => 'Aikido',
        'descripcion' => 'Arte marcial moderno que utiliza la fuerza del oponente en su contra. Técnicas fluidas y circulares.',
        'imagen' => 'images/aikido.jpg',
        'link' => 'aikido.php'
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia del Lago - Jiu Jitsu, Judo y Aikido en Santa Fe</title>
    <meta name="description" content="Academia del Lago - Artes marciales en Santa Fe Capital. Jiu Jitsu, Judo y Aikido en el Club Náutico El Quilla, orillas del Parque del Sur.">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- ===== HEADER MINIMALISTA CON COLORES AZUL Y AMARILLO ===== -->
    <header class="header">
        <div class="container">
            <a href="#inicio" class="logo-container">
                <img src="images/logo.jpg" alt="Academia del Lago" class="logo-img">
            </a>
            <nav>
                <ul class="nav-links">
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#disciplinas">Disciplinas</a></li>
                    <li><a href="#profesores">Instructores</a></li>
                    <li><a href="#horarios">Horarios</a></li>
                    <li><a href="#galeria">Galería</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                    <li>
                <a href="https://instagram.com/academiadelago" target="_blank" class="instagram-link">
    <img src="images/instagram-icon.jpeg" alt="Instagram" class="instagram-icon" style="height: 16px; width: 16px;">
    Instagram
</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

<!-- ===== HERO CON VIDEO QUE DESAPARECE A IMAGEN ===== -->
<section id="inicio" class="hero">
    <!-- Video que desaparece a los 7 segundos -->
    <div class="hero-video" id="heroVideoContainer">
        <video id="heroVideo" autoplay muted playsinline>
            <source src="images/herovideo.mp4" type="video/mp4">
            <source src="images/hero-video.webm" type="video/webm">
        </video>
        <div class="video-overlay"></div>
    </div>
    
    <!-- Imagen de fondo que aparece después -->
    <div class="hero-image" id="heroImage">
        <img src="images/jiujitsu.jpg" alt="Jiu Jitsu Academia del Lago">
        <div class="image-overlay"></div>
    </div>
    
    <div class="container">
        <div class="hero-content">
            <!-- Logo de la academia -->
            <div class="hero-logo">
                <img src="images/logo.jpg" alt="Academia del Lago">
            </div>
            
            <h1 class="hero-title">
                Academia
                <span class="hero-accent">del Lago</span>
            </h1>
            
            <p class="hero-quote">
                "Donde la disciplina se encuentra con la pasión"
            </p>
            
            <div class="hero-cta">
                <a href="#contacto" class="cta-button">
                    <i class="fas fa-map-marker-alt"></i>
                    Vení a Conocernos
                </a>
            </div>
            
            <div class="hero-info">
                <div class="info-item">
                    <i class="fas fa-location-dot"></i>
                    <span>Club Náutico El Quilla</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span>Clases para todas las edades</span>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- ===== DISCIPLINAS SECTION ===== -->
    <section id="disciplinas" class="cursos">
        <div class="container">
            <h2 class="section-title">Nuestras Disciplinas</h2>
            <p style="text-align: center; color: var(--gris-medio); margin-bottom: 3rem; font-size: 1.1rem;">
                Tres artes marciales, una misma pasión: tu desarrollo personal
            </p>
            
            <div class="cursos-grid">
                <?php foreach ($disciplinas as $disciplina): ?>
                <div class="curso-card">
                    <div class="disciplina-imagen" style="height: 200px; overflow: hidden; border-radius: 10px; margin-bottom: 1.5rem;">
                        <img src="<?php echo $disciplina['imagen']; ?>" alt="<?php echo $disciplina['nombre']; ?>" 
                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" 
                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMjU2M2ViIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxOCIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj48dHNwYW4+PD90c3Bhbj48L3RleHQ+PC9zdmc+'">
                    </div>
                    <h3><?php echo $disciplina['nombre']; ?></h3>
                    <p><?php echo $disciplina['descripcion']; ?></p>
                    <a href="<?php echo $disciplina['link']; ?>" class="cta-button" style="margin-top: 1rem; padding: 10px 25px; font-size: 0.9rem;">
                        Ver Más
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===== POR QUÉ ELEGIRNOS SECTION ===== -->
    <section style="background: var(--azul-principal); color: var(--blanco); padding: 5rem 0;">
        <div class="container">
            <h2 class="section-title" style="color: var(--blanco);">¿Por Qué Elegir Academia del Lago?</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div style="text-align: center; padding: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🏊‍♂️</div>
                    <h3 style="margin-bottom: 1rem;">Ubicación Privilegiada</h3>
                    <p>En el Club Náutico El Quilla, con vistas al Parque del Sur y el lago</p>
                </div>
                <div style="text-align: center; padding: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🥋</div>
                    <h3 style="margin-bottom: 1rem;">Instructores Certificados</h3>
                    <p>Profesores con años de experiencia y formación internacional</p>
                </div>
                <div style="text-align: center; padding: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">👨‍👩‍👧‍👦</div>
                    <h3 style="margin-bottom: 1rem;">Para Todas las Edades</h3>
                    <p>Clases adaptadas para niños, jóvenes y adultos</p>
                </div>
                <div style="text-align: center; padding: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">⭐</div>
                    <h3 style="margin-bottom: 1rem;">Ambiente Familiar</h3>
                    <p>Comunidad unida donde todos se apoyan mutuamente</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PROFESORES SECTION ===== -->
    <section id="profesores" class="profesores">
        <div class="container">
            <h2 class="section-title">Nuestros Instructores</h2>
            <p style="text-align: center; color: var(--gris-medio); margin-bottom: 3rem; font-size: 1.1rem;">
                Conoce a nuestro equipo de profesionales apasionados por las artes marciales
            </p>
            
            <div class="profesores-grid">
                <?php if (count($profesores) > 0): ?>
                    <?php foreach ($profesores as $profesor): ?>
                    <div class="profesor-card">
                       <div class="profesor-image">
    <?php if (!empty($profesor['imagen']) && file_exists('uploads/' . $profesor['imagen'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($profesor['imagen']); ?>" 
             alt="<?php echo htmlspecialchars($profesor['nombre']); ?>" 
             style="width: 100%; height: 100%; object-fit: cover;"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
    <?php endif; ?>
    <div class="placeholder-image" style="<?php echo !empty($profesor['imagen']) ? 'display:none;' : 'display:flex;'; ?>">
        <?php echo substr(htmlspecialchars($profesor['nombre']), 0, 1); ?>
    </div>
</div>
                        <div class="profesor-info">
                            <h3><?php echo htmlspecialchars($profesor['nombre']); ?></h3>
                            <p class="titulo"><?php echo htmlspecialchars($profesor['titulo']); ?></p>
                            <p class="especialidad"><?php echo htmlspecialchars($profesor['especialidad']); ?></p>
                            <p class="descripcion"><?php echo htmlspecialchars($profesor['descripcion']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: var(--blanco); border-radius: 12px; box-shadow: var(--sombra-suave);">
                        <h3 style="color: var(--gris-medio); margin-bottom: 1rem;">Próximamente</h3>
                        <p style="color: var(--gris-medio);">Nuestro equipo de instructores se está preparando para conocerte.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ===== HORARIOS SECTION ===== -->
    <section id="horarios" class="horarios">
        <div class="container">
            <h2 class="section-title">Horarios de Clases</h2>
            <p style="text-align: center; color: var(--gris-medio); margin-bottom: 3rem; font-size: 1.1rem;">
                Encuentra el horario perfecto para tu práctica
            </p>
            
            <div class="horarios-grid">
                <?php 
                $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                foreach ($dias_semana as $dia): 
                    if (isset($horarios_por_dia[$dia])): 
                ?>
                <div class="dia-card">
                    <h3><?php echo $dia; ?></h3>
                    <div class="clases-list">
                        <?php foreach ($horarios_por_dia[$dia] as $clase): ?>
                        <div class="clase-item">
                            <span class="hora"><?php echo date('H:i', strtotime($clase['hora_inicio'])); ?> - <?php echo date('H:i', strtotime($clase['hora_fin'])); ?></span>
                            <span class="disciplina"><?php echo htmlspecialchars($clase['disciplina']); ?></span>
                            <span class="nivel"><?php echo htmlspecialchars($clase['nivel']); ?></span>
                            <?php if (!empty($clase['nombre'])): ?>
                            <span class="profesor">Con <?php echo htmlspecialchars($clase['nombre']); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php 
                    endif;
                endforeach; 
                ?>
                
                <?php if (empty($horarios_por_dia)): ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: var(--blanco); border-radius: 12px; box-shadow: var(--sombra-suave);">
                    <h3 style="color: var(--gris-medio); margin-bottom: 1rem;">Horarios en Actualización</h3>
                    <p style="color: var(--gris-medio);">Estamos preparando los horarios para la nueva temporada.</p>
                    <p style="color: var(--azul-principal); font-weight: 600; margin-top: 1rem;">¡Contáctanos para más información!</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ===== GALERÍA SECTION ===== -->
    <section id="galeria" class="galeria">
        <div class="container">
            <h2 class="section-title">Galería</h2>
            <p style="text-align: center; color: var(--gris-medio); margin-bottom: 3rem; font-size: 1.1rem;">
                Momentos especiales de nuestra comunidad marcial
            </p>
            
            <div class="galeria-grid">
                <?php if (count($galeria) > 0): ?>
                    <?php foreach ($galeria as $item): ?>
                    <div class="galeria-item">
                        <?php if ($item['tipo'] == 'imagen'): ?>
                            <img src="uploads/<?php echo $item['archivo']; ?>" alt="<?php echo htmlspecialchars($item['titulo']); ?>"
                                 onerror="this.style.display='none'">
                        <?php else: ?>
                            <div class="video-placeholder">
                                <span>🎥</span>
                                <p><?php echo htmlspecialchars($item['titulo']); ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="galeria-info">
                            <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                            <p><?php echo htmlspecialchars($item['descripcion']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: var(--blanco); border-radius: 12px; box-shadow: var(--sombra-suave);">
                        <h3 style="color: var(--gris-medio); margin-bottom: 1rem;">Galería en Construcción</h3>
                        <p style="color: var(--gris-medio);">Próximamente podrás ver los mejores momentos de nuestra academia.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ===== CONTACTO SECTION ===== -->
    <section id="contacto" class="contacto">
        <div class="container">
            <h2 class="section-title">Contáctanos</h2>
            <p style="text-align: center; color: var(--gris-medio); margin-bottom: 3rem; font-size: 1.1rem;">
                ¿Listo para comenzar tu viaje en las artes marciales?
            </p>
            
            <div class="contacto-content">
                <div class="contacto-info">
                    <h3>Información de Contacto</h3>
                    <div class="contacto-item">
                        <strong>📍 Dirección</strong>
                        <p>Club Náutico El Quilla<br>Av. Costanera Oeste<br>Parque del Sur, Santa Fe Capital</p>
                    </div>
                    <div class="contacto-item">
                        <strong>📞 Teléfono</strong>
                        <p>+54 342 123-4567</p>
                    </div>
                    <div class="contacto-item">
                        <strong>✉️ Email</strong>
                        <p>info@academiadelago.com</p>
                    </div>
                    <div class="contacto-item">
                        <strong>🕒 Horario de Atención</strong>
                        <p>Lunes a Viernes: 8:00 - 21:00<br>Sábados: 9:00 - 13:00</p>
                    </div>
                </div>
                
                <form class="contacto-form" action="php/procesar_contacto.php" method="POST" id="formContacto">
                    <div class="form-group">
                        <input type="text" name="nombre" placeholder="Nombre completo" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Correo electrónico" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="telefono" placeholder="Teléfono (opcional)">
                    </div>
                    <div class="form-group">
                        <select name="curso" required>
                            <option value="">Selecciona tu disciplina de interés</option>
                            <option value="Jiu Jitsu">Jiu Jitsu</option>
                            <option value="Judo">Judo</option>
                            <option value="Aikido">Aikido</option>
                            <option value="Todas">Todas las disciplinas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea name="mensaje" placeholder="Cuéntanos sobre tus objetivos o haz cualquier consulta..." rows="5" required></textarea>
                    </div>
                    <button type="submit" class="cta-button" id="btnEnviar">
                        Enviar Mensaje
                    </button>
                    <div id="mensajeResultado" style="margin-top: 1rem; display: none;"></div>
                </form>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Academia del Lago</h3>
                    <p>Formación en artes marciales de calidad desde 2010. Ubicados en el Club Náutico El Quilla, a orillas del Parque del Sur en Santa Fe Capital.</p>
                </div>
                <div class="footer-section">
                    <h3>Enlaces Rápidos</h3>
                    <ul>
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#disciplinas">Disciplinas</a></li>
                        <li><a href="#profesores">Instructores</a></li>
                        <li><a href="#horarios">Horarios</a></li>
                        <li><a href="#galeria">Galería</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Síguenos</h3>
                    <ul>
                        <li><a href="https://instagram.com/academiadelago" target="_blank" style="color: var(--amarillo);">Instagram</a></li>
                        <li><a href="#" style="color: var(--amarillo);">Facebook</a></li>
                        <li><a href="#" style="color: var(--amarillo);">YouTube</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Academia del Lago. Todos los derechos reservados. | Club Náutico El Quilla - Santa Fe Capital</p>
            </div>
        </div>
    </footer>

    <!-- ===== SCRIPTS ===== -->
    <script>
        // Smooth Scroll para navegación
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Header con efecto al hacer scroll
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 50) {
                header.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
                header.style.background = 'rgba(29, 78, 216, 0.95)';
                header.style.backdropFilter = 'blur(10px)';
            } else {
                header.style.boxShadow = 'var(--sombra-media)';
                header.style.background = 'var(--azul-oscuro)';
                header.style.backdropFilter = 'blur(0px)';
            }
        });
// Transición de video a imagen a los 7 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const heroVideoContainer = document.getElementById('heroVideoContainer');
            const heroVideo = document.getElementById('heroVideo');
            const heroImage = document.getElementById('heroImage');
            
            if (!heroVideoContainer || !heroImage) return;
            
            setTimeout(() => {
                heroVideoContainer.classList.add('video-hidden');
                setTimeout(() => {
                    heroImage.classList.add('image-visible');
                }, 500);
                if (heroVideo) heroVideo.pause();
            }, 7000);
            
            if (heroVideo) {
                heroVideo.addEventListener('error', function() {
    console.log('Error cargando video, mostrando imagen de respaldo');
    heroVideoContainer.style.display = 'none';
    heroImage.classList.add('image-visible');
});

// AGREGAR DESPUÉS: Verificación de soporte de video
if (!document.createElement('video').canPlayType) {
    console.log('Navegador no soporta video, mostrando imagen');
    heroVideoContainer.style.display = 'none';
    heroImage.classList.add('image-visible');
}
                
                heroVideo.addEventListener('loadeddata', function() {
                    this.play().catch(e => {
                        heroVideoContainer.style.display = 'none';
                        heroImage.classList.add('image-visible');
                    });
                });
            } else {
                heroVideoContainer.style.display = 'none';
                heroImage.classList.add('image-visible');
            }
        });
        // Animación de elementos al hacer scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observar elementos para animación
        document.querySelectorAll('.curso-card, .profesor-card, .dia-card, .galeria-item').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });

        // Manejo del formulario de contacto
        document.getElementById('formContacto').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = document.getElementById('btnEnviar');
            const mensajeResultado = document.getElementById('mensajeResultado');
            const originalText = submitButton.textContent;
            
            submitButton.textContent = 'Enviando...';
            submitButton.disabled = true;
            mensajeResultado.style.display = 'none';
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mensajeResultado.style.display = 'block';
                    mensajeResultado.style.background = 'var(--amarillo)';
                    mensajeResultado.style.color = 'var(--negro)';
                    mensajeResultado.style.padding = '1rem';
                    mensajeResultado.style.borderRadius = '8px';
                    mensajeResultado.style.fontWeight = '600';
                    mensajeResultado.innerHTML = '✅ ' + data.message;
                    this.reset();
                } else {
                    mensajeResultado.style.display = 'block';
                    mensajeResultado.style.background = '#fef2f2';
                    mensajeResultado.style.color = '#dc2626';
                    mensajeResultado.style.padding = '1rem';
                    mensajeResultado.style.borderRadius = '8px';
                    mensajeResultado.style.border = '1px solid #fecaca';
                    mensajeResultado.innerHTML = '❌ ' + data.message;
                }
            })
            .catch(error => {
                mensajeResultado.style.display = 'block';
                mensajeResultado.style.background = '#fef2f2';
                mensajeResultado.style.color = '#dc2626';
                mensajeResultado.style.padding = '1rem';
                mensajeResultado.style.borderRadius = '8px';
                mensajeResultado.style.border = '1px solid #fecaca';
                mensajeResultado.innerHTML = '❌ Error al enviar el mensaje. Por favor, intenta nuevamente.';
            })
            .finally(() => {
                submitButton.textContent = originalText;
                submitButton.disabled = false;
                
                // Scroll al mensaje de resultado
                mensajeResultado.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });

        // Efecto hover mejorado para imágenes de disciplinas
        document.querySelectorAll('.disciplina-imagen img').forEach(img => {
            img.parentElement.addEventListener('mouseenter', function() {
                img.style.transform = 'scale(1.1)';
            });
            img.parentElement.addEventListener('mouseleave', function() {
                img.style.transform = 'scale(1)';
            });
        });

        // Mostrar año actual en footer
        document.addEventListener('DOMContentLoaded', function() {
            const yearElement = document.querySelector('.footer-bottom p');
            if (yearElement) {
                yearElement.innerHTML = yearElement.innerHTML.replace('2024', new Date().getFullYear());
            }
        });
    </script>
</body>
</html>
