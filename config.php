<?php
class Database {
    private $host = "localhost";
    private $db_name = "academia_lago";
    private $username = "root";
    private $password = "";
    public $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            // Intentar con MySQL primero
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $e) {
            // Si falla MySQL, usar SQLite
            try {
                $sqlite_path = __DIR__ . '/../academia_lago.db';
                $this->conn = new PDO("sqlite:$sqlite_path");
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Crear tablas si no existen
                $this->createSQLiteTables();
                
            } catch(PDOException $e2) {
                echo "Error de conexión: " . $e2->getMessage();
            }
        }
        
        return $this->conn;
    }
    
    private function createSQLiteTables() {
        $tables = [
            "CREATE TABLE IF NOT EXISTS contactos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nombre TEXT NOT NULL,
                email TEXT NOT NULL,
                telefono TEXT,
                interes TEXT,
                mensaje TEXT NOT NULL,
                fecha_contacto DATETIME DEFAULT CURRENT_TIMESTAMP
            )",
            
            "CREATE TABLE IF NOT EXISTS newsletter (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT NOT NULL UNIQUE,
                fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
                activo BOOLEAN DEFAULT 1
            )",
            
            "CREATE TABLE IF NOT EXISTS profesores (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nombre TEXT NOT NULL,
                titulo TEXT NOT NULL,
                especialidad TEXT NOT NULL,
                descripcion TEXT,
                imagen TEXT,
                orden INTEGER DEFAULT 0,
                activo BOOLEAN DEFAULT 1
            )",
            
            "CREATE TABLE IF NOT EXISTS galeria (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                titulo TEXT NOT NULL,
                descripcion TEXT,
                archivo TEXT NOT NULL,
                tipo TEXT NOT NULL,
                fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
                activo BOOLEAN DEFAULT 1
            )",
            
            "CREATE TABLE IF NOT EXISTS horarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                disciplina TEXT NOT NULL,
                dia_semana TEXT NOT NULL,
                hora_inicio TIME NOT NULL,
                hora_fin TIME NOT NULL,
                nivel TEXT NOT NULL,
                profesor_id INTEGER,
                activo BOOLEAN DEFAULT 1
            )"
        ];
        
        foreach ($tables as $table) {
            $this->conn->exec($table);
        }
        
        // Insertar datos de ejemplo
        $this->insertSampleData();
    }
    
    private function insertSampleData() {
        // Verificar si ya existen datos
        $stmt = $this->conn->query("SELECT COUNT(*) FROM profesores");
        if ($stmt->fetchColumn() == 0) {
            // Insertar profesores de ejemplo
            $profesores = [
                ['Carlos Rodríguez', '5to Dan Jiu Jitsu', 'Jiu Jitsu', 'Especialista en defensa personal con más de 15 años de experiencia'],
                ['María González', '4to Dan Aikido', 'Aikido', 'Instructora certificada internacionalmente'],
                ['Luis Fernández', '3er Dan Judo', 'Judo', 'Competidor nacional e internacional']
            ];
            
            foreach ($profesores as $profesor) {
                $stmt = $this->conn->prepare("INSERT INTO profesores (nombre, titulo, especialidad, descripcion) VALUES (?, ?, ?, ?)");
                $stmt->execute($profesor);
            }
        }
    }
}
?>
