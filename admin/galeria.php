<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include_once '../config.php';
$database = new Database();
$db = $database->getConnection();

$mensaje = '';
$error = '';

// Procesar subida de archivos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subir_archivo'])) {
    $titulo = htmlspecialchars(trim($_POST['titulo']));
    $descripcion = htmlspecialchars(trim($_POST['descripcion']));
    $categoria = htmlspecialchars(trim($_POST['categoria']));
    
    // Procesar imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $archivo_tmp = $_FILES['imagen']['tmp_name'];
        $nombre_archivo = basename($_FILES['imagen']['name']);
        $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
        
        // Extensiones permitidas
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($extension, $extensiones_permitidas)) {
            // Crear directorio de uploads si no existe
            $directorio_uploads = '../uploads/galeria/';
            if (!is_dir($directorio_uploads)) {
                mkdir($directorio_uploads, 0755, true);
            }
            
            // Generar nombre único
            $nuevo_nombre = uniqid() . '.' . $extension;
            $ruta_destino = $directorio_uploads . $nuevo_nombre;
            
            if (move_uploaded_file($archivo_tmp, $ruta_destino)) {
                // Insertar en base de datos
                try {
                    $query = "INSERT INTO galeria (titulo, imagen, descripcion, categoria, activo) VALUES (?, ?, ?, ?, 1)";
                    $stmt = $db->prepare($query);
                    $stmt->execute([$titulo, $nuevo_nombre, $descripcion, $categoria]);
                    $mensaje = "Imagen subida correctamente";
                } catch (PDOException $e) {
                    $error = "Error al guardar en base de datos: " . $e->getMessage();
                    // Eliminar archivo subido si hay error en BD
                    unlink($ruta_destino);
                }
            } else {
                $error = "Error al mover el archivo";
            }
        } else {
            $error = "Formato de archivo no permitido. Use: " . implode(', ', $extensiones_permitidas);
        }
    } else {
        $error = "Por favor seleccione una imagen válida";
    }
}

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    try {
        // Obtener nombre del archivo
        $query = "SELECT imagen FROM galeria WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $archivo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($archivo) {
            // Eliminar archivo físico
            $ruta_archivo = '../uploads/galeria/' . $archivo['imagen'];
            if (file_exists($ruta_archivo)) {
                unlink($ruta_archivo);
            }
            
            // Eliminar de base de datos
            $query = "DELETE FROM galeria WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $mensaje = "Imagen eliminada correctamente";
        }
    } catch (PDOException $e) {
        $error = "Error al eliminar: " . $e->getMessage();
    }
}

// Obtener archivos de la galería
$query = "SELECT * FROM galeria WHERE activo = 1 ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería - Academia del Lago</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f5f5f5; color: #333; }
        .header { background: #1a3a5f; color: white; padding: 20px 0; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .btn { display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin: 5px; border: none; cursor: pointer; }
        .btn-primary { background: #1a3a5f; color: white; }
        .btn-secondary { background: #e6b325; color: #1a3a5f; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #28a745; color: white; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        th { background: #f8f9fa; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { 
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; 
        }
        .alert { padding: 15px; margin: 15px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .imagen-miniatura { width: 100px; height: 80px; object-fit: cover; border-radius: 4px; }
        .seccion { background: white; padding: 25px; border-radius: 8px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div style="width: 90%; max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <h1>Galería - Academia del Lago</h1>
            <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
        </div>
    </div>
    
    <div class="container">
        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Formulario para subir imágenes -->
        <div class="seccion">
            <h2>Subir Nueva Imagen</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select id="categoria" name="categoria">
                        <option value="Clases">Clases</option>
                        <option value="Eventos">Eventos</option>
                        <option value="Grados">Grados</option>
                        <option value="Competiciones">Competiciones</option>
                        <option value="General">General</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*" required>
                    <small>Formatos permitidos: JPG, JPEG, PNG, GIF, WEBP</small>
                </div>
                
                <button type="submit" name="subir_archivo" class="btn btn-success">Subir Imagen</button>
            </form>
        </div>

        <!-- Lista de imágenes existentes -->
        <div class="seccion">
            <h2>Imágenes en la Galería</h2>
            <?php if (count($archivos) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($archivos as $archivo): ?>
                            <tr>
                                <td>
                                    <?php if (file_exists('../uploads/galeria/' . $archivo['imagen'])): ?>
                                        <img src="../uploads/galeria/<?php echo $archivo['imagen']; ?>" 
                                             alt="<?php echo htmlspecialchars($archivo['titulo']); ?>" 
                                             class="imagen-miniatura">
                                    <?php else: ?>
                                        <span style="color: #999;">No encontrada</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($archivo['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($archivo['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($archivo['categoria']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($archivo['created_at'])); ?></td>
                                <td>
                                    <a href="?eliminar=<?php echo $archivo['id']; ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('¿Está seguro de eliminar esta imagen?')">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay imágenes en la galería. Sube la primera imagen usando el formulario arriba.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
