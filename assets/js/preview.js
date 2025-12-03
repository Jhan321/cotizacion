// Sistema de Previsualización de Cotización

function mostrarPreview() {
    // Actualizar todos los campos hidden antes de generar la preview
    actualizarHTMLRequisitos();
    actualizarHTMLEstilo();
    actualizarHTMLPrecios();
    actualizarHTMLTerminos();
    actualizarHTMLContacto();

    // Obtener valores del formulario
    const titulo = document.getElementById('titulo').value || 'Sin título';
    const cliente = document.getElementById('cliente').value || 'Sin especificar';
    const desarrollador = document.getElementById('desarrollador').value || 'Sin especificar';
    const validez = document.getElementById('validez').value || '30 días';
    const color_primario = document.getElementById('color_primario').value || '#002855';
    const color_secundario = document.getElementById('color_secundario').value || '#97d700';
    const color_acento = document.getElementById('color_acento').value || '#fe5000';
    const logo_url = document.getElementById('logo_url')?.value || '';

    // Calcular color oscuro
    const primaryDark = darkenColor(color_primario);

    // Obtener contenidos HTML
    const requisitos = document.getElementById('requisitos').value || '';
    const estilo = document.getElementById('estilo').value || '';
    const precios = document.getElementById('precios').value || '';
    const terminos = document.getElementById('terminos').value || '';
    const contacto = document.getElementById('contacto').value || '';

    // Generar fecha actual
    const fecha = new Date().toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    // Construir HTML de previsualización
    let previewHTML = `
        <style>
            .quote-preview-container {
                max-width: 900px;
                margin: 0 auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
                overflow: hidden;
            }

            .quote-preview-header {
                background: linear-gradient(135deg, ${color_primario} 0%, ${primaryDark} 100%);
                color: white;
                padding: 2.5rem;
                text-align: center;
                border-bottom: 4px solid ${color_secundario};
                position: relative;
                min-height: 120px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .quote-preview-header-content {
                position: relative;
                z-index: 2;
                width: 100%;
            }

            .quote-preview-header h1 {
                font-size: 2rem;
                margin-bottom: 0.5rem;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
            }

            .quote-preview-header h1 .material-icons {
                font-size: 2rem;
            }

            .quote-preview-header .subtitle {
                font-size: 1.1rem;
                opacity: 0.95;
                font-weight: 300;
            }

            .quote-preview-header-logo-container {
                position: absolute;
                top: 1.5rem;
                left: 1.5rem;
                z-index: 3;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .quote-preview-header-logo-container .header-logo {
                max-height: 80px;
                max-width: 250px;
                height: auto;
                width: auto;
                object-fit: contain;
                filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.4));
                background: rgba(255, 255, 255, 0.1);
                padding: 0.5rem;
                border-radius: 8px;
                backdrop-filter: blur(10px);
            }

            .quote-preview-body {
                padding: 2.5rem;
            }

            .quote-preview-info {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
                margin-bottom: 2.5rem;
                padding-bottom: 2rem;
                border-bottom: 2px solid #e0e0e0;
            }

            .quote-preview-info-block h3 {
                color: ${color_primario};
                font-size: 1rem;
                margin-bottom: 0.5rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .quote-preview-info-block h3 .material-icons {
                font-size: 1.2rem;
            }

            .quote-preview-info-block p {
                color: #666;
                font-size: 0.95rem;
            }

            .quote-preview-section-title {
                color: ${color_primario};
                font-size: 1.8rem;
                margin: 2rem 0 1.5rem 0;
                padding-bottom: 0.75rem;
                border-bottom: 3px solid ${color_secundario};
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .quote-preview-section-title .material-icons {
                font-size: 2rem;
            }

            .quote-preview-requirements-list {
                list-style: none;
                margin: 1.5rem 0;
                padding: 0;
            }

            .quote-preview-requirements-list li {
                padding: 1rem;
                margin-bottom: 0.75rem;
                background: #f8f9fa;
                border-left: 4px solid ${color_primario};
                border-radius: 5px;
                transition: all 0.3s ease;
            }

            .quote-preview-requirements-list li:hover {
                background: #f0f0f0;
                transform: translateX(5px);
            }

            .quote-preview-requirements-list li strong {
                color: ${color_primario};
                display: block;
                margin-bottom: 0.25rem;
                font-size: 1.05rem;
            }

            .quote-preview-requirements-list li span {
                color: #666;
                font-size: 0.95rem;
            }

            .quote-preview-pricing-table {
                width: 100%;
                border-collapse: collapse;
                margin: 2rem 0;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                overflow: hidden;
            }

            .quote-preview-pricing-table thead {
                background: linear-gradient(135deg, ${color_primario} 0%, ${primaryDark} 100%);
                color: white;
            }

            .quote-preview-pricing-table th {
                padding: 1.25rem;
                text-align: left;
                font-weight: 600;
                font-size: 1.1rem;
            }

            .quote-preview-pricing-table tbody tr {
                border-bottom: 1px solid #e0e0e0;
            }

            .quote-preview-pricing-table tbody tr.total-row {
                background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                font-weight: 700;
            }

            .quote-preview-pricing-table td {
                padding: 1.25rem;
                font-size: 1rem;
            }

            .quote-preview-pricing-table td:first-child {
                font-weight: 600;
                color: ${color_primario};
            }

            .quote-preview-pricing-table td:last-child {
                text-align: right;
                font-weight: 700;
                color: ${color_acento};
                font-size: 1.2rem;
            }

            .quote-preview-visual-style {
                background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                padding: 2rem;
                border-radius: 10px;
                margin: 2rem 0;
                border: 2px solid ${color_secundario};
            }

            .quote-preview-visual-style h3 {
                color: ${color_primario};
                font-size: 1.4rem;
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .quote-preview-visual-style ul {
                list-style: none;
                padding-left: 0;
                margin: 0;
            }

            .quote-preview-visual-style li {
                padding: 0.75rem 0;
                border-bottom: 1px solid #e0e0e0;
                color: #666;
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
                position: relative;
                padding-left: 2rem;
            }

            .quote-preview-visual-style li .material-icons {
                color: ${color_secundario};
                font-size: 1.2rem;
                flex-shrink: 0;
                margin-top: 0.1rem;
                position: absolute;
                left: 0;
                top: 0.75rem;
            }

            .quote-preview-visual-style li:last-child {
                border-bottom: none;
            }

            .quote-preview-footer {
                background: ${color_primario};
                color: white;
                padding: 2rem;
                text-align: center;
            }

            .quote-preview-footer h3 {
                font-size: 1.3rem;
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            .quote-preview-footer h3 .material-icons {
                font-size: 1.5rem;
            }

            .quote-preview-footer p {
                opacity: 0.9;
                margin-bottom: 0.5rem;
            }

            .quote-preview-developer-info {
                margin-top: 1.5rem;
                padding-top: 1.5rem;
                border-top: 1px solid rgba(255, 255, 255, 0.2);
            }

            .quote-preview-developer-info a {
                color: ${color_secundario};
                text-decoration: none;
                font-weight: 600;
            }

            .quote-preview-developer-info a:hover {
                color: white;
                text-decoration: underline;
            }

            @media (max-width: 768px) {
                .quote-preview-info {
                    grid-template-columns: 1fr;
                    gap: 1rem;
                }

                .quote-preview-header {
                    min-height: 100px;
                    padding: 1.5rem 1rem;
                }

                .quote-preview-header-logo-container {
                    top: 0.75rem;
                    left: 0.75rem;
                }

                .quote-preview-header-logo-container .header-logo {
                    max-height: 50px;
                    max-width: 120px;
                }

                .quote-preview-header h1 {
                    font-size: 1.3rem;
                    padding-left: 0;
                    margin-top: 0.5rem;
                }

                .quote-preview-body {
                    padding: 1.5rem 1rem;
                }

                .quote-preview-section-title {
                    font-size: 1.4rem;
                }

                .quote-preview-pricing-table {
                    display: block;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }

                .quote-preview-pricing-table table {
                    min-width: 500px;
                }
            }

            @media (max-width: 480px) {
                .quote-preview-header h1 {
                    font-size: 1.2rem;
                }

                .quote-preview-section-title {
                    font-size: 1.2rem;
                }
            }
        </style>
        <div class="quote-preview-container">
            <div class="quote-preview-header">
                ${logo_url ? (() => {
                    // Ajustar ruta relativa si es necesario
                    let logoPath = logo_url.replace(/"/g, '&quot;');
                    // Si es ruta relativa desde raíz (empieza con uploads/), agregar ../
                    if (logoPath && !logoPath.startsWith('http') && !logoPath.startsWith('/') && !logoPath.startsWith('../')) {
                        logoPath = '../' + logoPath;
                    }
                    return `<div class="quote-preview-header-logo-container"><img src="${logoPath}" alt="Logo" class="header-logo" onerror="this.style.display='none';"></div>`;
                })() : ''}
                <div class="quote-preview-header-content">
                    <h1>
                        <i class="material-icons">description</i>
                        ${escapeHtmlPreview(titulo)}
                    </h1>
                    <p class="subtitle">Cotización de Desarrollo</p>
                </div>
            </div>

            <div class="quote-preview-body">
                <div class="quote-preview-info">
                    <div class="quote-preview-info-block">
                        <h3><i class="material-icons">business</i> Cliente</h3>
                        <p>${escapeHtmlPreview(cliente)}</p>
                    </div>
                    <div class="quote-preview-info-block">
                        <h3><i class="material-icons">event</i> Fecha</h3>
                        <p>${fecha}</p>
                    </div>
                    <div class="quote-preview-info-block">
                        <h3><i class="material-icons">person</i> Desarrollador</h3>
                        <p>${escapeHtmlPreview(desarrollador)}</p>
                    </div>
                    <div class="quote-preview-info-block">
                        <h3><i class="material-icons">schedule</i> Validez</h3>
                        <p>${escapeHtmlPreview(validez)}</p>
                    </div>
                </div>

                ${requisitos ? `
                    <h2 class="quote-preview-section-title">
                        <i class="material-icons">checklist</i> Requisitos de Desarrollo
                    </h2>
                    <ul class="quote-preview-requirements-list">
                        ${extraerItemsLista(requisitos)}
                    </ul>
                ` : ''}

                ${estilo ? `
                    <div class="quote-preview-visual-style">
                        <h3><i class="material-icons">brush</i> Estilo Visual y Gráficos</h3>
                        ${estilo.replace(/<li>/g, '<li><i class="material-icons">check_circle</i>')}
                    </div>
                ` : ''}

                ${precios ? `
                    <h2 class="quote-preview-section-title">
                        <i class="material-icons">attach_money</i> Desglose de Costos
                    </h2>
                    <table class="quote-preview-pricing-table">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th>Descripción</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${precios}
                        </tbody>
                    </table>
                ` : ''}

                ${terminos ? `
                    <h2 class="quote-preview-section-title">
                        <i class="material-icons">gavel</i> Términos y Condiciones
                    </h2>
                    <ul class="quote-preview-requirements-list">
                        ${extraerItemsLista(terminos)}
                    </ul>
                ` : ''}
            </div>

            ${contacto ? `
                <div class="quote-preview-footer">
                    <h3><i class="material-icons">question_answer</i> ¿Listo para comenzar?</h3>
                    <p>Estoy disponible para discutir los detalles del proyecto y responder cualquier pregunta.</p>
                    <div class="quote-preview-developer-info">
                        ${contacto}
                    </div>
                </div>
            ` : ''}
        </div>
    `;

    // Mostrar el modal
    document.getElementById('preview-content').innerHTML = previewHTML;
    document.getElementById('preview-modal').classList.add('active');

}

function cerrarPreview() {
    document.getElementById('preview-modal').classList.remove('active');
}

function escapeHtmlPreview(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function extraerItemsLista(html) {
    // Extraer solo los elementos <li> del HTML, removiendo las etiquetas <ul> y <ol>
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;

    // Buscar todos los elementos <li>
    const items = tempDiv.querySelectorAll('li');

    // Retornar solo el contenido de los <li>
    let itemsHTML = '';
    items.forEach(item => {
        itemsHTML += item.outerHTML;
    });

    return itemsHTML || html.replace(/<\/?ul[^>]*>/gi, '').replace(/<\/?ol[^>]*>/gi, '');
}

function darkenColor(color) {
    color = color.replace('#', '');
    const r = Math.max(0, parseInt(color.substr(0, 2), 16) - 30);
    const g = Math.max(0, parseInt(color.substr(2, 2), 16) - 30);
    const b = Math.max(0, parseInt(color.substr(4, 2), 16) - 30);
    return '#' + [r, g, b].map(x => {
        const hex = x.toString(16);
        return hex.length === 1 ? '0' + hex : hex;
    }).join('');
}

// Cerrar modal con ESC o click fuera
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('preview-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                cerrarPreview();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                cerrarPreview();
            }
        });
    }
});

