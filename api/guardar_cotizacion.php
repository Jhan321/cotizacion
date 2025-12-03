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

try {
    $pdo = getDB();
    $usuario_id = $_SESSION['usuario_id'];

    // Valores por defecto del ejemplo si están vacíos
    $titulo = $data['titulo'] ?? '';
    $cliente = $data['cliente'] ?? '';
    $desarrollador = $data['desarrollador'] ?? '';
    $validez = $data['validez'] ?? '30 días';
    $color_primario = $data['color_primario'] ?? '#002855';
    $color_secundario = $data['color_secundario'] ?? '#97d700';
    $color_acento = $data['color_acento'] ?? '#fe5000';
    $logo_url = $data['logo_url'] ?? null;

    // Generar HTML para requisitos si viene como texto plano
    $requisitos = $data['requisitos'] ?? '';
    if (!empty($requisitos) && !preg_match('/<[^>]+>/', $requisitos)) {
        // Si es texto plano, convertirlo a HTML
        $lineas = explode("\n", trim($requisitos));
        $requisitos = '<ul class="requirements-list">';
        foreach ($lineas as $linea) {
            $linea = trim($linea);
            if (!empty($linea)) {
                if (preg_match('/^(\d+\.?\s*)(.+)/', $linea, $matches)) {
                    $requisitos .= '<li><strong>' . htmlspecialchars($matches[1]) . '</strong><span>' . htmlspecialchars($matches[2]) . '</span></li>';
                } else {
                    $requisitos .= '<li>' . htmlspecialchars($linea) . '</li>';
                }
            }
        }
        $requisitos .= '</ul>';
    }

    $estilo = $data['estilo'] ?? '';
    $precios = $data['precios'] ?? '';
    $terminos = $data['terminos'] ?? '';
    $contacto = $data['contacto'] ?? '';

    $cotizacion_id = $data['id'] ?? null;

    if ($cotizacion_id) {
        // Actualizar cotización existente
        $stmt = $pdo->prepare("SELECT usuario_id FROM cotizaciones WHERE id = ?");
        $stmt->execute([$cotizacion_id]);
        $cot = $stmt->fetch();

        if (!$cot || $cot['usuario_id'] != $usuario_id) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'No tienes permiso para editar esta cotización']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE cotizaciones SET
            titulo = ?, cliente = ?, desarrollador = ?, validez = ?,
            color_primario = ?, color_secundario = ?, color_acento = ?, logo_url = ?,
            contenido_requisitos = ?, contenido_estilo = ?, contenido_precios = ?,
            contenido_terminos = ?, info_contacto = ?
            WHERE id = ? AND usuario_id = ?");

        $stmt->execute([
            $titulo, $cliente, $desarrollador, $validez,
            $color_primario, $color_secundario, $color_acento, $logo_url,
            $requisitos, $estilo, $precios, $terminos, $contacto,
            $cotizacion_id, $usuario_id
        ]);

        echo json_encode(['success' => true, 'message' => 'Cotización actualizada', 'id' => $cotizacion_id]);
    } else {
        // Crear nueva cotización
        $codigo_unico = generarCodigoUnico();

        $stmt = $pdo->prepare("INSERT INTO cotizaciones
            (usuario_id, titulo, cliente, desarrollador, validez,
             color_primario, color_secundario, color_acento, logo_url,
             contenido_requisitos, contenido_estilo, contenido_precios,
             contenido_terminos, info_contacto, codigo_unico)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $usuario_id, $titulo, $cliente, $desarrollador, $validez,
            $color_primario, $color_secundario, $color_acento, $logo_url,
            $requisitos, $estilo, $precios, $terminos, $contacto, $codigo_unico
        ]);

        $nuevo_id = $pdo->lastInsertId();
        echo json_encode(['success' => true, 'message' => 'Cotización creada', 'id' => $nuevo_id, 'codigo' => $codigo_unico]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

