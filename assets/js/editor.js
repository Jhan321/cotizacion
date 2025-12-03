// Variables globales para los items
let requisitosItems = [];
let estiloItems = [];
let preciosItems = [];
let terminosItems = [];

// Actualizar preview de colores
['primario', 'secundario', 'acento'].forEach(color => {
    const input = document.getElementById(`color_${color}`);
    const preview = document.getElementById(`preview_${color}`);

    if (input && preview) {
        input.addEventListener('input', function() {
            preview.style.background = this.value;
        });
    }
});

// ========== REQUISITOS ==========
function agregarRequisito(titulo = '', descripcion = '') {
    const id = Date.now() + Math.random();
    requisitosItems.push({ id, titulo, descripcion });
    renderizarRequisitos();
}

function eliminarRequisito(id) {
    requisitosItems = requisitosItems.filter(item => item.id !== id);
    renderizarRequisitos();
}

function renderizarRequisitos() {
    const container = document.getElementById('requisitos-list');
    container.innerHTML = '';

    requisitosItems.forEach((item, index) => {
        const div = document.createElement('div');
        div.className = 'item-card';
        div.innerHTML = `
            <div class="item-number">${index + 1}</div>
            <div class="item-card-content">
                <input type="text"
                    placeholder="Título del requisito (ej: Desarrollo Frontend)"
                    value="${escapeHtml(item.titulo)}"
                    onchange="actualizarRequisito(${item.id}, 'titulo', this.value)">
                <textarea
                    placeholder="Descripción detallada del requisito"
                    onchange="actualizarRequisito(${item.id}, 'descripcion', this.value)">${escapeHtml(item.descripcion)}</textarea>
            </div>
            <div class="item-card-actions">
                <button type="button" class="btn-remove-item" onclick="eliminarRequisito(${item.id})" title="Eliminar"><i class="material-icons">delete</i></button>
            </div>
        `;
        container.appendChild(div);
    });

    actualizarHTMLRequisitos();
}

function actualizarRequisito(id, campo, valor) {
    const item = requisitosItems.find(i => i.id === id);
    if (item) {
        item[campo] = valor;
        actualizarHTMLRequisitos();
    }
}

function actualizarHTMLRequisitos() {
    let html = '';
    requisitosItems.forEach((item, index) => {
        html += `<li><strong>${index + 1}. ${escapeHtml(item.titulo)}</strong><span>${escapeHtml(item.descripcion)}</span></li>`;
    });
    document.getElementById('requisitos').value = html ? `<ul class="requirements-list">${html}</ul>` : '';
}

// ========== ESTILO VISUAL ==========
function agregarEstilo(texto = '') {
    const id = Date.now() + Math.random();
    estiloItems.push({ id, texto });
    renderizarEstilo();
}

function eliminarEstilo(id) {
    estiloItems = estiloItems.filter(item => item.id !== id);
    renderizarEstilo();
}

function renderizarEstilo() {
    const container = document.getElementById('estilo-list');
    container.innerHTML = '';

    estiloItems.forEach(item => {
        const div = document.createElement('div');
        div.className = 'item-card';
        div.innerHTML = `
            <div class="item-card-content">
                <input type="text"
                    placeholder="Item de estilo visual (ej: Diseño de interfaz corporativa)"
                    value="${escapeHtml(item.texto)}"
                    onchange="actualizarEstilo(${item.id}, this.value)">
            </div>
            <div class="item-card-actions">
                <button type="button" class="btn-remove-item" onclick="eliminarEstilo(${item.id})" title="Eliminar"><i class="material-icons">delete</i></button>
            </div>
        `;
        container.appendChild(div);
    });

    actualizarHTMLEstilo();
}

function actualizarEstilo(id, valor) {
    const item = estiloItems.find(i => i.id === id);
    if (item) {
        item.texto = valor;
        actualizarHTMLEstilo();
    }
}

function actualizarHTMLEstilo() {
    let html = '';
    estiloItems.forEach(item => {
        html += `<li>${escapeHtml(item.texto)}</li>`;
    });
    document.getElementById('estilo').value = html ? `<ul>${html}</ul>` : '';
}

