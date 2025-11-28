cat > admin/galeria.php << 'EOF'
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Procesar subida de archivos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subir_archivo'])) {
    $titulo = htmlspecialchars(trim($_POST['titulo']));
    $descripcion = htmlspecialchars(trim($_POST['descripcion']));
    $tipo = $_POST['tipo'];
    
    if (!empty($_FILES['archivo']['name'])) {
        $target_dir = "../uploads/galeria/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Validar tipo de archivo
        $allowed_image_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $allowed_video_extensions = ['mp4', 'avi', 'mov', 'wmv'];
        
        if (($tipo == 'imagen' && in_array($file_extension, $allowed_image_extensions)) ||
            ($tipo == 'video' && in_array($file_extension, $allowed_video_extensions))) {
            
            if (move_uploaded_file($_FILES['archivo']['tmp_name'], $target_file)) {
                $query = "INSERT INTO galeria (titulo, descripcion, archivo, tipo) 
                         VALUES (:titulo, :descripcion, :archivo, :tipo)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(":titulo", $titulo);
                $stmt->bindParam(":descripcion", $descripcion);
                $stmt->bindParam(":archivo", $new_filename);
                $stmt->bindParam(":tipo", $tipo);
                
                if ($stmt->execute()) {
                    $mensaje_exito = "Archivo subido exitosamente";
                } else {
                    $mensaje_error = "Error al guardar en la base de datos";
                }
            } else {
                $mensaje_error = "Error al subir el archivo";
            }
        } else {
            $mensaje_error = "Tipo de archivo no permitido";
        }
    }
}

// Obtener galería
$query = "SELECT * FROM galeria WHERE activo = 1 ORDER BY fecha_subida DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$galeria = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería - Academia del Lago</title>
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
        
        .galeria-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); 
            gap: 20px; 
            margin-top: 20px;
        }
        
        .galeria-item { 
            border: 1px solid #e0e0e0; 
            border-radius: 8px; 
            overflow: hidden;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .galeria-item img, .galeria-item video { 
            width: 100%; 
            height: 200px; 
            object-fit: cover; 
            display: block;
        }
        
        .galeria-info { 
            padding: 15px; 
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
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
            background: white;
            border-radius: 8px;
            border: 2px dashed #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Gestión de Galería - Academia del Lago</h1>
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
        
        <!-- Formulario para subir archivos -->
        <div class="form-section">
            <h2 class="section-title">Subir Nuevo Archivo</h2>
            
            <?php if (isset($mensaje_exito)): ?>
                <div class="mensaje exito"><?php echo $mensaje_exito; ?></div>
            <?php endif; ?>
            
            <?php if (isset($mensaje_error)): ?>
                <div class="mensaje error"><?php echo $mensaje_error; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Título:</label>
                    <input type="text" name="titulo" required>
                </div>
                
                <div class="form-group">
                    <label>Descripción:</label>
                    <textarea name="descripcion" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Tipo:</label>
                    <select name="tipo" required>
                        <option value="imagen">Imagen</option>
                        <option value="video">Video</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Archivo:</label>
                    <input type="file" name="archivo" accept="image/*,video/*" required>
                </div>
                
                <button type="submit" name="subir_archivo">Subir Archivo</button>
            </form>
        </div>
        
        <!-- Galería existente -->
        <div class="form-section">
            <h2 class="section-title">Galería Actual</h2>
            
            <?php if (count($galeria) > 0): ?>
                <div class="galeria-grid">
                    <?php foreach ($galeria as $item): ?>
                        <div class="galeria-item">
                            <?php if ($item['tipo'] == 'imagen'): ?>
                                <img src="../uploads/galeria/<?php echo $item['archivo']; ?>" alt="<?php echo $item['titulo']; ?>">
                            <?php else: ?>
                                <video controls>
                                    <source src="../uploads/galeria/<?php echo $item['archivo']; ?>" type="video/mp4">
                                </video>
                            <?php endif; ?>
                            <div class="galeria-info">
                                <strong><?php echo $item['titulo']; ?></strong>
                                <p><?php echo $item['descripcion']; ?></p>
                                <small><?php echo $item['fecha_subida']; ?></small>
                                <br>
                                <a href="eliminar_archivo.php?id=<?php echo $item['id']; ?>" class="btn btn-danger" 
                                   onclick="return confirm('¿Estás seguro de eliminar este archivo?')">Eliminar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <h3>No hay archivos en la galería</h3>
                    <p>Comienza subiendo tu primer archivo usando el formulario de arriba.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
EOF
