cat > database/setup.php << 'EOF'
<?php
// Este archivo crea la base de datos y tablas iniciales
// EJECUTAR SOLO UNA VEZ Y LUEGO ELIMINAR POR SEGURIDAD

include_once '../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "<h2>Configuración de Base de Datos</h2>";
    echo "<p>Base de datos y tablas creadas exitosamente.</p>";
    
    // Verificar tablas creadas
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Tablas creadas:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    echo "<p><strong>IMPORTANTE:</strong> Elimina este archivo (setup.php) por seguridad.</p>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
EOF
