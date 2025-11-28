cat > admin/horarios.php << 'EOF'
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Procesar formulario de horarios
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_horario'])) {
    $disciplina = htmlspecialchars(trim($_POST['disciplina']));
    $dia_semana = htmlspecialchars(trim($_POST['dia_semana']));
    $hora_inicio = htmlspecialchars(trim($_POST['hora_inicio']));
    $hora_fin = htmlspecialchars(trim($_POST['hora_fin']));
    $nivel = htmlspecialchars(trim($_POST['nivel']));
    $profesor_id = !empty($_POST['profesor_id']) ? intval($_POST['profesor_id']) : NULL;
    
    $query = "INSERT INTO horarios (disciplina, dia_semana, hora_inicio, hora_fin, nivel, profesor_id) 
             VALUES (:disciplina, :dia_semana, :hora_inicio, :hora_fin, :nivel, :profesor_id)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":disciplina", $disciplina);
    $stmt->bindParam(":dia_semana", $dia_semana);
    $stmt->bindParam(":hora_inicio", $hora_inicio);
    $stmt->bindParam(":hora_fin", $hora_fin);
    $stmt->bindParam(":nivel", $nivel);
    $stmt->bindParam(":profesor_id", $profesor_id);
    
    if ($stmt->execute()) {
        $mensaje_exito = "Horario agregado exitosamente";
    } else {
        $mensaje_error = "Error al agregar el horario";
    }
}

// Obtener horarios
$query = "SELECT h.*, p.nombre as profesor_nombre 
          FROM horarios h 
          LEFT JOIN profesores p ON h.profesor_id = p.id 
          WHERE h.activo = 1 
          ORDER BY 
            FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'),
            h.hora_inicio";
