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

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de cotización requerido']);
    exit;
}

try {
    $pdo = getDB();
    $usuario_id = $_SESSION['usuario_id'];
    $cotizacion_id = intval($data['id']);

    // Obtener la cotización original
    $stmt = $pdo->prepare("SELECT * FROM cotizaciones WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$cotizacion_id, $usuario_id]);
    $cotizacion_original = $stmt->fetch();

    if (!$cotizacion_original) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Cotización no encontrada o no tienes permiso']);
        exit;
    }

    // Preparar datos para la copia
    $titulo_copia = $cotizacion_original['titulo'] . ' (- copia)';
    $codigo_unico = generarCodigoUnico();

    // Crear la copia de la cotización
    $stmt = $pdo->prepare("INSERT INTO cotizaciones
        (usuario_id, titulo, cliente, desarrollador, validez,
         color_primario, color_secundario, color_acento, logo_url,
         contenido_requisitos, contenido_estilo, contenido_precios,
         contenido_terminos, info_contacto, codigo_unico)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $usuario_id,
        $titulo_copia,
        $cotizacion_original['cliente'],
        $cotizacion_original['desarrollador'],
        $cotizacion_original['validez'],
        $cotizacion_original['color_primario'],
        $cotizacion_original['color_secundario'],
        $cotizacion_original['color_acento'],
        $cotizacion_original['logo_url'],
        $cotizacion_original['contenido_requisitos'],
        $cotizacion_original['contenido_estilo'],
        $cotizacion_original['contenido_precios'],
        $cotizacion_original['contenido_terminos'],
        $cotizacion_original['info_contacto'],
        $codigo_unico
    ]);

    $nuevo_id = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Cotización duplicada exitosamente',
        'id' => $nuevo_id,
        'codigo' => $codigo_unico
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