// ========== PRECIOS ==========
function agregarPrecio(concepto = '', descripcion = '', valor = '') {
    const id = Date.now() + Math.random();
    const valorFormateado = valor ? formatearValor(valor) : '';
    preciosItems.push({ id, concepto, descripcion, valor: valorFormateado || valor, isTotal: false });
    renderizarPrecios();
}

function eliminarPrecio(id) {
    preciosItems = preciosItems.filter(item => item.id !== id && !item.isTotal);
    // Asegurar que siempre haya un total
    const tieneTotal = preciosItems.some(item => item.isTotal);
    if (!tieneTotal && preciosItems.length > 0) {
        agregarTotalPrecio();
    } else {
        renderizarPrecios();
    }
}

// Función para formatear valor monetario
function formatearValor(valor) {
    if (!valor) return '';
    // Remover todo lo que no sea número
    const numeros = valor.toString().replace(/[^0-9]/g, '');
    if (!numeros) return '';
    // Formatear con puntos para miles
    const num = parseInt(numeros);
    return '$' + num.toLocaleString('es-ES');
}

// Función para extraer número de un valor monetario
function extraerNumero(valor) {
    if (!valor) return 0;
    const numeros = valor.toString().replace(/[^0-9]/g, '');
    return parseInt(numeros) || 0;
}

// Calcular total automáticamente
function calcularTotal() {
    let total = 0;
    preciosItems.forEach(item => {
        if (!item.isTotal) {
            total += extraerNumero(item.valor);
        }
    });
    return total;
}

function renderizarPrecios() {
    const tbody = document.getElementById('precios-list');
    tbody.innerHTML = '';

    // Separar items normales y total
    const itemsNormales = preciosItems.filter(item => !item.isTotal);
    const totalItem = preciosItems.find(item => item.isTotal);

    // Renderizar items normales
    itemsNormales.forEach(item => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <input type="text"
                    class="price-input"
                    placeholder="Concepto"
                    value="${escapeHtml(item.concepto)}"
                    onchange="actualizarPrecio(${item.id}, 'concepto', this.value)">
            </td>
            <td>
                <input type="text"
                    placeholder="Descripción"
                    value="${escapeHtml(item.descripcion)}"
                    onchange="actualizarPrecio(${item.id}, 'descripcion', this.value)">
            </td>
            <td>
                <input type="text"
                    class="price-input"
                    placeholder="Valor (ej: $1.000.000)"
                    value="${escapeHtml(item.valor)}"
                    onblur="formatearValorInput(${item.id}, this)"
                    onchange="actualizarPrecio(${item.id}, 'valor', this.value); calcularYActualizarTotal()">
            </td>
            <td>
                <div style="display:flex;gap:0.5rem;">
                    <button type="button" class="btn-remove-item" onclick="eliminarPrecio(${item.id})" title="Eliminar"><i class="material-icons">delete</i></button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });

    // Renderizar fila de total
    const totalCalculado = calcularTotal();
    const totalFormateado = formatearValor(totalCalculado.toString());

    if (!totalItem) {
        // Crear item de total si no existe
        agregarTotalPrecio();
        return;
    }

    // Actualizar valor del total
    totalItem.valor = totalFormateado || '$0';

    const trTotal = document.createElement('tr');
    trTotal.className = 'total-row';
    trTotal.innerHTML = `
        <td>
            <input type="text"
                value="TOTAL"
                readonly
                style="font-weight:bold;background:#f0f0f0;border:none;"
                class="total-display">
        </td>
        <td>
            <input type="text"
                value=""
                readonly
                style="background:#f0f0f0;border:none;">
        </td>
        <td>
            <input type="text"
                value="${escapeHtml(totalFormateado)}"
                readonly
                style="font-weight:bold;font-size:1.2em;background:#f0f0f0;border:none;color:#002855;"
                class="total-display">
        </td>
        <td></td>
    `;
    tbody.appendChild(trTotal);

    actualizarHTMLPrecios();
}

function formatearValorInput(id, input) {
    const valor = formatearValor(input.value);
    if (valor && valor !== input.value) {
        input.value = valor;
        actualizarPrecio(id, 'valor', valor);
        calcularYActualizarTotal();
    }
}