$stmt = $db->prepare($query);
$stmt->execute();
$horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener profesores para el select
$query_profesores = "SELECT id, nombre FROM profesores WHERE activo = 1 ORDER BY nombre";
$stmt_profesores = $db->prepare($query_profesores);
$stmt_profesores->execute();
$profesores = $stmt_profesores->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horarios - Academia del Lago</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body { 
            background: #f5f5f5; 
            color: #333;
        }
        
        .header { 
            background: #1a3a5f; 
            color: white; 
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-content {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .container { 
            max-width: 1200px; 
            margin: 30px auto; 
            padding: 0 20px;
        }
        
        .form-section { 
            margin-bottom: 40px; 
            padding: 30px; 
            background: white; 
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .form-group { 
            margin-bottom: 20px; 
        }
        
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold;
            color: #1a3a5f;
        }
        
        input, select { 
            width: 100%; 
            padding: 12px 15px; 
            border: 2px solid #e0e0e0; 
            border-radius: 6px; 
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #1a3a5f;
        }
        
        button { 
            background: #1a3a5f; 
            color: white; 
            padding: 12px 30px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background: #2a5a8f;
        }
        
        .horarios-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .horarios-table th, .horarios-table td { 
            padding: 15px; 
            text-align: left; 
            border-bottom: 1px solid #e0e0e0; 
        }
        
        .horarios-table th { 
            background: #f8f9fa; 
            font-weight: 600;
            color: #1a3a5f;
        }
        
        .mensaje { 
            padding: 15px; 
            margin: 15px 0; 
            border-radius: 6px; 
            font-weight: 500;
        }
        
        .exito { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        
        .error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
        
        .btn { 
            display: inline-block; 
            padding: 8px 16px; 
            text-decoration: none; 
            border-radius: 4px; 
            margin: 2px; 
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .btn-danger { 
            background: #dc3545; 
            color: white; 
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .nav { 
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .nav a { 
            padding: 10px 20px;
            text-decoration: none; 
            color: #1a3a5f; 
            font-weight: bold;
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .nav a:hover {
            background: #1a3a5f;
            color: white;
        }
        
        .section-title {
            color: #1a3a5f;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e6b325;
        }
        
        .dia-header { 
            background: #e9ecef; 
            font-weight: bold;
            color: #1a3a5f;
        }
        
        .no-classes {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
        
        .time-cell {
            font-weight: 600;
            color: #1a3a5f;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Gestión de Horarios - Academia del Lago</h1>
        </div>
    </div>
    
    <div class="container">
        <div class="nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="galeria.php">Galería</a>
            <a href="profesores.php">Profesores</a>
            <a href="horarios.php">Horarios</a>
            <a href="logout.php" style="margin-left: auto; background: #dc3545; color: white;">Cerrar Sesión</a>
        </div>
        
        <!-- Formulario para agregar horario -->
        <div class="form-section">
            <h2 class="section-title">Agregar Nuevo Horario</h2>
            
            <?php if (isset($mensaje_exito)): ?>
                <div class="mensaje exito"><?php echo $mensaje_exito; ?></div>
            <?php endif; ?>
            
            <?php if (isset($mensaje_error)): ?>
                <div class="mensaje error"><?php echo $mensaje_error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Disciplina:</label>
                    <select name="disciplina" required>
                        <option value="Jiu Jitsu">Jiu Jitsu</option>
                        <option value="Aikido">Aikido</option>
                        <option value="Judo">Judo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Día de la semana:</label>
                    <select name="dia_semana" required>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Hora de inicio:</label>
                    <input type="time" name="hora_inicio" required>
                </div>
                
                <div class="form-group">
                    <label>Hora de fin:</label>
                    <input type="time" name="hora_fin" required>
                </div>
                
                <div class="form-group">
                    <label>Nivel:</label>
                    <select name="nivel" required>
                        <option value="Principiante">Principiante</option>
                        <option value="Intermedio">Intermedio</option>
                        <option value="Avanzado">Avanzado</option>
                        <option value="Todos los niveles">Todos los niveles</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Profesor (opcional):</label>
                    <select name="profesor_id">
                        <option value="">Seleccionar profesor</option>
                        <?php foreach ($profesores as $profesor): ?>
                            <option value="<?php echo $profesor['id']; ?>">
                                <?php echo $profesor['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" name="agregar_horario">Agregar Horario</button>
            </form>
        </div>
        
        <!-- Tabla de horarios -->
        <div class="form-section">
            <h2 class="section-title">Horarios Actuales</h2>
            
            <?php if (count($horarios) > 0): ?>
                <table class="horarios-table">
                    <thead>
                        <tr>
                            <th>Día</th>
                            <th>Horario</th>
                            <th>Disciplina</th>
                            <th>Nivel</th>
                            <th>Profesor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                        $current_day = '';
                        ?>
                        
                        <?php foreach ($dias_semana as $dia): ?>
                            <?php
                            $horarios_dia = array_filter($horarios, function($horario) use ($dia) {
                                return $horario['dia_semana'] === $dia;
                            });
                            ?>
                            
                            <?php if (count($horarios_dia) > 0): ?>
                                <?php foreach ($horarios_dia as $index => $horario): ?>
                                    <tr>
                                        <?php if ($current_day !== $dia): ?>
                                            <?php $current_day = $dia; ?>
                                            <td class="dia-header" rowspan="<?php echo count($horarios_dia); ?>">
                                                <?php echo $dia; ?>
                                            </td>
                                        <?php endif; ?>
                                        
                                        <td class="time-cell">
                                            <?php echo date('H:i', strtotime($horario['hora_inicio'])); ?> - 
                                            <?php echo date('H:i', strtotime($horario['hora_fin'])); ?>
                                        </td>
                                        <td><?php echo $horario['disciplina']; ?></td>
                                        <td><?php echo $horario['nivel']; ?></td>
                                        <td><?php echo $horario['profesor_nombre'] ?? 'Por asignar'; ?></td>
                                        <td>
                                            <a href="eliminar_horario.php?id=<?php echo $horario['id']; ?>" 
                                               class="btn btn-danger" 
                                               onclick="return confirm('¿Estás seguro de eliminar este horario?')">
                                                Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td class="dia-header"><?php echo $dia; ?></td>
                                    <td colspan="5" class="no-classes">No hay clases programadas</td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-classes" style="padding: 40px; background: white; border-radius: 8px;">
                    <h3>No hay horarios programados</h3>
                    <p>Comienza agregando tu primer horario usando el formulario de arriba.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
EOF
