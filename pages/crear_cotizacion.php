<?php
require_once '../includes/config.php';
requireLogin();

// Valores por defecto basados en el ejemplo
$requisitos_default = '<li><strong>1. Desarrollo Frontend</strong><span>Interfaz de usuario responsive con HTML5, CSS3 y JavaScript (ES6+). Implementación de diseño corporativo, animaciones y transiciones suaves. Optimización para dispositivos móviles, tablets y desktop.</span></li>
<li><strong>2. Desarrollo Backend</strong><span>API RESTful con PHP 7.4+. Sistema de gestión de resultados, leaderboard y almacenamiento de datos. Integración con base de datos MySQL para persistencia de información.</span></li>
<li><strong>3. Sistema de Quiz Interactivo</strong><span>Lógica de juego con sistema de puntuación, cronómetro, validación de respuestas y retroalimentación inmediata. Manejo de estados del juego y flujo de navegación.</span></li>';

$estilo_default = '<li>Diseño de interfaz corporativa con identidad visual</li>
<li>Paleta de colores corporativos personalizados</li>
<li>Iconografía y elementos gráficos personalizados</li>
<li>Diseño de cards y componentes UI consistentes</li>
<li>Animaciones y transiciones visuales</li>';

$precios_default = '<tr><td>Concepto 1</td><td>Descripción del servicio o producto</td><td>$0.000</td></tr>
<tr><td>Concepto 2</td><td>Descripción del servicio o producto</td><td>$0.000</td></tr>
<tr class="total-row"><td colspan="2"><strong>TOTAL</strong></td><td><strong>$0.000</strong></td></tr>';

$terminos_default = '<li><strong>Forma de Pago</strong><span>50% al inicio del proyecto, 50% al finalizar y entregar el proyecto completo.</span></li>
<li><strong>Tiempo de Desarrollo</strong><span>Estimado: 1-2 semanas desde la aprobación y pago inicial.</span></li>
<li><strong>Entregables</strong><span>Código fuente completo, documentación técnica, base de datos configurada, y guía de instalación.</span></li>';

$contacto_default = '<p><strong>Nombre</strong> - Descripción</p>
<p>🌐 <a href="#" target="_blank">Sitio web</a> | 💼 <a href="#" target="_blank">GitHub</a></p>
<p>📧 email@ejemplo.com | 📱 +57 XXX XXX XXXX</p>
<p>📍 Ciudad, País</p>';

$cotizacion = [
    'titulo' => '',
    'cliente' => '',
    'desarrollador' => '',
    'validez' => '30 días',
    'color_primario' => '#002855',
    'color_secundario' => '#97d700',
    'color_acento' => '#fe5000',
    'logo_url' => '',
    'requisitos' => $requisitos_default,
    'estilo' => $estilo_default,
    'precios' => $precios_default,
    'terminos' => $terminos_default,
    'contacto' => $contacto_default
];
$es_edicion = false;
$cotizacion_id = null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $es_edicion ? 'Editar' : 'Crear'; ?> Cotización</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../assets/css/editor.css">
    <link rel="stylesheet" href="../assets/css/notifications.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="material-icons"><?php echo $es_edicion ? 'edit' : 'add'; ?></i> <?php echo $es_edicion ? 'Editar' : 'Crear'; ?> Cotización</h1>
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
                    <div id="logo-preview" style="margin-top: 1rem; display: none;">
                        <label>Vista Previa:</label>
                        <div style="background: white; padding: 1rem; border: 2px solid #ddd; border-radius: 8px; display: inline-block;">
                            <img id="logo-preview-img" src="" alt="Logo Preview" style="max-height: 80px; max-width: 200px; display: block;">
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
                    <input type="hidden" id="requisitos" value="">
                </div>

                <!-- Estilo Visual -->
                <div class="form-group full-width">
                    <label><i class="material-icons">brush</i> Estilo Visual y Gráficos</label>
                    <div class="items-editor" id="estilo-editor">
                        <div class="items-list" id="estilo-list"></div>
                        <button type="button" class="btn-add-item" onclick="agregarEstilo()"><i class="material-icons">add</i> Agregar Item</button>
                    </div>
                    <input type="hidden" id="estilo" value="">
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
                    <input type="hidden" id="precios" value="">
                </div>

                <!-- Términos y Condiciones -->
                <div class="form-group full-width">
                    <label><i class="material-icons">gavel</i> Términos y Condiciones</label>
                    <div class="items-editor" id="terminos-editor">
                        <div class="items-list" id="terminos-list"></div>
                        <button type="button" class="btn-add-item" onclick="agregarTermino()"><i class="material-icons">add</i> Agregar Término</button>
                    </div>
                    <input type="hidden" id="terminos" value="">
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
                    <input type="hidden" id="contacto" value="">
                </div>
            </div>

            <button type="submit" class="btn btn-save"><i class="material-icons">save</i> Guardar Cotización</button>
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