function calcularYActualizarTotal() {
    const totalCalculado = calcularTotal();
    const totalFormateado = formatearValor(totalCalculado.toString());
    const totalItem = preciosItems.find(item => item.isTotal);
    if (totalItem) {
        totalItem.valor = totalFormateado || '$0';
        renderizarPrecios();
    }
}

function agregarTotalPrecio() {
    const id = Date.now() + Math.random();
    const totalCalculado = calcularTotal();
    const totalFormateado = formatearValor(totalCalculado.toString());
    preciosItems.push({ id, concepto: 'TOTAL', descripcion: '', valor: totalFormateado || '$0', isTotal: true });
    renderizarPrecios();
}

function actualizarPrecio(id, campo, valor) {
    const item = preciosItems.find(i => i.id === id);
    if (item && !item.isTotal) {
        item[campo] = valor;
        if (campo === 'valor') {
            calcularYActualizarTotal();
        } else {
            actualizarHTMLPrecios();
        }
    }
}

function actualizarHTMLPrecios() {
    let html = '';
    preciosItems.forEach(item => {
        if (item.isTotal) {
            html += `<tr class="total-row"><td colspan="2"><strong>${escapeHtml(item.concepto)}</strong></td><td><strong>${escapeHtml(item.valor)}</strong></td></tr>`;
        } else {
            html += `<tr><td>${escapeHtml(item.concepto)}</td><td>${escapeHtml(item.descripcion)}</td><td>${escapeHtml(item.valor)}</td></tr>`;
        }
    });
    document.getElementById('precios').value = html || '';
}

// ========== TÉRMINOS ==========
function agregarTermino(titulo = '', descripcion = '') {
    const id = Date.now() + Math.random();
    terminosItems.push({ id, titulo, descripcion });
    renderizarTerminos();
}

function eliminarTermino(id) {
    terminosItems = terminosItems.filter(item => item.id !== id);
    renderizarTerminos();
}

function renderizarTerminos() {
    const container = document.getElementById('terminos-list');
    container.innerHTML = '';

    terminosItems.forEach(item => {
        const div = document.createElement('div');
        div.className = 'item-card';
        div.innerHTML = `
            <div class="item-card-content">
                <input type="text"
                    placeholder="Título (ej: Forma de Pago)"
                    value="${escapeHtml(item.titulo)}"
                    onchange="actualizarTermino(${item.id}, 'titulo', this.value)">
                <textarea
                    placeholder="Descripción del término"
                    onchange="actualizarTermino(${item.id}, 'descripcion', this.value)">${escapeHtml(item.descripcion)}</textarea>
            </div>
            <div class="item-card-actions">
                <button type="button" class="btn-remove-item" onclick="eliminarTermino(${item.id})" title="Eliminar"><i class="material-icons">delete</i></button>
            </div>
        `;
        container.appendChild(div);
    });

    actualizarHTMLTerminos();
}

function actualizarTermino(id, campo, valor) {
    const item = terminosItems.find(i => i.id === id);
    if (item) {
        item[campo] = valor;
        actualizarHTMLTerminos();
    }
}

function actualizarHTMLTerminos() {
    let html = '';
    terminosItems.forEach(item => {
        html += `<li><strong>${escapeHtml(item.titulo)}</strong><span>${escapeHtml(item.descripcion)}</span></li>`;
    });
    document.getElementById('terminos').value = html ? `<ul class="requirements-list">${html}</ul>` : '';
}

// ========== CONTACTO ==========
function actualizarHTMLContacto() {
    const nombre = document.getElementById('contacto_nombre').value || '';
    const descripcion = document.getElementById('contacto_descripcion').value || '';
    const sitio = document.getElementById('contacto_sitio').value || '';
    const github = document.getElementById('contacto_github').value || '';
    const email = document.getElementById('contacto_email').value || '';
    const telefono = document.getElementById('contacto_telefono').value || '';
    const ubicacion = document.getElementById('contacto_ubicacion').value || '';

    let html = '';
    if (nombre || descripcion) {
        html += `<p><strong>${escapeHtml(nombre)}</strong>${descripcion ? ' - ' + escapeHtml(descripcion) : ''}</p>`;
    }

    const links = [];
    if (sitio) links.push(`<a href="${escapeHtml(sitio)}" target="_blank">Sitio web</a>`);
    if (github) links.push(`<a href="${escapeHtml(github)}" target="_blank">GitHub</a>`);
    if (links.length > 0) {
        html += `<p>${links.join(' | ')}</p>`;
    }

    const contactoInfo = [];
    if (email) contactoInfo.push(escapeHtml(email));
    if (telefono) contactoInfo.push(escapeHtml(telefono));
    if (contactoInfo.length > 0) {
        html += `<p>${contactoInfo.join(' | ')}</p>`;
    }

    if (ubicacion) {
        html += `<p>${escapeHtml(ubicacion)}</p>`;
    }

    document.getElementById('contacto').value = html;
}

