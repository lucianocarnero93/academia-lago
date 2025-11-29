<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Incluir config.php que ya existe
include_once '../config.php';
$database = new Database();
$db = $database->getConnection();

// Obtener estadísticas
$stats = [];

// Contactos
$query = "SELECT COUNT(*) as total FROM contactos";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['contactos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Newsletter
$query = "SELECT COUNT(*) as total FROM newsletter WHERE activo = 1";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['newsletter'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Profesores
$query = "SELECT COUNT(*) as total FROM profesores WHERE activo = 1";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['profesores'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Galería - USAR LA ESTRUCTURA EXISTENTE
$query = "SELECT COUNT(*) as total FROM galeria WHERE activo = 1";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['galeria'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Últimos contactos
$query = "SELECT nombre, email, telefono, interes, fecha_contacto FROM contactos ORDER BY fecha_contacto DESC LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute();
$ultimos_contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Academia del Lago</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f5f5f5; color: #333; }
        .header { background: #1a3a5f; color: white; padding: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header-content { width: 90%; max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 25px; border-radius: 12px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border-left: 4px solid #1a3a5f; }
        .stat-card h3 { color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
        .stat-card p { font-size: 2rem; font-weight: bold; color: #1a3a5f; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        th { background: #f8f9fa; font-weight: 600; color: #1a3a5f; }
        .btn { display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin: 5px; font-weight: 500; transition: all 0.3s; }
        .btn-primary { background: #1a3a5f; color: white; }
        .btn-primary:hover { background: #2a5a8f; }
        .btn-secondary { background: #e6b325; color: #1a3a5f; }
        .btn-secondary:hover { background: #d4a220; }
        .logout { float: right; }
        .nav { display: flex; gap: 15px; margin-bottom: 30px; flex-wrap: wrap; }
        .section { background: white; padding: 25px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .section h2 { color: #1a3a5f; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e6b325; }
        .welcome { text-align: center; padding: 40px; background: linear-gradient(135deg, #1a3a5f, #2a5a8f); color: white; border-radius: 12px; margin-bottom: 30px; }
        .welcome h1 { margin-bottom: 10px; }
        .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Dashboard - Academia del Lago</h1>
            <a href="logout.php" class="btn btn-secondary logout">Cerrar Sesión</a>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome">
            <h1>Bienvenido al Panel de Administración</h1>
            <p>Gestiona el contenido de tu sitio web desde aquí</p>
        </div>
        
        <div class="nav">
            <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
            <a href="galeria.php" class="btn btn-secondary">Galería</a>
            <a href="profesores.php" class="btn btn-secondary">Profesores</a>
            <a href="horarios.php" class="btn btn-secondary">Horarios</a>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Contactos</h3>
                <p><?php echo $stats['contactos']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Newsletter</h3>
                <p><?php echo $stats['newsletter']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Profesores</h3>
                <p><?php echo $stats['profesores']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Galería</h3>
                <p><?php echo $stats['galeria']; ?></p>
            </div>
        </div>
        
        <div class="section">
            <h2>Últimos Contactos</h2>
            <?php if (count($ultimos_contactos) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Interés</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimos_contactos as $contacto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($contacto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($contacto['email']); ?></td>
                                <td><?php echo htmlspecialchars($contacto['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($contacto['interes']); ?></td>
                                <td><?php echo $contacto['fecha_contacto']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #666; padding: 20px;">No hay contactos registrados aún.</p>
            <?php endif; ?>
        </div>
        
        <div class="quick-actions">
            <a href="galeria.php" class="btn btn-primary" style="text-align: center; display: block;">
                <h3>📷 Galería</h3>
                <p>Gestionar imágenes y videos</p>
            </a>
            <a href="profesores.php" class="btn btn-primary" style="text-align: center; display: block;">
                <h3>👥 Profesores</h3>
                <p>Administrar instructores</p>
            </a>
            <a href="horarios.php" class="btn btn-primary" style="text-align: center; display: block;">
                <h3>🕒 Horarios</h3>
                <p>Configurar clases</p>
            </a>
        </div>
    </div>
</body>
</html>
