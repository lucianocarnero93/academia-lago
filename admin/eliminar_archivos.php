cat > admin/eliminar_archivo.php << 'EOF'
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
    
    // Obtener información del archivo para eliminarlo físicamente
    $query = "SELECT archivo FROM galeria WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $archivo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Eliminar archivo físico
        $file_path = "../uploads/galeria/" . $archivo['archivo'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Eliminar de la base de datos
        $query = "DELETE FROM galeria WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }
}

header("Location: galeria.php");
exit();
?>
EOF