// Agregar listeners a los campos de contacto
document.addEventListener('DOMContentLoaded', function() {
    const contactFields = ['contacto_nombre', 'contacto_descripcion', 'contacto_sitio', 'contacto_github', 'contacto_email', 'contacto_telefono', 'contacto_ubicacion'];
    contactFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', actualizarHTMLContacto);
        }
    });
});

// ========== UTILIDADES ==========
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function parsearHTMLExistente() {
    // Limpiar arrays antes de parsear
    requisitosItems = [];
    estiloItems = [];
    preciosItems = [];
    terminosItems = [];

    // Debug: verificar que los campos hidden existan
    const campos = ['requisitos', 'estilo', 'precios', 'terminos', 'contacto'];
    campos.forEach(campo => {
        const elem = document.getElementById(campo);
        if (!elem) {
            console.warn(`Campo ${campo} no encontrado en el DOM`);
        }
    });

    // Parsear requisitos
    const requisitosHTML = document.getElementById('requisitos')?.value || '';
    if (requisitosHTML && requisitosHTML.trim() !== '') {
        const parser = new DOMParser();
        const doc = parser.parseFromString(requisitosHTML, 'text/html');
        const items = doc.querySelectorAll('li');
        items.forEach(li => {
            const strong = li.querySelector('strong');
            const span = li.querySelector('span');
            const titulo = strong ? strong.textContent.replace(/^\d+\.\s*/, '').trim() : '';
            const descripcion = span ? span.textContent.trim() : '';
            if (titulo || descripcion) {
                requisitosItems.push({
                    id: Date.now() + Math.random(),
                    titulo: titulo,
                    descripcion: descripcion
                });
            }
        });
        renderizarRequisitos();
    }

    // Parsear estilo
    const estiloHTML = document.getElementById('estilo')?.value || '';
    if (estiloHTML && estiloHTML.trim() !== '') {
        const parser = new DOMParser();
        const doc = parser.parseFromString(estiloHTML, 'text/html');
        const items = doc.querySelectorAll('li');
        items.forEach(li => {
            const texto = li.textContent.trim();
            if (texto) {
                estiloItems.push({
                    id: Date.now() + Math.random(),
                    texto: texto
                });
            }
        });
        renderizarEstilo();
    }

    // Parsear precios
    const preciosHTML = document.getElementById('precios')?.value || '';
    if (preciosHTML && preciosHTML.trim() !== '') {
        // Intentar parsear como HTML completo de tabla o solo filas
        let htmlToParse = preciosHTML.trim();

        // Si no tiene etiquetas de tabla, envolver las filas en una tabla
        if (!htmlToParse.includes('<table') && htmlToParse.includes('<tr')) {
            htmlToParse = '<table><tbody>' + htmlToParse + '</tbody></table>';
        } else if (!htmlToParse.includes('<tr') && htmlToParse.includes('<td')) {
            // Si solo hay celdas sueltas, crear filas
            htmlToParse = '<table><tbody><tr>' + htmlToParse + '</tr></tbody></table>';
        }

        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlToParse, 'text/html');
        const rows = doc.querySelectorAll('tr');

        rows.forEach((tr, index) => {
            const tds = tr.querySelectorAll('td');
            if (tds.length >= 3) {
                const isTotal = tr.classList.contains('total-row') ||
                               tr.querySelector('td strong')?.textContent.trim().toUpperCase() === 'TOTAL';

                // Extraer texto limpio de cada celda
                let concepto = '';
                let descripcion = '';
                let valor = '';

                // Concepto (primera columna)
                const conceptoEl = tds[0];
                if (conceptoEl) {
                    const conceptoStrong = conceptoEl.querySelector('strong');
                    concepto = conceptoStrong ? conceptoStrong.textContent.trim() : conceptoEl.textContent.trim();
                }

                // Descripción (segunda columna, puede tener colspan en total)
                if (tds.length > 1 && !tds[1].hasAttribute('colspan')) {
                    const descripcionEl = tds[1];
                    if (descripcionEl) {
                        const descripcionStrong = descripcionEl.querySelector('strong');
                        descripcion = descripcionStrong ? descripcionStrong.textContent.trim() : descripcionEl.textContent.trim();
                    }
                }

                // Valor (última columna)
                const valorEl = tds[tds.length - 1];
                if (valorEl) {
                    const valorStrong = valorEl.querySelector('strong');
                    valor = valorStrong ? valorStrong.textContent.trim() : valorEl.textContent.trim();
                }

                // Solo agregar si tiene contenido válido
                if (concepto || descripcion || valor) {
                    preciosItems.push({
                        id: Date.now() + Math.random() + index,
                        concepto: concepto || '',
                        descripcion: descripcion || '',
                        valor: valor || '',
                        isTotal: isTotal
                    });
                }
            }
        });

        // Si no hay total, crearlo automáticamente
        const tieneTotal = preciosItems.some(item => item.isTotal);
        if (!tieneTotal && preciosItems.length > 0) {
            agregarTotalPrecio();
        } else {
            renderizarPrecios();
        }
    }

    // Parsear términos
    const terminosHTML = document.getElementById('terminos')?.value || '';
    if (terminosHTML && terminosHTML.trim() !== '') {
        const parser = new DOMParser();
        const doc = parser.parseFromString(terminosHTML, 'text/html');
        const items = doc.querySelectorAll('li');
        items.forEach(li => {
            const strong = li.querySelector('strong');
            const span = li.querySelector('span');
            const titulo = strong ? strong.textContent.trim() : '';
            const descripcion = span ? span.textContent.trim() : '';
            if (titulo || descripcion) {
                terminosItems.push({
                    id: Date.now() + Math.random(),
                    titulo: titulo,
                    descripcion: descripcion
                });
            }
        });
        renderizarTerminos();
    }

    // Parsear contacto
    const contactoHTML = document.getElementById('contacto')?.value || '';
    if (contactoHTML && contactoHTML.trim() !== '') {
        const parser = new DOMParser();
        const doc = parser.parseFromString(contactoHTML, 'text/html');

        // Nombre y descripción
        const firstP = doc.querySelector('p');
        if (firstP) {
            const strong = firstP.querySelector('strong');
            if (strong) {
                const nombreTexto = strong.textContent.trim();
                document.getElementById('contacto_nombre').value = nombreTexto;

                // Buscar descripción después de " - "
                const fullText = firstP.textContent.trim();
                const descMatch = fullText.match(/\s*-\s*(.+)/);
                if (descMatch) {
                    document.getElementById('contacto_descripcion').value = descMatch[1].trim();
                }
            }
        }

        // Links
        const links = doc.querySelectorAll('a');
        links.forEach(link => {
            const href = link.getAttribute('href');
            if (href && href !== '#') {
                const text = link.textContent.toLowerCase().trim();
                const linkText = text || href.toLowerCase();

                if (linkText.includes('sitio') || linkText.includes('web') || linkText.includes('http')) {
                    // Si no hay sitio ya cargado o este parece ser el principal
                    if (!document.getElementById('contacto_sitio').value || text.includes('sitio') || text.includes('web')) {
                        document.getElementById('contacto_sitio').value = href;
                    }
                } else if (linkText.includes('github') || linkText.includes('git')) {
                    document.getElementById('contacto_github').value = href;
                }
            }
        });

        // Email y teléfono - buscar en todo el texto
        const allText = doc.body.textContent || '';
        const allHTML = doc.body.innerHTML || '';

        // Buscar email (patrón de email) en todo el documento
        const emailRegex = /([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9_-]+)/gi;
        const emailMatches = allText.match(emailRegex);
        if (emailMatches && emailMatches.length > 0) {
            // Tomar el primer email encontrado que no sea un ejemplo
            const email = emailMatches.find(e => !e.includes('ejemplo.com') && !e.includes('example')) || emailMatches[0];
            document.getElementById('contacto_email').value = email.trim();
        }

        // Buscar teléfono (patrones comunes de teléfono)
        const phonePatterns = [
            /([+]?\d{1,4}[\s.-]?\(?\d{1,4}\)?[\s.-]?\d{1,4}[\s.-]?\d{1,4}[\s.-]?\d{1,9})/g,
            /([+]?\d{1,4}[\s.-]?\d{3,4}[\s.-]?\d{3,4}[\s.-]?\d{3,4})/g,
            /(\d{10,15})/g
        ];

        let phoneFound = false;
        for (const pattern of phonePatterns) {
            const phoneMatches = allText.match(pattern);
            if (phoneMatches && phoneMatches.length > 0) {
                // Filtrar números que parezcan teléfonos (mínimo 8 dígitos, máximo 15)
                const phones = phoneMatches.filter(p => {
                    const digits = p.replace(/\D/g, '');
                    return digits.length >= 8 && digits.length <= 15;
                });
                if (phones.length > 0) {
                    const phone = phones[0].trim();
                    if (!phone.includes('XXX') && !phone.includes('000')) {
                        document.getElementById('contacto_telefono').value = phone;
                        phoneFound = true;
                        break;
                    }
                }
            }
        }

        // También buscar por emojis si están presentes (compatibilidad con datos antiguos)
        const emailEmojiMatch = allText.match(/📧\s*([^\s|]+)/);
        const phoneEmojiMatch = allText.match(/📱\s*([^\s|]+)/);
        if (emailEmojiMatch && !document.getElementById('contacto_email').value) {
            document.getElementById('contacto_email').value = emailEmojiMatch[1].trim();
        }
        if (phoneEmojiMatch && !phoneFound && !document.getElementById('contacto_telefono').value) {
            document.getElementById('contacto_telefono').value = phoneEmojiMatch[1].trim();
        }

        // Ubicación - buscar texto después de emoji o al final
        const ubicEmojiMatch = allText.match(/📍\s*(.+?)(?:\n|$)/);
        if (ubicEmojiMatch) {
            document.getElementById('contacto_ubicacion').value = ubicEmojiMatch[1].trim();
        } else {
            // Buscar en los párrafos
            const allPs = doc.querySelectorAll('p');
            if (allPs.length > 0) {
                // Buscar en el último párrafo que no tenga links ni email
                for (let i = allPs.length - 1; i >= 0; i--) {
                    const p = allPs[i];
                    const pText = p.textContent.trim();
                    const hasLink = p.querySelector('a');
                    const hasEmail = pText.includes('@');
                    const hasPhone = phonePatterns.some(pattern => pattern.test(pText));

                    if (!hasLink && !hasEmail && !hasPhone && pText.length > 0 && pText.length < 100) {
                        // Parece ser una ubicación
                        document.getElementById('contacto_ubicacion').value = pText;
                        break;
                    }
                }
            }
        }

        // Actualizar el HTML de contacto después de parsear
        actualizarHTMLContacto();
    }
}

