<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

$password = $data['password'] ?? '';

if (empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'La contraseña es requerida']);
    exit;
}

try {
    $pdo = getDB();
    $usuario_id = $_SESSION['usuario_id'];

    // Verificar contraseña
    $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
        exit;
    }

    // Iniciar transacción
    $pdo->beginTransaction();

    // Eliminar todas las cotizaciones del usuario (CASCADE debería hacerlo automáticamente, pero lo hacemos explícitamente)
    $stmt = $pdo->prepare("DELETE FROM cotizaciones WHERE usuario_id = ?");
    $stmt->execute([$usuario_id]);

    // Eliminar el usuario
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);

    // Confirmar transacción
    $pdo->commit();

    // Eliminar token de "recordar sesión" si existe
    if (isset($_COOKIE['remember_token'])) {
        eliminarRememberToken($_COOKIE['remember_token']);
    }

    // Destruir sesión
    session_destroy();

    echo json_encode(['success' => true, 'message' => 'Cuenta eliminada exitosamente']);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>

