cat > admin/login.php << 'EOF'
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Credenciales simples (deberías usar hash en producción)
    $valid_username = "admin";
    $valid_password = "academia123";
    
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Academia del Lago</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .login-container { 
            max-width: 400px; 
            width: 90%;
            margin: 20px;
            padding: 40px; 
            background: white; 
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .login-container h2 {
            text-align: center;
            color: #1a3a5f;
            margin-bottom: 30px;
            font-size: 1.8rem;
        }
        
        .form-group { 
            margin-bottom: 20px; 
        }
        
        label { 
            display: block; 
            margin-bottom: 8px;
            color: #1a3a5f;
            font-weight: 600;
        }
        
        input[type="text"], 
        input[type="password"] { 
            width: 100%; 
            padding: 12px 15px; 
            border: 2px solid #e0e0e0; 
            border-radius: 6px; 
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus, 
        input[type="password"]:focus {
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
            width: 100%;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background: #2a5a8f;
        }
        
        .error { 
            color: #dc3545; 
            margin-bottom: 20px; 
            padding: 10px;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            text-align: center;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #1a3a5f;
            text-decoration: none;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Administración</h2>
        <h3 style="text-align: center; color: #666; margin-bottom: 30px;">Academia del Lago</h3>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" name="username" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Ingresar</button>
        </form>
        
        <div class="back-link">
            <a href="../index.php">← Volver al sitio principal</a>
        </div>
    </div>
</body>
</html>
EOF