// Manejar subida de logo
async function manejarSubidaLogo() {
    const logoFile = document.getElementById('logo_file');
    const logoUrlInput = document.getElementById('logo_url');
    const previewDiv = document.getElementById('logo-preview');
    const previewImg = document.getElementById('logo-preview-img');

    if (!logoFile || !logoFile.files || !logoFile.files[0]) {
        return;
    }

    const file = logoFile.files[0];

    // Validar que sea PNG
    if (file.type !== 'image/png') {
        showError('Solo se permiten archivos PNG');
        logoFile.value = '';
        return;
    }

    // Validar tamaño (máximo 5MB)
    if (file.size > 5 * 1024 * 1024) {
        showError('El archivo es muy grande. Máximo 5MB');
        logoFile.value = '';
        return;
    }

    // Mostrar preview local
    const reader = new FileReader();
    reader.onload = function(e) {
        previewImg.src = e.target.result;
        previewDiv.style.display = 'block';
    };
    reader.readAsDataURL(file);

    // Subir archivo
    try {
        const formData = new FormData();
        formData.append('logo', file);

        const response = await fetch('../api/upload_logo.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            logoUrlInput.value = data.url;
            showSuccess('Logo subido exitosamente');
        } else {
            showError(data.message || 'Error al subir el logo');
            logoFile.value = '';
            previewDiv.style.display = 'none';
        }
    } catch (error) {
        showError('Error al subir el logo. Por favor, intenta de nuevo.');
        console.error(error);
        logoFile.value = '';
        previewDiv.style.display = 'none';
    }
}

