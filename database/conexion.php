<?php
class Database {
    private static $instance = null;
    private $db;
    
    private function __construct() {
        try {
            // Ruta CORREGIDA - usar .db que ya existe
            $db_path = __DIR__ . '/academia_lago.db';
            
            // Conectar a SQLite - el archivo YA EXISTE
            $this->db = new SQLite3($db_path);
            $this->db->enableExceptions(true);
            
            // Configuraciones
            $this->db->exec('PRAGMA encoding = "UTF-8"');
            $this->db->exec('PRAGMA foreign_keys = ON');
            $this->db->busyTimeout(5000);
            
        } catch (Exception $e) {
            error_log("Error SQLite: " . $e->getMessage());
            die("Error conectando a la base de datos.");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->db;
    }
}

// Función de conveniencia
function getDB() {
    return Database::getInstance();
}

// Función para compatibilidad
function getDBConnection() {
    return getDB();
}
?>
