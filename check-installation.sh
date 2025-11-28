#!/bin/bash
echo "=== Verificación de Instalación Academia del Lago ==="
echo

# Verificar estructura de carpetas
echo "1. Verificando estructura de carpetas..."
folders=("css" "js" "config" "php" "admin" "database" "uploads/galeria" "uploads/profesores" "images")
for folder in "${folders[@]}"; do
    if [ -d "$folder" ]; then
        echo "   ✅ $folder"
    else
        echo "   ❌ $folder - FALTANTE"
    fi
done

echo

# Verificar archivos principales
echo "2. Verificando archivos principales..."
files=("index.php" "css/style.css" "css/responsive.css" "js/main.js" "config/database.php" 
       "php/procesar_contacto.php" "php/procesar_newsletter.php" "admin/login.php" 
       "admin/dashboard.php" "admin/galeria.php" "admin/profesores.php" "admin/horarios.php"
       "admin/logout.php" "database/setup.php")

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "   ✅ $file"
    else
        echo "   ❌ $file - FALTANTE"
    fi
done

echo

# Verificar permisos
echo "3. Verificando permisos..."
if [ -w "uploads/" ]; then
    echo "   ✅ Permisos de escritura en uploads/"
else
    echo "   ❌ Problema con permisos en uploads/"
fi

echo

# Verificar base de datos
echo "4. Verificando base de datos..."
if [ -f "academia_lago.db" ]; then
    echo "   ✅ Base de datos SQLite creada"
    # Verificar tablas
    if command -v sqlite3 &> /dev/null; then
        tables=$(sqlite3 academia_lago.db ".tables")
        if [ -n "$tables" ]; then
            echo "   ✅ Tablas creadas en la base de datos"
        else
            echo "   ❌ No hay tablas en la base de datos"
        fi
    fi
else
    echo "   ⚠️  La base de datos se creará automáticamente al cargar la página"
fi

echo
echo "=== Instrucciones ==="
echo "1. Accede a: http://localhost/academia-lago/"
echo "2. Panel admin: http://localhost/academia-lago/admin/login.php"
echo "3. Usuario: admin, Contraseña: academia123"
echo "4. Ejecuta una vez: http://localhost/academia-lago/database/setup.php"
echo "5. ELIMINA database/setup.php después de usarlo"
