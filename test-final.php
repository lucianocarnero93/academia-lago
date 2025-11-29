<?php
require_once 'database/conexion.php';

echo "<h2>✅ Test Final - Conexión Unificada</h2>";

try {
    $db = getDB();
    
    // Verificar conexión
    $version = $db->querySingle('SELECT sqlite_version()');
    echo "<p style='color: green;'>✅ Conectado a SQLite v$version</p>";
    
    // Verificar tablas
    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
    
    echo "<h3>📊 Tablas en la base de datos:</h3>";
    echo "<ul>";
    while ($table = $tables->fetchArray(SQLITE3_ASSOC)) {
        echo "<li>" . $table['name'] . "</li>";
    }
    echo "</ul>";
    
    // Probar inserción
    $test_name = "Test_" . date('Y-m-d_H-i-s');
    $stmt = $db->prepare("INSERT INTO contactos (nombre, email, mensaje) VALUES (?, ?, ?)");
    $stmt->bindValue(1, $test_name, SQLITE3_TEXT);
    $stmt->bindValue(2, "test@ejemplo.com", SQLITE3_TEXT);
    $stmt->bindValue(3, "Mensaje de prueba", SQLITE3_TEXT);
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ Inserción de prueba exitosa</p>";
        
        // Limpiar prueba
        $db->exec("DELETE FROM contactos WHERE nombre LIKE 'Test_%'");
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='/academia-lago/'>Volver al sitio principal</a></p>";
?>
