cat > admin/profesores.php << 'EOF'
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Procesar agregar profesor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_profesor'])) {
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $titulo = htmlspecialchars(trim($_POST['titulo']));
    $especialidad = htmlspecialchars(trim($_POST['especialidad']));
    $descripcion = htmlspecialchars(trim($_POST['descripcion']));
    $orden = intval($_POST['orden']);
    
    $imagen_nombre = '';
    if (!empty($_FILES['imagen']['name'])) {
        $target_dir = "../uploads/profesores/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
                $imagen_nombre = $new_filename;
            }
        }
    }
    
    $query = "INSERT INTO profesores (nombre, titulo, especialidad, descripcion, imagen, orden) 
             VALUES (:nombre, :titulo, :especialidad, :descripcion, :imagen, :orden)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":titulo", $titulo);
    $stmt->bindParam(":especialidad", $especialidad);
    $stmt->bindParam(":descripcion", $descripcion);
    $stmt->bindParam(":imagen", $imagen_nombre);
    $stmt->bindParam(":orden", $orden);
    
    if ($stmt->execute()) {
        $mensaje_exito = "Profesor agregado exitosamente";
    } else {
        $mensaje_error = "Error al agregar el profesor";
    }
}

// Obtener profesores
$query = "SELECT * FROM profesores WHERE activo = 1 ORDER BY orden ASC, nombre ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesores - Academia del Lago</title>
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
        
        input, textarea, select { 
            width: 100%; 
            padding: 12px 15px; 
            border: 2px solid #e0e0e0; 
            border-radius: 6px; 
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input:focus, textarea:focus, select:focus {
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
        
        .profesores-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
            gap: 25px; 
            margin-top: 20px;
        }
        
        .profesor-card { 
            border: 1px solid #e0e0e0; 
            border-radius: 12px; 
            padding: 25px; 
            text-align: center;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .profesor-card:hover {
            transform: translateY(-5px);
        }
        
        .profesor-imagen { 
            width: 150px; 
            height: 150px; 
            border-radius: 50%; 
            object-fit: cover; 
            margin: 0 auto 20px; 
            border: 4px solid #1a3a5f;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .profesor-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #1a3a5f;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            border: 4px solid #1a3a5f;
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
            margin: 5px; 
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
        
        .profesor-info {
            margin: 10px 0;
        }
        
        .profesor-nombre {
            color: #1a3a5f;
            font-size: 1.3rem;
            margin-bottom: 5px;
        }
        
        .profesor-titulo {
            color: #e6b325;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .profesor-especialidad {
            color: #666;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
            background: white;
            border-radius: 8px;
            border: 2px dashed #ddd;
            grid-column: 1 / -1;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Gestión de Profesores - Academia del Lago</h1>
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
        
        <!-- Formulario para agregar profesor -->
        <div class="form-section">
            <h2 class="section-title">Agregar Nuevo Profesor</h2>
            
            <?php if (isset($mensaje_exito)): ?>
                <div class="mensaje exito"><?php echo $mensaje_exito; ?></div>
            <?php endif; ?>
            
            <?php if (isset($mensaje_error)): ?>
                <div class="mensaje error"><?php echo $mensaje_error; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nombre:</label>
                    <input type="text" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label>Título/Grado:</label>
                    <input type="text" name="titulo" required>
                </div>
                
                <div class="form-group">
                    <label>Especialidad:</label>
                    <select name="especialidad" required>
                        <option value="Jiu Jitsu">Jiu Jitsu</option>
                        <option value="Aikido">Aikido</option>
                        <option value="Judo">Judo</option>
                        <option value="Multiple">Múltiple</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Descripción:</label>
                    <textarea name="descripcion" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Imagen (opcional):</label>
                    <input type="file" name="imagen" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label>Orden de visualización:</label>
                    <input type="number" name="orden" value="0" min="0">
                </div>
                
                <button type="submit" name="agregar_profesor">Agregar Profesor</button>
            </form>
        </div>
        
        <!-- Lista de profesores -->
        <div class="form-section">
            <h2 class="section-title">Profesores Actuales</h2>
            
            <div class="profesores-grid">
                <?php if (count($profesores) > 0): ?>
                    <?php foreach ($profesores as $profesor): ?>
                        <div class="profesor-card">
                            <?php if (!empty($profesor['imagen'])): ?>
                                <img src="../uploads/profesores/<?php echo $profesor['imagen']; ?>" 
                                     alt="<?php echo $profesor['nombre']; ?>" class="profesor-imagen">
                            <?php else: ?>
                                <div class="profesor-placeholder">
                                    <?php echo substr($profesor['nombre'], 0, 1); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="profesor-nombre"><?php echo $profesor['nombre']; ?></div>
                            <div class="profesor-titulo"><?php echo $profesor['titulo']; ?></div>
                            <div class="profesor-especialidad"><?php echo $profesor['especialidad']; ?></div>
                            
                            <?php if (!empty($profesor['descripcion'])): ?>
                                <div class="profesor-info"><?php echo $profesor['descripcion']; ?></div>
                            <?php endif; ?>
                            
                            <div style="margin-top: 15px;">
                                <small style="color: #666;">Orden: <?php echo $profesor['orden']; ?></small>
                                <br>
                                <a href="eliminar_profesor.php?id=<?php echo $profesor['id']; ?>" class="btn btn-danger" 
                                   onclick="return confirm('¿Estás seguro de eliminar este profesor?')">Eliminar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>No hay profesores registrados</h3>
                        <p>Comienza agregando tu primer profesor usando el formulario de arriba.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
EOF
