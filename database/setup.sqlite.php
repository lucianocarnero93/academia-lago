<?php
$db = new SQLite3(__DIR__ . '/academia_lago.sqlite');

// Crear tabla de usuarios
$db->exec("
CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    telefono TEXT,
    curso_interes TEXT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
)
");

// Crear tabla de cursos
$db->exec("
CREATE TABLE IF NOT EXISTS cursos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    descripcion TEXT,
    duracion TEXT,
    precio DECIMAL(10,2),
    activo BOOLEAN DEFAULT 1
)
");

echo "Base de datos SQLite configurada correctamente";
?>
