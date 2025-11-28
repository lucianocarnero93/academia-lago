cat > php/procesar_newsletter.php << 'EOF'
<?php
// Incluir configuración de la base de datos
include_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener email del formulario
    $email = htmlspecialchars(trim($_POST['email_newsletter']));
    
    // Validar email
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $database = new Database();
        $db = $database->getConnection();
        
        // Verificar si el email ya existe
        $query = "SELECT id FROM newsletter WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Email ya existe
            header("Location: ../index.php?newsletter=ya_registrado");
            exit();
        } else {
            // Insertar nuevo email
            $query = "INSERT INTO newsletter (email) VALUES (:email)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":email", $email);
            
            if ($stmt->execute()) {
                header("Location: ../index.php?newsletter=exito");
                exit();
            } else {
                header("Location: ../index.php?newsletter=error");
                exit();
            }
        }
    } else {
        header("Location: ../index.php?newsletter=email_invalido");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>
EOF
