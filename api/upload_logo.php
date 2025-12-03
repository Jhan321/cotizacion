<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Error al subir el archivo']);
    exit;
}

$file = $_FILES['logo'];

// Validar que sea PNG
$allowedTypes = ['image/png'];
$fileType = mime_content_type($file['tmp_name']);

if (!in_array($fileType, $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Solo se permiten archivos PNG']);
    exit;
}

// Validar tamaño (máximo 5MB)
$maxSize = 5 * 1024 * 1024; // 5MB
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El archivo es muy grande. Máximo 5MB']);
    exit;
}

// Crear directorio de logos si no existe
$uploadDir = '../uploads/logos/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generar nombre único
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$fileName = uniqid('logo_', true) . '.' . $extension;
$filePath = $uploadDir . $fileName;

// Mover archivo
if (!move_uploaded_file($file['tmp_name'], $filePath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al guardar el archivo']);
    exit;
}

// Retornar URL relativa
$relativeUrl = 'uploads/logos/' . $fileName;
echo json_encode([
    'success' => true,
    'message' => 'Logo subido exitosamente',
    'url' => $relativeUrl
]);
