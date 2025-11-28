cat > index.php << 'EOF'
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

// Obtener horarios
$query_horarios = "SELECT h.*, p.nombre as profesor_nombre 
                  FROM horarios h 
                  LEFT JOIN profesores p ON h.profesor_id = p.id 
                  WHERE h.activo = 1 
                  ORDER BY 
                    FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'),
                    h.hora_inicio";
$stmt_horarios = $db->prepare($query_horarios);
$stmt_horarios->execute();
$horarios = $stmt_horarios->fetchAll(PDO::FETCH_ASSOC);

// Agrupar horarios por día
$horarios_por_dia = [];
foreach ($horarios as $horario) {
    $horarios_por_dia[$horario['dia_semana']][] = $horario;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia del Lago - Jiu Jitsu, Aikido y Judo en Santa Fe</title>
    <meta name="description" content="Academia del Lago - Entrenamiento profesional de Jiu Jitsu, Aikido y Judo en Club Náutico El Quilla, Santa Fe. Registrada en la Confederación Argentina de Jiu Jitsu.">
    <meta name="keywords" content="jiu jitsu, aikido, judo, artes marciales, santa fe, academia del lago, defensa personal">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <div class="logo">Academia <span>del Lago</span></div>
            <nav>
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#sobre-nosotros">Sobre Nosotros</a></li>
                    <li><a href="#disciplinas">Disciplinas</a></li>
                    <li><a href="#profesores">Profesores</a></li>
                    <li><a href="#horarios">Horarios</a></li>
                    <li><a href="#galeria">Galería</a></li>
                    <li><a href="#ubicacion">Ubicación</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Sección Principal -->
    <section id="inicio" class="hero">
        <div class="container">
            <h1>Academia del Lago</h1>
            <p>Entrenamiento de Jiu Jitsu, Aikido y Judo en Santa Fe, Argentina</p>
            <p>Ubicados en Club Náutico El Quilla, a las orillas del lago del sur</p>
            <p class="certification">Registrada en la Confederación Argentina de Jiu Jitsu</p>
            <div class="hero-buttons">
                <a href="#contacto" class="btn btn-primary">Contáctanos</a>
                <a href="#horarios" class="btn btn-secondary">Ver Horarios</a>
            </div>
        </div>
    </section>

    <!-- Sección Sobre Nosotros -->
    <section id="sobre-nosotros" class="about">
        <div class="container">
            <h2>Sobre Nosotros</h2>
            <div class="about-content">
                <div class="about-text">
                    <p>La <strong>Academia del Lago</strong> es un centro de artes marciales ubicado en el hermoso entorno del Club Náutico El Quilla, a orillas del lago del sur en Santa Fe, Argentina.</p>
                    <p>Nos especializamos en la enseñanza de <strong>Jiu Jitsu, Aikido y Judo</strong>, ofreciendo un ambiente familiar y profesional para todos los niveles, desde principiantes hasta avanzados.</p>
                    <p>Nuestra academia está <strong>registrada en la Confederación Argentina de Jiu Jitsu</strong>, garantizando la calidad y autenticidad de nuestra enseñanza.</p>
                    <p>Creemos en el desarrollo integral de nuestros estudiantes, fomentando no solo las habilidades físicas, sino también los valores de <strong>disciplina, respeto y superación personal</strong>.</p>
                    <div class="features">
                        <div class="feature">
                            <h3>🏆 Profesores Certificados</h3>
                            <p>Instructores con años de experiencia y certificaciones internacionales</p>
                        </div>
                        <div class="feature">
                            <h3>🌊 Entorno Único</h3>
                            <p>Ubicados en un entorno natural privilegiado a orillas del lago</p>
                        </div>
                        <div class="feature">
                            <h3>👥 Ambiente Familiar</h3>
                            <p>Clases personalizadas en grupos reducidos para mejor aprendizaje</p>
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <img src="images/academia-exterior.jpg" alt="Academia del Lago - Club Náutico El Quilla" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAwIiBoZWlnaHQ9IjQwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIyNCIgZmlsbD0iIzY2NiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkFjYWRlbWlhIGRlbCBMYWdvPC90ZXh0Pjwvc3ZnPg=='">
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Disciplinas -->
    <section id="disciplinas" class="disciplines">
        <div class="container">
            <h2>Nuestras Disciplinas</h2>
            <p class="section-subtitle">Descubre las artes marciales que enseñamos y encuentra la que mejor se adapte a ti</p>
            <div class="disciplines-grid">
                <div class="discipline-card">
                    <div class="discipline-image">
                        <img src="images/jiu-jitsu.jpg" alt="Jiu Jitsu en Academia del Lago" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMWEzYTVmIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIyNCIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5KaXUgSml0c3U8L3RleHQ+PC9zdmc+'">
                    </div>
                    <div class="discipline-content">
                        <h3>Jiu Jitsu</h3>
                        <p>Arte marcial japonés que se centra en la lucha cuerpo a cuerpo, donde se utiliza la fuerza del oponente en su contra.</p>
                        <ul>
                            <li>Ideal para defensa personal</li>
                            <li>Desarrollo físico integral</li>
                            <li>Técnicas de sumisión y control</li>
                            <li>Para todas las edades</li>
                        </ul>
                    </div>
                </div>
                <div class="discipline-card">
                    <div class="discipline-image">
                        <img src="images/aikido.jpg" alt="Aikido en Academia del Lago" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMWEzYTVmIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIyNCIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5BaWtpZG88L3RleHQ+PC9zdmc+'">
                    </div>
                    <div class="discipline-content">
                        <h3>Aikido</h3>
                        <p>Arte marcial japonés moderno que se centra en la redirección de la fuerza del atacante mediante movimientos circulares.</p>
                        <ul>
                            <li>Promueve la armonía</li>
                            <li>Resolución pacífica de conflictos</li>
                            <li>Desarrollo espiritual</li>
                            <li>Movimientos fluidos y elegantes</li>
                        </ul>
                    </div>
                </div>
                <div class="discipline-card">
                    <div class="discipline-image">
                        <img src="images/judo.jpg" alt="Judo en Academia del Lago" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMWEzYTVmIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIyNCIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5KdWRvPC90ZXh0Pjwvc3ZnPg=='">
                    </div>
                    <div class="discipline-content">
                        <h3>Judo</h3>
                        <p>Deporte olímpico y arte marcial que se enfoca en proyecciones, inmovilizaciones y sumisiones.</p>
                        <ul>
                            <li>Deporte olímpico</li>
                            <li>Desarrolla coordinación y equilibrio</li>
                            <li>Técnicas de proyección</li>
                            <li>Fuerza física y mental</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Profesores -->
    <section id="profesores" class="instructors">
        <div class="container">
            <h2>Nuestros Profesores</h2>
            <p class="section-subtitle">Conoce a nuestro equipo de instructores altamente calificados</p>
            <div class="instructors-grid">
                <?php if (count($profesores) > 0): ?>
                    <?php foreach ($profesores as $profesor): ?>
                        <div class="instructor-card">
                            <div class="instructor-image">
                                <?php if (!empty($profesor['imagen'])): ?>
                                    <img src="uploads/profesores/<?php echo $profesor['imagen']; ?>" alt="<?php echo $profesor['nombre']; ?> - Academia del Lago">
                                <?php else: ?>
                                    <img src="images/profesor-default.jpg" alt="<?php echo $profesor['nombre']; ?>" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSI3NSIgY3k9Ijc1IiByPSI3MCIgZmlsbD0iIzFhM2E1ZiIvPjx0ZXh0IHg9Ijc1IiB5PSI4NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+UHJvZmVzb3I8L3RleHQ+PC9zdmc+'">
                                <?php endif; ?>
                            </div>
                            <h3><?php echo $profesor['nombre']; ?></h3>
                            <p class="instructor-title"><?php echo $profesor['titulo']; ?></p>
                            <p class="instructor-specialty"><?php echo $profesor['especialidad']; ?></p>
                            <?php if (!empty($profesor['descripcion'])): ?>
                                <p class="instructor-description"><?php echo $profesor['descripcion']; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Profesores por defecto si no hay en la base de datos -->
                    <div class="instructor-card">
                        <div class="instructor-image">
                            <img src="images/profesor1.jpg" alt="Profesor Carlos Rodríguez - Academia del Lago" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSI3NSIgY3k9Ijc1IiByPSI3MCIgZmlsbD0iIzFhM2E1ZiIvPjx0ZXh0IHg9Ijc1IiB5PSI4NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+Q2FybG9zPC90ZXh0Pjwvc3ZnPg=='">
                        </div>
                        <h3>Carlos Rodríguez</h3>
                        <p class="instructor-title">5to Dan Jiu Jitsu</p>
                        <p class="instructor-specialty">Especialista en defensa personal</p>
                        <p class="instructor-description">Más de 15 años de experiencia enseñando Jiu Jitsu tradicional y moderno.</p>
                    </div>
                    <div class="instructor-card">
                        <div class="instructor-image">
                            <img src="images/profesor2.jpg" alt="Profesora María González - Academia del Lago" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSI3NSIgY3k9Ijc1IiByPSI3MCIgZmlsbD0iIzFhM2E1ZiIvPjx0ZXh0IHg9Ijc1IiB5PSI4NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+TWFyw61hPC90ZXh0Pjwvc3ZnPg=='">
                        </div>
                        <h3>María González</h3>
                        <p class="instructor-title">4to Dan Aikido</p>
                        <p class="instructor-specialty">Instructora certificada internacionalmente</p>
                        <p class="instructor-description">Especializada en Aikido tradicional y sus aplicaciones modernas.</p>
                    </div>
                    <div class="instructor-card">
                        <div class="instructor-image">
                            <img src="images/profesor3.jpg" alt="Profesor Luis Fernández - Academia del Lago" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSI3NSIgY3k9Ijc1IiByPSI3MCIgZmlsbD0iIzFhM2E1ZiIvPjx0ZXh0IHg9Ijc1IiB5PSI4NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE4IiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+THVpczwvdGV4dD48L3N2Zz4='">
                        </div>
                        <h3>Luis Fernández</h3>
                        <p class="instructor-title">3er Dan Judo</p>
                        <p class="instructor-specialty">Competidor nacional e internacional</p>
                        <p class="instructor-description">Campeón nacional de Judo en múltiples oportunidades.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Sección Horarios -->
    <section id="horarios" class="schedule">
        <div class="container">
            <h2>Nuestros Horarios</h2>
            <p class="section-subtitle">Consulta nuestros horarios de clases y encuentra el que mejor se adapte a tus necesidades</p>
            
            <div class="schedule-container">
                <?php
                $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                ?>
                
                <div class="schedule-grid">
                    <?php foreach ($dias_semana as $dia): ?>
                        <div class="schedule-day">
                            <h3><?php echo $dia; ?></h3>
                            
                            <?php if (isset($horarios_por_dia[$dia]) && count($horarios_por_dia[$dia]) > 0): ?>
                                <?php foreach ($horarios_por_dia[$dia] as $horario): ?>
                                    <div class="schedule-class">
                                        <div class="class-time">
                                            <?php echo date('H:i', strtotime($horario['hora_inicio'])); ?> - 
                                            <?php echo date('H:i', strtotime($horario['hora_fin'])); ?>
                                        </div>
                                        <div class="class-info">
                                            <strong><?php echo $horario['disciplina']; ?></strong>
                                            <span class="class-level"><?php echo $horario['nivel']; ?></span>
                                            <?php if (!empty($horario['profesor_nombre'])): ?>
                                                <span class="class-professor">Con <?php echo $horario['profesor_nombre']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="schedule-class no-class">
                                    No hay clases programadas
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="schedule-notes">
                <p><strong>Nota:</strong> Todos los horarios están sujetos a cambios. Te recomendamos contactarnos para confirmar disponibilidad y realizar una clase de prueba gratuita.</p>
            </div>
        </div>
    </section>

    <!-- Sección Galería -->
    <section id="galeria" class="gallery">
        <div class="container">
            <h2>Galería</h2>
            <p class="section-subtitle">Momentos de entrenamiento y actividades en nuestra academia</p>
            <div class="gallery-grid">
                <?php if (count($galeria) > 0): ?>
                    <?php foreach ($galeria as $item): ?>
                        <div class="gallery-item">
                            <?php if ($item['tipo'] == 'imagen'): ?>
                                <img src="uploads/galeria/<?php echo $item['archivo']; ?>" alt="<?php echo $item['titulo']; ?> - Academia del Lago">
                                <div class="gallery-overlay">
                                    <h4><?php echo $item['titulo']; ?></h4>
                                    <?php if (!empty($item['descripcion'])): ?>
                                        <p><?php echo $item['descripcion']; ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <video controls>
                                    <source src="uploads/galeria/<?php echo $item['archivo']; ?>" type="video/mp4">
                                    Tu navegador no soporta el elemento video.
                                </video>
                                <div class="gallery-overlay">
                                    <h4><?php echo $item['titulo']; ?></h4>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Galería por defecto si no hay archivos -->
                    <div class="gallery-item">
                        <div style="width:100%;height:100%;background:#1a3a5f;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;">
                            Imagen de entrenamiento
                        </div>
                        <div class="gallery-overlay">
                            <h4>Entrenamiento de Jiu Jitsu</h4>
                            <p>Técnicas de suelo y defensa personal</p>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <div style="width:100%;height:100%;background:#1a3a5f;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;">
                            Clase de Aikido
                        </div>
                        <div class="gallery-overlay">
                            <h4>Clase de Aikido</h4>
                            <p>Movimientos circulares y fluidos</p>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <div style="width:100%;height:100%;background:#1a3a5f;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;">
                            Práctica de Judo
                        </div>
                        <div class="gallery-overlay">
                            <h4>Práctica de Judo</h4>
                            <p>Técnicas de proyección y control</p>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <div style="width:100%;height:100%;background:#1a3a5f;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;">
                            Clase para niños
                        </div>
                        <div class="gallery-overlay">
                            <h4>Clase para niños</h4>
                            <p>Formación en valores y disciplina</p>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <div style="width:100%;height:100%;background:#1a3a5f;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;">
                            Video demostración
                        </div>
                        <div class="gallery-overlay">
                            <h4>Técnica de llave de brazo</h4>
                            <p>Demostración paso a paso</p>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <div style="width:100%;height:100%;background:#1a3a5f;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;">
                            Técnica avanzada
                        </div>
                        <div class="gallery-overlay">
                            <h4>Llave de brazo</h4>
                            <p>Técnica avanzada de Jiu Jitsu</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Sección Ubicación -->
    <section id="ubicacion" class="location">
        <div class="container">
            <h2>Nuestra Ubicación</h2>
            <div class="location-content">
                <div class="location-info">
                    <h3>Club Náutico El Quilla</h3>
                    <p>Nos encontramos en un entorno privilegiado a orillas del lago del sur en Santa Fe, Argentina.</p>
                    
                    <div class="location-features">
                        <h4>Nuestras instalaciones cuentan con:</h4>
                        <ul>
                            <li>✅ Dojo amplio y equipado</li>
                            <li>✅ Vestuarios con duchas</li>
                            <li>✅ Estacionamiento gratuito</li>
                            <li>✅ Área de descanso con vista al lago</li>
                            <li>✅ Equipamiento profesional</li>
                            <li>✅ Ambiente climatizado</li>
                        </ul>
                    </div>
                    
                    <div class="location-hours">
                        <h4>Horarios de atención:</h4>
                        <p>🕗 <strong>Lunes a Viernes:</strong> 8:00 - 22:00</p>
                        <p>🕘 <strong>Sábados:</strong> 9:00 - 18:00</p>
                        <p>🚫 <strong>Domingos:</strong> Cerrado</p>
                    </div>
                    
                    <div class="location-contact">
                        <p>📍 <strong>Dirección:</strong> Club Náutico El Quilla, orillas del lago del sur, Santa Fe, Argentina</p>
                        <p>📞 <strong>Teléfono:</strong> +54 342 123-4567</p>
                        <p>✉️ <strong>Email:</strong> info@academiadelago.com</p>
                    </div>
                </div>
                <div class="location-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3397.454231293497!2d-60.71138872468667!3d-31.63690767416824!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95b5a9e8e9e9e9e9%3A0x9e9e9e9e9e9e9e9e!2sSanta%20Fe%2C%20Argentina!5e0!3m2!1ses!2ses!4v1620000000000!5m2!1ses!2ses" 
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Contacto -->
    <section id="contacto" class="contact">
        <div class="container">
            <h2>Contáctanos</h2>
            <p class="section-subtitle">¿Tienes preguntas? Escríbenos y te responderemos a la brevedad</p>
            <div class="contact-content">
                <div class="contact-info">
                    <h3>Información de Contacto</h3>
                    <div class="contact-item">
                        <h4>📍 Dirección</h4>
                        <p>Club Náutico El Quilla<br>Orillas del lago del sur<br>Santa Fe, Argentina</p>
                    </div>
                    <div class="contact-item">
                        <h4>📞 Teléfono</h4>
                        <p>+54 342 123-4567</p>
                    </div>
                    <div class="contact-item">
                        <h4>✉️ Email</h4>
                        <p>info@academiadelago.com</p>
                    </div>
                    <div class="contact-item">
                        <h4>🕗 Horarios de Atención</h4>
                        <p>Lunes a Viernes: 8:00 - 22:00<br>Sábados: 9:00 - 18:00</p>
                    </div>
                    <div class="social-links">
                        <a href="#" class="social-link">📘 Facebook</a>
                        <a href="#" class="social-link">📷 Instagram</a>
                        <a href="#" class="social-link">📹 YouTube</a>
                    </div>
                </div>
                <div class="contact-form">
                    <form action="php/procesar_contacto.php" method="POST">
                        <div class="form-group">
                            <label for="nombre">Nombre completo *</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono">
                        </div>
                        <div class="form-group">
                            <label for="interes">Interés Principal</label>
                            <select id="interes" name="interes">
                                <option value="">Selecciona una opción</option>
                                <option value="jiu-jitsu">Jiu Jitsu</option>
                                <option value="aikido">Aikido</option>
                                <option value="judo">Judo</option>
                                <option value="todos">Todas las disciplinas</option>
                                <option value="clase-prueba">Clase de prueba gratuita</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="mensaje">Mensaje *</label>
                            <textarea id="mensaje" name="mensaje" placeholder="Cuéntanos en qué podemos ayudarte..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Newsletter -->
    <section class="newsletter">
        <div class="container">
            <h2>Suscríbete a nuestro Newsletter</h2>
            <p>Recibe noticias sobre clases, eventos, promociones especiales y contenido exclusivo de artes marciales.</p>
            <form class="newsletter-form" action="php/procesar_newsletter.php" method="POST">
                <input type="email" name="email_newsletter" placeholder="Tu correo electrónico" required>
                <button type="submit" class="btn btn-secondary">Suscribirse</button>
            </form>
            <p class="newsletter-note">🔒 Respetamos tu privacidad. Puedes darte de baja en cualquier momento.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Academia del Lago</h3>
                    <p>Especialistas en Jiu Jitsu, Aikido y Judo en Santa Fe, Argentina.</p>
                    <p>Registrada en la Confederación Argentina de Jiu Jitsu.</p>
                    <div class="certification-badge">
                        <span>🏅 Certificada CAJJ</span>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Enlaces Rápidos</h3>
                    <ul>
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#sobre-nosotros">Sobre Nosotros</a></li>
                        <li><a href="#disciplinas">Disciplinas</a></li>
                        <li><a href="#profesores">Profesores</a></li>
                        <li><a href="#horarios">Horarios</a></li>
                        <li><a href="#galeria">Galería</a></li>
                        <li><a href="#ubicacion">Ubicación</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contacto</h3>
                    <p>📍 Club Náutico El Quilla</p>
                    <p>🌊 Orillas del lago del sur</p>
                    <p>🏙️ Santa Fe, Argentina</p>
                    <p>📞 +54 342 123-4567</p>
                    <p>✉️ info@academiadelago.com</p>
                    <div class="social-links">
                        <a href="#" class="social-link">Facebook</a>
                        <a href="#" class="social-link">Instagram</a>
                        <a href="#" class="social-link">YouTube</a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Horarios</h3>
                    <p>🕗 Lunes a Viernes: 8:00 - 22:00</p>
                    <p>🕘 Sábados: 9:00 - 18:00</p>
                    <p>🚫 Domingos: Cerrado</p>
                    <div class="emergency-contact">
                        <p>📞 Urgencias: +54 342 987-6543</p>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2024 Academia del Lago. Todos los derechos reservados. | Desarrollado con ❤️ para la comunidad marcial</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>

