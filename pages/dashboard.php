<?php
require_once '../includes/config.php';
requireLogin();

$pdo = getDB();
$usuario = getCurrentUser();

// Ordenar por defecto
$orden = $_GET['orden'] ?? 'fecha_desc';
$ordenSQL = 'creado_en DESC';
switch ($orden) {
    case 'fecha_asc':
        $ordenSQL = 'creado_en ASC';
        break;
    case 'titulo_asc':
        $ordenSQL = 'titulo ASC';
        break;
    case 'titulo_desc':
        $ordenSQL = 'titulo DESC';
        break;
    default:
        $ordenSQL = 'creado_en DESC';
}

// Obtener cotizaciones del usuario
$stmt = $pdo->prepare("SELECT id, titulo, cliente, codigo_unico, creado_en, actualizado_en
                       FROM cotizaciones
                       WHERE usuario_id = ?
                       ORDER BY $ordenSQL");
$stmt->execute([$_SESSION['usuario_id']]);
$cotizaciones = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Cotizaciones</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/notifications.css">
</head>

<body>
    <div class="header">
        <h1><i class="material-icons">description</i> Mis Cotizaciones</h1>
        <div class="user-info">
            <a href="perfil.php" class="user-name" style="text-decoration: none; cursor: pointer;"><i class="material-icons">account_circle</i> <?php echo htmlspecialchars($usuario['nombre_completo']); ?></a>
            <a href="crear_cotizacion.php" class="btn btn-primary"><i class="material-icons">add</i> Nueva Cotización</a>
            <a href="../logout.php" class="btn btn-secondary"><i class="material-icons">logout</i> Cerrar Sesión</a>
        </div>
    </div>

    <div class="container">
        <div class="toolbar">
            <div class="sort-group">
                <i class="material-icons">sort</i>
                <label>Ordenar por:</label>
                <select onchange="window.location.href='?orden=' + this.value">
                    <option value="fecha_desc" <?php echo $orden === 'fecha_desc' ? 'selected' : ''; ?>>Fecha (Más recientes)</option>
                    <option value="fecha_asc" <?php echo $orden === 'fecha_asc' ? 'selected' : ''; ?>>Fecha (Más antiguas)</option>
                    <option value="titulo_asc" <?php echo $orden === 'titulo_asc' ? 'selected' : ''; ?>>Título (A-Z)</option>
                    <option value="titulo_desc" <?php echo $orden === 'titulo_desc' ? 'selected' : ''; ?>>Título (Z-A)</option>
                </select>
            </div>
            <div class="quote-count">
                <i class="material-icons">inventory_2</i>
                <strong><?php echo count($cotizaciones); ?></strong> cotización(es)
            </div>
        </div>

        <?php if (empty($cotizaciones)): ?>
            <div class="empty-state">
                <i class="material-icons">description</i>
                <h2>No hay cotizaciones aún</h2>
                <p>Crea tu primera cotización para comenzar</p>
                <a href="crear_cotizacion.php" class="btn btn-primary"><i class="material-icons">add</i> Crear Primera Cotización</a>
            </div>
        <?php else: ?>
            <div class="quotes-grid">
                <?php foreach ($cotizaciones as $cot): ?>
                    <div class="quote-card">
                        <div class="quote-card-header">
                            <div class="quote-icon">
                                <i class="material-icons">description</i>
                            </div>
                            <div class="quote-title-section">
                                <h3><?php echo htmlspecialchars($cot['titulo']); ?></h3>
                                <div class="cliente">
                                    <i class="material-icons">business</i>
                                    <?php echo htmlspecialchars($cot['cliente']); ?>
                                </div>
                            </div>
                        </div>

                        <div class="quote-footer">
                            <div class="quote-meta">
                                <div class="meta-item">
                                    <i class="material-icons">event</i>
                                    <span>
                                        <strong>Creado:</strong> <?php echo date('d/m/Y', strtotime($cot['creado_en'])); ?>
                                    </span>
                                </div>
                                <div class="meta-item">
                                    <i class="material-icons">update</i>
                                    <span>
                                        <strong>Actualizado:</strong> <?php echo date('d/m/Y', strtotime($cot['actualizado_en'])); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="quote-actions">
                                <div class="menu-container">
                                    <button class="menu-toggle" onclick="toggleMenu(<?php echo $cot['id']; ?>, event)">
                                        <i class="material-icons">more_vert</i>
                                    </button>
                                    <div class="menu-dropdown" id="menu-<?php echo $cot['id']; ?>">
                                        <a href="editar_cotizacion.php?id=<?php echo $cot['id']; ?>" class="menu-item menu-edit">
                                            <i class="material-icons">edit</i>
                                            <span>Editar</span>
                                        </a>
                                        <a href="ver_cotizacion.php?codigo=<?php echo $cot['codigo_unico']; ?>" target="_blank" class="menu-item menu-view">
                                            <i class="material-icons">visibility</i>
                                            <span>Ver</span>
                                        </a>
                                        <button onclick="duplicarCotizacion(<?php echo $cot['id']; ?>); closeMenu(<?php echo $cot['id']; ?>);" class="menu-item menu-duplicate">
                                            <i class="material-icons">content_copy</i>
                                            <span>Duplicar</span>
                                        </button>
                                        <button onclick="eliminarCotizacion(<?php echo $cot['id']; ?>); closeMenu(<?php echo $cot['id']; ?>);" class="menu-item menu-delete">
                                            <i class="material-icons">delete</i>
                                            <span>Eliminar</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="../assets/js/notifications.js"></script>
    <script>
        // Cerrar menús al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.menu-container')) {
                document.querySelectorAll('.menu-dropdown').forEach(menu => {
                    menu.classList.remove('active');
                });
            }
        });

        function toggleMenu(id, e) {
            if (e) {
                e.stopPropagation();
            }
            const menu = document.getElementById('menu-' + id);
            const allMenus = document.querySelectorAll('.menu-dropdown');

            // Cerrar otros menús abiertos
            allMenus.forEach(m => {
                if (m.id !== menu.id) {
                    m.classList.remove('active');
                }
            });

            // Toggle del menú actual
            menu.classList.toggle('active');
        }

        function closeMenu(id) {
            const menu = document.getElementById('menu-' + id);
            if (menu) {
                menu.classList.remove('active');
            }
        }

        function eliminarCotizacion(id) {
            showConfirm(
                '¿Estás seguro de eliminar esta cotización? Esta acción no se puede deshacer.',
                'Eliminar Cotización',
                'warning'
            ).then(confirmed => {
                if (confirmed) {
                    fetch('../api/eliminar_cotizacion.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                id: id
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showSuccess('Cotización eliminada exitosamente', 2000);
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            } else {
                                showError(data.message || 'Error al eliminar la cotización');
                            }
                        })
                        .catch(error => {
                            showError('Error al eliminar la cotización. Por favor, intenta de nuevo.');
                            console.error(error);
                        });
                }
            });
        }

        function duplicarCotizacion(id) {
            fetch('../api/duplicar_cotizacion.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccess('Cotización duplicada exitosamente', 2000);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showError(data.message || 'Error al duplicar la cotización');
                    }
                })
                .catch(error => {
                    showError('Error al duplicar la cotización. Por favor, intenta de nuevo.');
                    console.error(error);
                });
        }
    </script>
</body>

</html>
