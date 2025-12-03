<?php
require_once '../includes/config.php';
requireLogin();

$cotizacion_id = $_GET['id'] ?? null;

if (!$cotizacion_id) {
    header('Location: dashboard.php');
    exit;
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT * FROM cotizaciones WHERE id = ? AND usuario_id = ?");
$stmt->execute([$cotizacion_id, $_SESSION['usuario_id']]);
$cot = $stmt->fetch();

if (!$cot) {
    header('Location: dashboard.php');
    exit;
}

$cotizacion = [
    'titulo' => $cot['titulo'],
    'cliente' => $cot['cliente'],
    'desarrollador' => $cot['desarrollador'],
    'validez' => $cot['validez'],
    'color_primario' => $cot['color_primario'],
    'color_secundario' => $cot['color_secundario'],
    'color_acento' => $cot['color_acento'],
    'requisitos' => $cot['contenido_requisitos'] ?? '',
    'estilo' => $cot['contenido_estilo'] ?? '',
    'precios' => $cot['contenido_precios'] ?? '',
    'terminos' => $cot['contenido_terminos'] ?? '',
    'contacto' => $cot['info_contacto'] ?? '',
    'logo_url' => $cot['logo_url'] ?? ''
];
$es_edicion = true;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cotización</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../assets/css/editor.css">
    <link rel="stylesheet" href="../assets/css/notifications.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="material-icons">edit</i> Editar Cotización</h1>
            <a href="dashboard.php" class="btn btn-secondary"><i class="material-icons">arrow_back</i> Volver al Dashboard</a>
        </div>

        <form id="cotizacionForm" class="form-container">
            <input type="hidden" id="cotizacion_id" value="<?php echo $cotizacion_id; ?>">

            <div class="form-section">
                <h2><i class="material-icons">info</i> Información General</h2>
                <div class="form-group full-width">
                    <label for="logo_file"><i class="material-icons">image</i> Logo de la Empresa (PNG)</label>
                    <input type="file" id="logo_file" accept="image/png" style="padding: 0.5rem; border: 2px solid #ddd; border-radius: 5px; width: 100%;">
                    <input type="hidden" id="logo_url" value="<?php echo htmlspecialchars($cotizacion['logo_url'] ?? ''); ?>">
                    <p class="info-text">Sube una imagen PNG del logo de la empresa. Se mostrará en el header de la cotización en lugar del icono.</p>
                    <div id="logo-preview" style="margin-top: 1rem; <?php echo !empty($cotizacion['logo_url']) ? '' : 'display: none;'; ?>">
                        <label>Vista Previa:</label>
                        <div style="background: white; padding: 1rem; border: 2px solid #ddd; border-radius: 8px; display: inline-block;">
                            <?php
                            $logoPreviewPath = $cotizacion['logo_url'] ?? '';
                            if (!empty($logoPreviewPath) && !preg_match('/^https?:\/\//', $logoPreviewPath) && !preg_match('/^\//', $logoPreviewPath)) {
                                $logoPreviewPath = '../' . $logoPreviewPath;
                            }
                            ?>
                            <img id="logo-preview-img" src="<?php echo htmlspecialchars($logoPreviewPath); ?>" alt="Logo Preview" style="max-height: 80px; max-width: 200px; display: block;">
                        </div>
                        <button type="button" id="remove-logo-btn" class="btn btn-danger" style="margin-top: 0.5rem; padding: 0.5rem 1rem; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;"><i class="material-icons" style="font-size: 1rem; vertical-align: middle;">delete</i> Eliminar Logo</button>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="titulo">Título de la Cotización</label>
                        <input type="text" id="titulo" value="<?php echo htmlspecialchars($cotizacion['titulo']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="cliente">Cliente</label>
                        <input type="text" id="cliente" value="<?php echo htmlspecialchars($cotizacion['cliente']); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="desarrollador">Desarrollador</label>
                        <input type="text" id="desarrollador" value="<?php echo htmlspecialchars($cotizacion['desarrollador']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="validez">Validez</label>
                        <input type="text" id="validez" value="<?php echo htmlspecialchars($cotizacion['validez']); ?>" placeholder="Ej: 30 días">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2><i class="material-icons">palette</i> Personalización de Colores</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="color_primario">Color Primario</label>
                        <input type="color" id="color_primario" value="<?php echo htmlspecialchars($cotizacion['color_primario']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="color_secundario">Color Secundario</label>
                        <input type="color" id="color_secundario" value="<?php echo htmlspecialchars($cotizacion['color_secundario']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="color_acento">Color Acento</label>
                        <input type="color" id="color_acento" value="<?php echo htmlspecialchars($cotizacion['color_acento']); ?>">
                    </div>
                </div>
                <div class="color-preview">
                    <div class="color-box" id="preview_primario" style="background: <?php echo htmlspecialchars($cotizacion['color_primario']); ?>;">Primario</div>
                    <div class="color-box" id="preview_secundario" style="background: <?php echo htmlspecialchars($cotizacion['color_secundario']); ?>;">Secundario</div>
                    <div class="color-box" id="preview_acento" style="background: <?php echo htmlspecialchars($cotizacion['color_acento']); ?>;">Acento</div>
                </div>
            </div>

            <div class="form-section">
                <h2><i class="material-icons">description</i> Contenido de la Cotización</h2>

                <!-- Requisitos de Desarrollo -->
                <div class="form-group full-width">
                    <label><i class="material-icons">checklist</i> Requisitos de Desarrollo</label>
                    <div class="items-editor" id="requisitos-editor">
                        <div class="items-list" id="requisitos-list"></div>
                        <button type="button" class="btn-add-item" onclick="agregarRequisito()"><i class="material-icons">add</i> Agregar Requisito</button>
                    </div>
                    <input type="hidden" id="requisitos" value="<?php echo htmlspecialchars($cotizacion['requisitos']); ?>">
                </div>

                <!-- Estilo Visual -->
                <div class="form-group full-width">
                    <label><i class="material-icons">brush</i> Estilo Visual y Gráficos</label>
                    <div class="items-editor" id="estilo-editor">
                        <div class="items-list" id="estilo-list"></div>
                        <button type="button" class="btn-add-item" onclick="agregarEstilo()"><i class="material-icons">add</i> Agregar Item</button>
                    </div>
                    <input type="hidden" id="estilo" value="<?php echo htmlspecialchars($cotizacion['estilo']); ?>">
                </div>

                <!-- Precios -->
                <div class="form-group full-width">
                    <label><i class="material-icons">attach_money</i> Tabla de Precios</label>
                    <div class="items-editor" id="precios-editor">
                        <div class="pricing-table-editor">
                            <table class="editor-table">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Descripción</th>
                                        <th>Valor</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="precios-list"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4">
                                            <button type="button" class="btn-add-item" onclick="agregarPrecio()"><i class="material-icons">add</i> Agregar Precio</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" id="precios" value="<?php echo htmlspecialchars($cotizacion['precios']); ?>">
                </div>

                <!-- Términos y Condiciones -->
                <div class="form-group full-width">
                    <label><i class="material-icons">gavel</i> Términos y Condiciones</label>
                    <div class="items-editor" id="terminos-editor">
                        <div class="items-list" id="terminos-list"></div>
                        <button type="button" class="btn-add-item" onclick="agregarTermino()"><i class="material-icons">add</i> Agregar Término</button>
                    </div>
                    <input type="hidden" id="terminos" value="<?php echo htmlspecialchars($cotizacion['terminos']); ?>">
                </div>

                <!-- Información de Contacto -->
                <div class="form-group full-width">
                    <label><i class="material-icons">contact_mail</i> Información de Contacto</label>
                    <div class="contact-editor">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contacto_nombre">Nombre Completo</label>
                                <input type="text" id="contacto_nombre" placeholder="Ej: Juan Pérez - Desarrollador">
                            </div>
                            <div class="form-group">
                                <label for="contacto_descripcion">Descripción</label>
                                <input type="text" id="contacto_descripcion" placeholder="Ej: Desarrollador Web Freelance">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contacto_sitio">Sitio Web</label>
                                <input type="url" id="contacto_sitio" placeholder="https://ejemplo.com">
                            </div>
                            <div class="form-group">
                                <label for="contacto_github">GitHub / Red Social</label>
                                <input type="url" id="contacto_github" placeholder="https://github.com/usuario">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contacto_email">Email</label>
                                <input type="email" id="contacto_email" placeholder="email@ejemplo.com">
                            </div>
                            <div class="form-group">
                                <label for="contacto_telefono">Teléfono</label>
                                <input type="tel" id="contacto_telefono" placeholder="+57 XXX XXX XXXX">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contacto_ubicacion">Ubicación</label>
                                <input type="text" id="contacto_ubicacion" placeholder="Ciudad, País">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="contacto" value="<?php echo htmlspecialchars($cotizacion['contacto']); ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-save"><i class="material-icons">save</i> Guardar Cambios</button>
        </form>
    </div>

    <!-- Botón flotante de previsualización -->
    <button onclick="mostrarPreview()" class="fab-preview" title="Vista Previa">
        <i class="material-icons">preview</i>
    </button>

    <!-- Modal de Previsualización -->
    <div id="preview-modal" class="preview-modal-overlay">
        <div class="preview-modal-container">
            <div class="preview-modal-header">
                <h2><i class="material-icons">preview</i> Vista Previa de la Cotización</h2>
                <button onclick="cerrarPreview()" class="preview-close-btn"><i class="material-icons">close</i></button>
            </div>
            <div class="preview-modal-body">
                <div id="preview-content" class="quote-preview"></div>
            </div>
        </div>
    </div>

    <script src="../assets/js/notifications.js"></script>
    <script src="../assets/js/editor.js"></script>
    <script src="../assets/js/preview.js"></script>
</body>

</html>
