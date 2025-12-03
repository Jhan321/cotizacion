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
$cotizacion_id = $data['id'] ?? null;

if (!$cotizacion_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de cotización requerido']);
    exit;
}

try {
    $pdo = getDB();
    $usuario_id = $_SESSION['usuario_id'];

    // Verificar que la cotización pertenece al usuario
    $stmt = $pdo->prepare("SELECT id FROM cotizaciones WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$cotizacion_id, $usuario_id]);

    if (!$stmt->fetch()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'No tienes permiso para eliminar esta cotización']);
        exit;
    }

    // Eliminar la cotización
    $stmt = $pdo->prepare("DELETE FROM cotizaciones WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$cotizacion_id, $usuario_id]);

    echo json_encode(['success' => true, 'message' => 'Cotización eliminada']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

