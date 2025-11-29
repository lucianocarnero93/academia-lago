<?php
require_once __DIR__ . '/../conexion.php';

function initializeDatabase() {
    $db = getDBConnection();
    
    try {
        // Crear tabla de contactos/inscripciones
        $db->exec("
        CREATE TABLE IF NOT EXISTS inscripciones (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL,
            email TEXT NOT NULL,
            telefono TEXT,
            curso TEXT NOT NULL,
            mensaje TEXT,
            fecha_inscripcion DATETIME DEFAULT CURRENT_TIMESTAMP
        )
        ");
        
        // Crear tabla de cursos
        $db->exec("
        CREATE TABLE IF NOT EXISTS cursos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL UNIQUE,
            descripcion TEXT,
            duracion TEXT,
            precio DECIMAL(10,2),
            imagen TEXT,
            activo BOOLEAN DEFAULT 1,
            fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
        )
        ");
        
        // Insertar cursos de ejemplo si no existen
        $cursos = [
            ['Inglés Básico', 'Curso inicial de inglés para principiantes', '3 meses', 299.00, 'ingles.jpg'],
            ['Programación Web', 'HTML, CSS, JavaScript y PHP', '4 meses', 499.00, 'programacion.jpg'],
            ['Marketing Digital', 'Estrategias de marketing online', '2 meses', 399.00, 'marketing.jpg'],
            ['Excel Avanzado', 'Funciones avanzadas y macros', '6 semanas', 249.00, 'excel.jpg']
        ];
        
        foreach ($cursos as $curso) {
            $stmt = $db->prepare("
                INSERT OR IGNORE INTO cursos (nombre, descripcion, duracion, precio, imagen) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bindValue(1, $curso[0], SQLITE3_TEXT);
            $stmt->bindValue(2, $curso[1], SQLITE3_TEXT);
            $stmt->bindValue(3, $curso[2], SQLITE3_TEXT);
            $stmt->bindValue(4, $curso[3], SQLITE3_FLOAT);
            $stmt->bindValue(5, $curso[4], SQLITE3_TEXT);
            $stmt->execute();
        }
        
        echo "Base de datos inicializada correctamente!";
        
    } catch (Exception $e) {
        die("Error inicializando base de datos: " . $e->getMessage());
    }
}

// Ejecutar inicialización si se llama directamente
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    initializeDatabase();
}
?>
