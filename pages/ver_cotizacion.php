<?php
require_once '../includes/config.php';

$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    die('Código de cotización no válido');
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT * FROM cotizaciones WHERE codigo_unico = ?");
$stmt->execute([$codigo]);
$cot = $stmt->fetch();

if (!$cot) {
    die('Cotización no encontrada');
}

// Colores personalizados
$primary_color = $cot['color_primario'];
$secondary_color = $cot['color_secundario'];
$accent_color = $cot['color_acento'];

// Calcular versión más oscura del color primario para gradiente
function darkenColor($color)
{
    $color = ltrim($color, '#');
    $r = hexdec(substr($color, 0, 2));
    $g = hexdec(substr($color, 2, 2));
    $b = hexdec(substr($color, 4, 2));
    $r = max(0, $r - 30);
    $g = max(0, $g - 30);
    $b = max(0, $b - 30);
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

$primary_dark = darkenColor($primary_color);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($cot['titulo']); ?> - Cotización</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: <?php echo $primary_color; ?>;
            --secondary-color: <?php echo $secondary_color; ?>;
            --accent-color: <?php echo $accent_color; ?>;
            --primary-dark: <?php echo $primary_dark; ?>;
            --text-color: #333;
            --bg-color: #f4f4f4;
            --white: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f4f4f4 0%, #e8e8e8 100%);
            color: var(--text-color);
            line-height: 1.6;
            padding: 2rem 1rem;
        }

        .quote-container {
            max-width: 900px;
            margin: 0 auto;
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            position: relative;
        }

        .quote-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white);
            padding: 2.5rem;
            text-align: center;
            border-bottom: 4px solid var(--secondary-color);
            position: relative;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quote-header-content {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .quote-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .quote-header .subtitle {
            font-size: 1.1rem;
            opacity: 0.95;
            font-weight: 300;
        }

        .header-logo-container {
            position: absolute;
            bottom: 1rem;
            left: 1.5rem;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quote-body {
            padding: 2.5rem;
        }

        .quote-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #e0e0e0;
        }

        /* Estilos para modo PDF */
        .pdf-mode .quote-container {
            max-width: 100%;
            border-radius: 0;
            box-shadow: none;
        }

        .pdf-mode body {
            background: white;
            padding: 0;
        }

        .pdf-mode .quote-body {
            padding: 1.5rem 2rem;
        }

        .pdf-mode .quote-header {
            padding: 2rem;
            min-height: auto;
        }

        .pdf-mode .section-title {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
        }

        .pdf-mode .requirements-list {
            margin: 1rem 0;
        }

        .pdf-mode .requirements-list li {
            margin-bottom: 0.5rem;
            padding: 0.75rem;
        }

        .pdf-mode .pricing-table-wrapper {
            margin: 1rem 0;
        }

        .pdf-mode .visual-style-section {
            margin: 1rem 0;
            padding: 1.5rem;
        }

        .info-block h3 {
            color: var(--primary-color);
            font-size: 1rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-block h3 .material-icons {
            font-size: 1.2rem;
        }

        .info-block p {
            color: #666;
            font-size: 0.95rem;
        }

        .section-title {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin: 2rem 0 1.5rem 0;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--secondary-color);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title .material-icons {
            font-size: 2rem;
        }

        .quote-header h1 .material-icons {
            font-size: 2.5rem;
        }

        .header-logo-container .header-logo {
            max-height: 50px;
            max-width: 150px;
            height: auto;
            width: auto;
            object-fit: contain;
            opacity: 1;
            background: transparent;
            padding: 0;
            border-radius: 0;
            filter: none;
        }

        .requirements-list {
            list-style: none;
            margin: 1.5rem 0;
        }

        .requirements-list li {
            padding: 1rem;
            margin-bottom: 0.75rem;
            background: #f8f9fa;
            border-left: 4px solid var(--primary-color);
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .requirements-list li:hover {
            background: #f0f0f0;
            transform: translateX(5px);
        }

        .requirements-list li strong {
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.25rem;
            font-size: 1.05rem;
        }

        .requirements-list li span {
            color: #666;
            font-size: 0.95rem;
        }

        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .pricing-table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 2rem 0;
        }

        .pricing-table thead {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white);
        }

        .pricing-table th {
            padding: 1.25rem;
            text-align: left;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .pricing-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
            transition: background 0.3s ease;
        }

        .pricing-table tbody tr:hover {
            background: #f8f9fa;
        }

        .pricing-table tbody tr:last-child {
            border-bottom: none;
        }

        .pricing-table tbody tr.total-row {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            font-weight: 700;
        }

        .pricing-table td {
            padding: 1.25rem;
            font-size: 1rem;
        }

        .pricing-table td:first-child {
            font-weight: 600;
            color: var(--primary-color);
        }

        .pricing-table td:last-child {
            text-align: right;
            font-weight: 700;
            color: var(--accent-color);
            font-size: 1.2rem;
        }

        .total-row td {
            font-size: 1.3rem;
            color: var(--primary-color);
        }

        .visual-style-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 2rem;
            border-radius: 10px;
            margin: 2rem 0;
            border: 2px solid var(--secondary-color);
        }

        .visual-style-section h3 {
            color: var(--primary-color);
            font-size: 1.4rem;
            margin-bottom: 1rem;
        }

        .visual-style-section ul {
            list-style: none;
            padding-left: 0;
        }

        .visual-style-section li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #e0e0e0;
            color: #666;
        }

        .visual-style-section li:last-child {
            border-bottom: none;
        }

        .visual-style-section li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding-left: 0;
            position: relative;
        }

        .visual-style-section li .material-icons {
            color: var(--secondary-color);
            font-size: 1.2rem;
            flex-shrink: 0;
            margin-top: 0.1rem;
        }

        .quote-footer {
            background: var(--primary-color);
            color: var(--white);
            padding: 2rem;
            text-align: center;
        }

        .quote-footer h3 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .quote-footer h3 .material-icons {
            font-size: 1.5rem;
        }

        .quote-footer p {
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .developer-info {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .developer-info a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .developer-info a:hover {
            color: var(--white);
            text-decoration: underline;
        }

        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .action-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .action-btn .material-icons {
            font-size: 1.2rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-share {
            background: var(--secondary-color);
            color: #000;
        }

        .btn-download {
            background: var(--accent-color);
            color: white;
        }

        /* Estilos específicos para PDF */
        .pdf-section {
            page-break-inside: avoid;
            break-inside: avoid;
            orphans: 2;
            widows: 2;
        }

        .pdf-no-break {
            page-break-inside: avoid;
            break-inside: avoid;
            orphans: 3;
            widows: 3;
        }

        .pricing-table {
            page-break-inside: auto;
        }

        .pricing-table tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .pricing-table thead {
            display: table-header-group;
        }

        .pricing-table tbody {
            display: table-row-group;
        }

        .pricing-table tfoot {
            display: table-footer-group;
        }

        .requirements-list {
            orphans: 2;
            widows: 2;
        }

        .requirements-list li {
            page-break-inside: avoid;
            break-inside: avoid;
            orphans: 2;
            widows: 2;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .quote-container {
                box-shadow: none;
                max-width: 100%;
            }

            .action-buttons {
                display: none;
            }

            .quote-header {
                page-break-after: avoid;
                break-after: avoid;
            }

            .section-title {
                page-break-after: avoid;
                break-after: avoid;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .quote-info {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .visual-style-section {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .quote-footer {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem 0.5rem;
            }

            .quote-container {
                border-radius: 10px;
            }

            .quote-header {
                min-height: 100px;
                padding: 1.5rem 1rem;
            }

            .header-logo-container {
                bottom: 0.5rem;
                left: 1rem;
                right: auto;
                top: auto;
            }

            .header-logo-container .header-logo {
                max-height: 40px;
                max-width: 100px;
                padding: 0;
            }

            .quote-header-content h1 {
                font-size: 1.3rem;
                padding-left: 0;
                margin-top: 0.5rem;
            }

            .quote-header-content .subtitle {
                font-size: 0.9rem;
            }

            .quote-body {
                padding: 1.5rem 1rem;
            }

            .quote-info {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding-bottom: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .section-title {
                font-size: 1.4rem;
                margin: 1.5rem 0 1rem;
            }

            .section-title .material-icons {
                font-size: 1.5rem;
            }

            .pricing-table-wrapper {
                margin: 1rem 0;
            }

            .pricing-table {
                font-size: 0.85rem;
                min-width: 500px;
            }

            .pricing-table th,
            .pricing-table td {
                padding: 0.6rem 0.5rem;
                font-size: 0.85rem;
            }

            .visual-style-section {
                padding: 1.5rem;
            }

            .requirements-list li {
                padding: 0.75rem;
                font-size: 0.9rem;
            }

            .quote-footer {
                padding: 1.5rem 1rem;
            }

            .quote-footer h3 {
                font-size: 1.1rem;
            }

            .action-buttons {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                top: auto;
                margin: 0;
                padding: 0.75rem;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                flex-direction: row;
                justify-content: space-around;
                gap: 0.5rem;
                z-index: 1000;
            }

            .action-btn {
                flex: 1;
                padding: 0.75rem;
                font-size: 0.85rem;
                max-width: 160px;
            }

            .action-btn .material-icons {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .quote-header h1 {
                font-size: 1.2rem;
            }

            .section-title {
                font-size: 1.2rem;
            }

            .pricing-table th,
            .pricing-table td {
                padding: 0.5rem 0.4rem;
                font-size: 0.8rem;
            }

            .action-btn {
                font-size: 0.75rem;
                padding: 0.6rem;
            }
        }

        /* Notificaciones Toast */
        .notification-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            z-index: 10001;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .notification-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .notification-toast.success {
            border-left-color: #28a745;
        }

        .notification-toast.success .material-icons {
            color: #28a745;
        }

        .notification-toast.error {
            border-left-color: #dc3545;
        }

        .notification-toast.error .material-icons {
            color: #dc3545;
        }

        .notification-toast.info {
            border-left-color: #17a2b8;
        }

        .notification-toast.info .material-icons {
            color: #17a2b8;
        }

        .notification-toast .material-icons {
            font-size: 1.5rem;
        }

        .notification-toast span {
            color: #333;
            font-weight: 500;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>

<body>
    <div class="action-buttons">
        <button onclick="compartirURL()" class="action-btn btn-share"><i class="material-icons">share</i> Compartir</button>
        <button onclick="descargarPDF()" class="action-btn btn-download"><i class="material-icons">download</i> Descargar PDF</button>
    </div>

    <div class="quote-container" id="cotizacion-content">
        <div class="quote-header">
            <?php if (!empty($cot['logo_url'])):
                // Asegurar que la ruta sea correcta desde pages/
                $logoPath = $cot['logo_url'];
                // Si es ruta relativa desde raíz (empieza con uploads/), agregar ../
                if (!preg_match('/^https?:\/\//', $logoPath) && !preg_match('/^\//', $logoPath) && !preg_match('/^\.\.\//', $logoPath)) {
                    $logoPath = '../' . $logoPath;
                }
            ?>
                <div class="header-logo-container">
                    <img src="<?php echo htmlspecialchars($logoPath); ?>" alt="Logo" class="header-logo" onerror="this.style.display='none';">
                </div>
            <?php endif; ?>
            <div class="quote-header-content">
                <h1>
                    <?php echo htmlspecialchars($cot['titulo']); ?>
                </h1>
                <p class="subtitle">Cotización de Desarrollo</p>
            </div>
        </div>

        <div class="quote-body">
            <div class="quote-info pdf-section pdf-no-break">
                <div class="info-block">
                    <h3><i class="material-icons">business</i> Cliente</h3>
                    <p><?php echo htmlspecialchars($cot['cliente']); ?></p>
                </div>
                <div class="info-block">
                    <h3><i class="material-icons">event</i> Fecha</h3>
                    <p><?php echo date('d/m/Y', strtotime($cot['creado_en'])); ?></p>
                </div>
                <div class="info-block">
                    <h3><i class="material-icons">person</i> Desarrollador</h3>
                    <p><?php echo htmlspecialchars($cot['desarrollador']); ?></p>
                </div>
                <div class="info-block">
                    <h3><i class="material-icons">schedule</i> Validez</h3>
                    <p><?php echo htmlspecialchars($cot['validez']); ?></p>
                </div>
            </div>

            <?php if (!empty($cot['contenido_requisitos'])): ?>
                <div class="pdf-section">
                    <h2 class="section-title"><i class="material-icons">checklist</i> Requisitos de Desarrollo</h2>
                    <ul class="requirements-list">
                        <?php echo $cot['contenido_requisitos']; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($cot['contenido_estilo'])): ?>
                <div class="visual-style-section pdf-section">
                    <h3><i class="material-icons">brush</i> Estilo Visual y Gráficos</h3>
                    <ul id="estilo-list">
                        <?php echo $cot['contenido_estilo']; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($cot['contenido_precios'])): ?>
                <div class="pdf-section">
                    <h2 class="section-title"><i class="material-icons">attach_money</i> Desglose de Costos</h2>
                    <div class="pricing-table-wrapper">
                        <table class="pricing-table">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Descripción</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $cot['contenido_precios']; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($cot['contenido_terminos'])): ?>
                <div class="pdf-section">
                    <h2 class="section-title"><i class="material-icons">gavel</i> Términos y Condiciones</h2>
                    <ul class="requirements-list">
                        <?php echo $cot['contenido_terminos']; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($cot['info_contacto'])): ?>
            <div class="quote-footer pdf-section">
                <h3><i class="material-icons">question_answer</i> ¿Listo para comenzar?</h3>
                <p>Estoy disponible para discutir los detalles del proyecto y responder cualquier pregunta.</p>
                <div class="developer-info">
                    <?php echo $cot['info_contacto']; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function compartirURL() {
            const url = window.location.href;

            if (navigator.share) {
                navigator.share({
                    title: 'Cotización',
                    text: 'Revisa esta cotización',
                    url: url
                }).catch(err => console.log('Error al compartir', err));
            } else {
                // Fallback: copiar al portapapeles
                navigator.clipboard.writeText(url).then(() => {
                    mostrarNotificacion('URL copiada al portapapeles', 'success');
                }).catch(() => {
                    // Fallback para navegadores antiguos
                    const textarea = document.createElement('textarea');
                    textarea.value = url;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    mostrarNotificacion('URL copiada al portapapeles', 'success');
                });
            }
        }

        function descargarPDF() {
            const element = document.getElementById('cotizacion-content');
            if (!element) {
                mostrarNotificacion('Error: No se encontró el contenido', 'error');
                return;
            }

            // Verificar que el elemento tenga contenido
            if (element.offsetHeight === 0 && element.offsetWidth === 0) {
                mostrarNotificacion('Error: El contenido no es visible', 'error');
                return;
            }

            mostrarNotificacion('Generando PDF...', 'info');

            // Ocultar botones de acción temporalmente
            const actionButtons = document.querySelector('.action-buttons');
            if (actionButtons) {
                actionButtons.style.display = 'none';
            }

            // Scroll al inicio del elemento para asegurar que esté visible
            window.scrollTo(0, 0);
            element.scrollIntoView({
                behavior: 'instant',
                block: 'start'
            });

            // Esperar a que las imágenes se carguen
            const images = element.querySelectorAll('img');
            let imagesLoaded = 0;
            const totalImages = images.length;

            if (totalImages === 0) {
                generarPDF();
            } else {
                images.forEach(img => {
                    if (img.complete) {
                        imagesLoaded++;
                        if (imagesLoaded === totalImages) {
                            generarPDF();
                        }
                    } else {
                        img.onload = function() {
                            imagesLoaded++;
                            if (imagesLoaded === totalImages) {
                                generarPDF();
                            }
                        };
                        img.onerror = function() {
                            imagesLoaded++;
                            if (imagesLoaded === totalImages) {
                                generarPDF();
                            }
                        };
                    }
                });
            }

            function generarPDF() {
                setTimeout(() => {
                    const opt = {
                        margin: [0.5, 0.6, 0.5, 0.6],
                        filename: 'cotizacion_' + new Date().getTime() + '.pdf',
                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2,
                            useCORS: true,
                            letterRendering: true,
                            logging: false,
                            backgroundColor: '#ffffff',
                            allowTaint: true,
                            scrollX: 0,
                            scrollY: 0
                        },
                        jsPDF: {
                            unit: 'in',
                            format: 'letter',
                            orientation: 'portrait',
                            compress: true
                        },
                        pagebreak: {
                            mode: ['avoid-all', 'css', 'legacy'],
                            avoid: ['.pdf-section', '.pdf-no-break', '.quote-header', '.section-title', '.requirements-list li', 'table tr', '.visual-style-section', '.quote-footer']
                        },
                        enableLinks: true
                    };

                    html2pdf().set(opt).from(element).save().then(() => {
                        // Restaurar botones
                        if (actionButtons) {
                            actionButtons.style.display = 'flex';
                        }
                        mostrarNotificacion('PDF descargado exitosamente', 'success');
                    }).catch((error) => {
                        // Restaurar botones
                        if (actionButtons) {
                            actionButtons.style.display = 'flex';
                        }
                        console.error('Error al generar PDF:', error);
                        mostrarNotificacion('Error al generar el PDF', 'error');
                    });
                }, 500);
            }
        }

        // Agregar iconos a los items de estilo visual
        document.addEventListener('DOMContentLoaded', function() {
            const estiloList = document.getElementById('estilo-list');
            if (estiloList) {
                const items = estiloList.querySelectorAll('li');
                items.forEach(item => {
                    const icon = document.createElement('i');
                    icon.className = 'material-icons';
                    icon.textContent = 'check_circle';
                    item.insertBefore(icon, item.firstChild);
                });
            }
        });

        // Sistema simple de notificaciones
        function mostrarNotificacion(mensaje, tipo = 'info') {
            const notificacion = document.createElement('div');
            notificacion.className = `notification-toast ${tipo}`;
            notificacion.innerHTML = `
                <i class="material-icons">${tipo === 'success' ? 'check_circle' : tipo === 'error' ? 'error' : 'info'}</i>
                <span>${mensaje}</span>
            `;
            document.body.appendChild(notificacion);

            setTimeout(() => {
                notificacion.classList.add('show');
            }, 10);

            setTimeout(() => {
                notificacion.classList.remove('show');
                setTimeout(() => notificacion.remove(), 300);
            }, 3000);
        }
    </script>
</body>

</html>
