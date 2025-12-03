<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); 
define('DB_PASS', '');
define('DB_NAME', 'sistema_cotizaciones');

// Configuración de la aplicación
// Detectar automáticamente el protocolo (HTTP/HTTPS)
function detectProtocol()
{
    // Verificar si está detrás de un proxy/load balancer
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        return $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ? 'https' : 'http';
    }

    // Verificar HTTPS directo
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return 'https';
    }

    // Verificar puerto
    if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
        return 'https';
    }

    return 'http';
}

// Detectar automáticamente el dominio
$protocol = detectProtocol();
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Determinar el path base
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';
$base_path = '';

// Si no estamos en la raíz, extraer el directorio base
if (dirname($script_name) != '.' && dirname($script_name) != '/') {
    $base_path = rtrim(dirname($script_name), '/');
}

// Construir la URL base
$app_url = $protocol . '://' . $host . $base_path;

// Para desarrollo local, usar configuración manual
if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
    $app_url = 'http://localhost/cotizacion';
}

// Para producción en Hostinger, ajustar si es necesario
// Descomentar y ajustar la siguiente línea con tu dominio real:
// if (strpos($host, 'cotizacion.jhanstudios.com') !== false) {
//     $app_url = 'http://cotizacion.jhanstudios.com'; // o https:// si tienes SSL
// }

define('APP_URL', rtrim($app_url, '/'));
define('SESSION_NAME', 'cotizacion_system');

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// Conexión a la base de datos
function getDB()
{
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Verificar si el usuario está autenticado
function isLoggedIn()
{
    // Primero verificar sesión activa
    if (isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
        return true;
    }

    // Si no hay sesión, verificar cookie de "recordar sesión"
    if (isset($_COOKIE['remember_token'])) {
        $token_valido = verificarRememberToken($_COOKIE['remember_token']);
        // Si el token era válido, la sesión ya fue restaurada, verificar nuevamente
        if ($token_valido && isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
            return true;
        }
    }

    return false;
}

// Generar token seguro para "recordar sesión"
function generarRememberToken()
{
    return bin2hex(random_bytes(32)); // Token de 64 caracteres
}

// Guardar token de "recordar sesión" en base de datos y cookie
function guardarRememberToken($usuario_id)
{
    $pdo = getDB();

    // Generar token único
    $token = generarRememberToken();

    // Expira en 30 días
    $expira_en = date('Y-m-d H:i:s', strtotime('+30 days'));

    // Guardar en base de datos
    $stmt = $pdo->prepare("INSERT INTO sesiones_remember (usuario_id, token, expira_en) VALUES (?, ?, ?)");
    $stmt->execute([$usuario_id, $token, $expira_en]);

    // Guardar en cookie (30 días, httpOnly para seguridad)
    $cookie_name = 'remember_token';
    $cookie_value = $token;
    $cookie_expire = time() + (30 * 24 * 60 * 60); // 30 días
    $cookie_path = '/';
    $cookie_domain = ''; // Dominio actual
    $cookie_secure = (detectProtocol() === 'https'); // Solo HTTPS en producción
    $cookie_httponly = true; // Prevenir acceso desde JavaScript

    setcookie($cookie_name, $cookie_value, $cookie_expire, $cookie_path, $cookie_domain, $cookie_secure, $cookie_httponly);

    return $token;
}

// Verificar token de "recordar sesión" y restaurar sesión si es válido
function verificarRememberToken($token)
{
    if (empty($token)) {
        return false;
    }

    $pdo = getDB();

    // Limpiar tokens expirados ocasionalmente (1% de probabilidad para no ralentizar)
    if (rand(1, 100) === 1) {
        limpiarTokensExpirados();
    }

    // Buscar token válido y no expirado
    $stmt = $pdo->prepare("SELECT usuario_id FROM sesiones_remember WHERE token = ? AND expira_en > NOW()");
    $stmt->execute([$token]);
    $sesion = $stmt->fetch();

    if ($sesion) {
        // Token válido, restaurar sesión
        $usuario_id = $sesion['usuario_id'];

        // Obtener datos del usuario
        $stmt = $pdo->prepare("SELECT id, usuario, nombre_completo FROM usuarios WHERE id = ?");
        $stmt->execute([$usuario_id]);
        $user = $stmt->fetch();

        if ($user) {
            // Restaurar sesión
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nombre'] = $user['nombre_completo'];
            $_SESSION['usuario'] = $user['usuario'];

            return true;
        }
    } else {
        // Token inválido o expirado, eliminar cookie
        eliminarRememberToken($token);
    }

    return false;
}

// Eliminar token de "recordar sesión"
function eliminarRememberToken($token = null)
{
    $pdo = getDB();

    // Si no se proporciona token, usar el de la cookie
    if ($token === null && isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
    }

    if ($token) {
        // Eliminar de base de datos
        $stmt = $pdo->prepare("DELETE FROM sesiones_remember WHERE token = ?");
        $stmt->execute([$token]);
    }

    // Eliminar cookie
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
        unset($_COOKIE['remember_token']);
    }
}

// Limpiar tokens expirados (ejecutar periódicamente)
function limpiarTokensExpirados()
{
    $pdo = getDB();
    $stmt = $pdo->prepare("DELETE FROM sesiones_remember WHERE expira_en < NOW()");
    $stmt->execute();
}

// Redirigir si no está autenticado
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: ' . APP_URL . '/index.php');
        exit;
    }
}

// Obtener información del usuario actual
function getCurrentUser()
{
    if (!isLoggedIn()) {
        return null;
    }

    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id, usuario, email, nombre_completo FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    return $stmt->fetch();
}

// Generar código único para cotización
function generarCodigoUnico()
{
    return bin2hex(random_bytes(16));
}
