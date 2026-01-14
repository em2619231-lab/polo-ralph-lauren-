<?php
require_once 'conexion.php';

echo "<h1>Configuración de Base de Datos</h1>";

try {
    // Primero, crear la base de datos sin seleccionarla
    $temp_conn = new PDO("mysql:host=localhost", "root", "");
    $temp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Crear base de datos
    $temp_conn->exec("CREATE DATABASE IF NOT EXISTS tienda_polo");
    echo "✅ Base de datos creada exitosamente!<br>";
    
    $temp_conn = null;
    
    // Ahora conectar a la base de datos creada
    $database = new Database();
    $conn = $database->getConnection();
    
    // Leer y ejecutar el script SQL
    $sql = file_get_contents('tienda_polo.sql');
    $conn->exec($sql);
    echo "✅ Tablas creadas exitosamente!<br>";
    
    // Insertar datos iniciales
    $data_sql = file_get_contents('datos_iniciales.sql');
    $conn->exec($data_sql);
    echo "✅ Datos iniciales insertados!<br>";
    
    echo "<h2>✅ Configuración completada exitosamente!</h2>";
    echo "<p>Ahora puedes acceder a tu tienda en: <a href='../polo.html'>polo.html</a></p>";
    
} catch(PDOException $e) {
    echo "<h2>❌ Error durante la configuración:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}


echo "✅ Tabla 'detalles_orden' creada<br>";

$conn->exec("CREATE TABLE IF NOT EXISTS usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    tipo ENUM('cliente', 'admin') DEFAULT 'cliente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE
)");
echo "✅ Tabla 'usuarios' creada<br>";

// INSERTAR USUARIO ADMIN POR DEFECTO
$password_hash = password_hash('1234', PASSWORD_DEFAULT);
$conn->exec("INSERT IGNORE INTO usuarios (nombre, email, password, telefono, tipo) VALUES
    ('Administrador', 'admin@ralphlauren.com', '$password_hash', '555-123-4567', 'admin'),
    ('Juan Pérez', 'cliente@ejemplo.com', '$password_hash', '555-987-6543', 'cliente')");
echo "✅ Usuarios de ejemplo insertados<br>";

echo "<p><strong>Credenciales:</strong></p>";
echo "<ul>";
echo "<li><strong>Admin:</strong> admin@ralphlauren.com / 1234</li>";
echo "<li><strong>Cliente:</strong> cliente@ejemplo.com / 1234</li>";
echo "</ul>";



?>