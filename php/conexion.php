<?php
require_once __DIR__ . '/../database/conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');
    $curso = trim($_POST['curso'] ?? 'General');
    
    // Validaciones
    if (empty($nombre) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Nombre y email son obligatorios']);
        exit;
    }
    
    try {
        $db = getDB();
        
        $stmt = $db->prepare("
            INSERT INTO contactos (nombre, email, telefono, mensaje, curso) 
            VALUES (:nombre, :email, :telefono, :mensaje, :curso)
        ");
        
        $stmt->bindValue(':nombre', $nombre, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':telefono', $telefono, SQLITE3_TEXT);
        $stmt->bindValue(':mensaje', $mensaje, SQLITE3_TEXT);
        $stmt->bindValue(':curso', $curso, SQLITE3_TEXT);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Mensaje enviado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
