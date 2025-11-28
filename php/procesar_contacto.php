cat > php/procesar_contacto.php << 'EOF'
<?php
// Incluir configuración de la base de datos
include_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $interes = htmlspecialchars(trim($_POST['interes']));
    $mensaje = htmlspecialchars(trim($_POST['mensaje']));
    
    // Validaciones básicas
    $errores = [];
    
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido";
    }
    
    if (empty($mensaje)) {
        $errores[] = "El mensaje es obligatorio";
    }
    
    // Si no hay errores, guardar en la base de datos
    if (empty($errores)) {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO contactos (nombre, email, telefono, interes, mensaje) 
                  VALUES (:nombre, :email, :telefono, :interes, :mensaje)";
        
        $stmt = $db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":interes", $interes);
        $stmt->bindParam(":mensaje", $mensaje);
        
        if ($stmt->execute()) {
            // Redirigir con mensaje de éxito
            header("Location: ../index.php?contacto=exito");
            exit();
        } else {
            $errores[] = "Error al guardar el mensaje. Por favor, intenta nuevamente.";
        }
    }
    
    // Si hay errores, redirigir con mensajes de error
    if (!empty($errores)) {
        $error_string = implode("|", $errores);
        header("Location: ../index.php?contacto=error&errores=" . urlencode($error_string));
        exit();
    }
} else {
    // Si no es POST, redirigir al inicio
    header("Location: ../index.php");
    exit();
}
?>
EOF
