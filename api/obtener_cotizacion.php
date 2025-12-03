<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

$id = $_GET['id'] ?? null;
$codigo = $_GET['codigo'] ?? null;

if (!$id && !$codigo) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID o código requerido']);
    exit;
}

try {
    $pdo = getDB();

    if ($id) {
        // Si se pasa ID, verificar que el usuario esté autenticado y sea el dueño
        if (!isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM cotizaciones WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $_SESSION['usuario_id']]);
    } else {
        // Si se pasa código, es acceso público (para compartir)
        $stmt = $pdo->prepare("SELECT * FROM cotizaciones WHERE codigo_unico = ?");
        $stmt->execute([$codigo]);
    }

    $cotizacion = $stmt->fetch();

    if (!$cotizacion) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Cotización no encontrada']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $cotizacion]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