// Eliminar logo
function eliminarLogo() {
    const logoFile = document.getElementById('logo_file');
    const logoUrlInput = document.getElementById('logo_url');
    const previewDiv = document.getElementById('logo-preview');

    if (logoFile) logoFile.value = '';
    if (logoUrlInput) logoUrlInput.value = '';
    if (previewDiv) previewDiv.style.display = 'none';
}

// Inicializar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    // Manejo del logo
    const logoFile = document.getElementById('logo_file');
    const removeLogoBtn = document.getElementById('remove-logo-btn');
    const logoUrlInput = document.getElementById('logo_url');

    if (logoFile) {
        logoFile.addEventListener('change', manejarSubidaLogo);
    }

    if (removeLogoBtn) {
        removeLogoBtn.addEventListener('click', eliminarLogo);
    }

    // Cargar preview si ya hay un logo
    if (logoUrlInput && logoUrlInput.value) {
        const previewDiv = document.getElementById('logo-preview');
        const previewImg = document.getElementById('logo-preview-img');
        if (previewDiv && previewImg) {
            // Ajustar ruta relativa si es necesario
            let logoPath = logoUrlInput.value;
            if (logoPath && !logoPath.startsWith('http') && !logoPath.startsWith('/') && !logoPath.startsWith('../')) {
                logoPath = '../' + logoPath;
            }
            previewImg.src = logoPath;
            previewDiv.style.display = 'block';
        }
    }

    // Esperar un momento para asegurar que todos los elementos estén disponibles
    setTimeout(() => {
        // Primero parsear HTML existente
        parsearHTMLExistente();

        // Si no hay items después de parsear y estamos en crear_cotizacion, agregar algunos por defecto
        const esCrear = !document.getElementById('cotizacion_id')?.value;

        if (requisitosItems.length === 0 && esCrear) {
            agregarRequisito('Desarrollo Frontend', 'Interfaz de usuario responsive con HTML5, CSS3 y JavaScript');
        }
        if (estiloItems.length === 0 && esCrear) {
            agregarEstilo('Diseño de interfaz corporativa con identidad visual');
        }
        if (preciosItems.length === 0 && esCrear) {
            agregarPrecio('Concepto 1', 'Descripción del servicio', '$0');
            // agregarTotalPrecio() se llamará automáticamente en renderizarPrecios()
        }
        if (terminosItems.length === 0 && esCrear) {
            agregarTermino('Forma de Pago', '50% al inicio, 50% al finalizar');
        }
    }, 100);
});

