<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include_once '../config.php';
$database = new Database();
$db = $database->getConnection();

// Obtener profesores
$query = "SELECT * FROM profesores WHERE activo = 1 ORDER BY orden";
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
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f5f5f5; color: #333; }
        .header { background: #1a3a5f; color: white; padding: 20px 0; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .btn { display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin: 5px; }
        .btn-primary { background: #1a3a5f; color: white; }
        .btn-secondary { background: #e6b325; color: #1a3a5f; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="header">
        <div style="width: 90%; max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <h1>Profesores - Academia del Lago</h1>
            <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
        </div>
    </div>
    
    <div class="container">
        <h2>Lista de Profesores</h2>
        <?php if (count($profesores) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Título</th>
                        <th>Especialidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($profesores as $profesor): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($profesor['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($profesor['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($profesor['especialidad']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay profesores registrados.</p>
        <?php endif; ?>
    </div>
</body>
</html>
