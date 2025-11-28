cat > admin/eliminar_profesor.php << 'EOF'
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Obtener información del profesor para eliminar imagen
    $query = "SELECT imagen FROM profesores WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $profesor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Eliminar imagen física si existe
        if (!empty($profesor['imagen'])) {
            $file_path = "../uploads/profesores/" . $profesor['imagen'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        // Eliminar de la base de datos
        $query = "DELETE FROM profesores WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }
}

header("Location: profesores.php");
exit();
?>
EOF