// Manejar envío del formulario
document.getElementById('cotizacionForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    // Asegurar que todos los HTML estén actualizados
    actualizarHTMLRequisitos();
    actualizarHTMLEstilo();
    actualizarHTMLPrecios();
    actualizarHTMLTerminos();
    actualizarHTMLContacto();

    const formData = {
        id: document.getElementById('cotizacion_id').value || null,
        titulo: document.getElementById('titulo').value,
        cliente: document.getElementById('cliente').value,
        desarrollador: document.getElementById('desarrollador').value,
        validez: document.getElementById('validez').value,
        color_primario: document.getElementById('color_primario').value,
        color_secundario: document.getElementById('color_secundario').value,
        color_acento: document.getElementById('color_acento').value,
        logo_url: document.getElementById('logo_url')?.value || '',
        requisitos: document.getElementById('requisitos').value,
        estilo: document.getElementById('estilo').value,
        precios: document.getElementById('precios').value,
        terminos: document.getElementById('terminos').value,
        contacto: document.getElementById('contacto').value
    };

    try {
        const response = await fetch('../api/guardar_cotizacion.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            showSuccess('Cotización guardada exitosamente', 2000);
            setTimeout(() => {
                window.location.href = 'dashboard.php';
            }, 1500);
        } else {
            showError(data.message || 'Error al guardar la cotización');
        }
    } catch (error) {
        showError('Error al guardar la cotización. Por favor, intenta de nuevo.');
        console.error(error);
    }
});
