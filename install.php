<?php
/**
 * Script de instalación del Sistema de Cotizaciones
 * Ejecutar una sola vez para crear el usuario inicial
 */

require_once 'includes/config.php';

// Solo permitir ejecutar si no hay usuarios en la base de datos
$pdo = getDB();
$stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios");
$count = $stmt->fetch()['count'];

if ($count > 0) {
    die('El sistema ya está instalado. El usuario ya existe en la base de datos.');
}

// Crear usuario administrador
$password_hash = password_hash('admin123', PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, email, password, nombre_completo) VALUES (?, ?, ?, ?)");
    $stmt->execute(['admin', 'admin@ejemplo.com', $password_hash, 'Administrador']);

    echo "<h1>✅ Instalación Completada</h1>";
    echo "<p>Usuario creado exitosamente:</p>";
    echo "<ul>";
    echo "<li><strong>Usuario:</strong> admin</li>";
    echo "<li><strong>Contraseña:</strong> admin123</li>";
    echo "</ul>";
    echo "<p><strong>⚠️ IMPORTANTE:</strong> Cambia la contraseña después del primer acceso.</p>";
    echo "<p><a href='index.php'>Ir al Login</a></p>";
    echo "<p><strong>Nota:</strong> Puedes eliminar este archivo (install.php) después de la instalación por seguridad.</p>";

} catch (Exception $e) {
    die("Error al crear el usuario: " . $e->getMessage());
}

