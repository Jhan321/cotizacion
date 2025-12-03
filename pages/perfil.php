<?php
require_once '../includes/config.php';
requireLogin();

$pdo = getDB();
$usuario = getCurrentUser();

// Obtener estadísticas del usuario
$stmt = $pdo->prepare("SELECT
    COUNT(*) as total_cotizaciones,
    MAX(creado_en) as ultima_cotizacion,
    MIN(creado_en) as primera_cotizacion
    FROM cotizaciones
    WHERE usuario_id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$stats = $stmt->fetch();

// Obtener cotizaciones recientes
$stmt = $pdo->prepare("SELECT id, titulo, cliente, creado_en, codigo_unico
    FROM cotizaciones
    WHERE usuario_id = ?
    ORDER BY creado_en DESC
    LIMIT 10");
$stmt->execute([$_SESSION['usuario_id']]);
$cotizaciones_recientes = $stmt->fetchAll();

$tab_actual = $_GET['tab'] ?? 'informacion';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Sistema de Cotizaciones</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../assets/css/profile.css">
    <link rel="stylesheet" href="../assets/css/notifications.css">
</head>
<body>
    <div class="header">
        <div class="header-content">
            <a href="dashboard.php" class="back-btn">
                <i class="material-icons">arrow_back</i> Volver al Dashboard
            </a>
            <h1><i class="material-icons">account_circle</i> Mi Perfil</h1>
        </div>
    </div>

    <div class="container">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="material-icons">account_circle</i>
            </div>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($usuario['nombre_completo']); ?></h2>
                <p><i class="material-icons">email</i> <?php echo htmlspecialchars($usuario['email']); ?></p>
                <p><i class="material-icons">person</i> Usuario: <?php echo htmlspecialchars($usuario['usuario']); ?></p>
            </div>
        </div>

        <div class="profile-nav">
            <button class="nav-tab <?php echo $tab_actual === 'informacion' ? 'active' : ''; ?>" onclick="cambiarTab('informacion')">
                <i class="material-icons">info</i> Información
            </button>
            <button class="nav-tab <?php echo $tab_actual === 'cotizaciones' ? 'active' : ''; ?>" onclick="cambiarTab('cotizaciones')">
                <i class="material-icons">description</i> Mis Proyectos
            </button>
            <button class="nav-tab <?php echo $tab_actual === 'analiticas' ? 'active' : ''; ?>" onclick="cambiarTab('analiticas')">
                <i class="material-icons">analytics</i> Analíticas
            </button>
            <button class="nav-tab <?php echo $tab_actual === 'seguridad' ? 'active' : ''; ?>" onclick="cambiarTab('seguridad')">
                <i class="material-icons">lock</i> Seguridad
            </button>
            <button class="nav-tab danger <?php echo $tab_actual === 'eliminar' ? 'active' : ''; ?>" onclick="cambiarTab('eliminar')">
                <i class="material-icons">delete</i> Eliminar Cuenta
            </button>
        </div>

        <div class="profile-content">
            <!-- Tab: Información -->
            <div id="tab-informacion" class="tab-content <?php echo $tab_actual === 'informacion' ? 'active' : ''; ?>">
                <div class="card">
                    <h3><i class="material-icons">person</i> Información Personal</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Nombre Completo</label>
                            <p><?php echo htmlspecialchars($usuario['nombre_completo']); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Usuario</label>
                            <p><?php echo htmlspecialchars($usuario['usuario']); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Email</label>
                            <p><?php echo htmlspecialchars($usuario['email']); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Miembro desde</label>
                            <p><?php
                                $stmt = $pdo->prepare("SELECT creado_en FROM usuarios WHERE id = ?");
                                $stmt->execute([$_SESSION['usuario_id']]);
                                $fecha_registro = $stmt->fetchColumn();
                                echo date('d/m/Y', strtotime($fecha_registro));
                            ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Cotizaciones/Proyectos -->
            <div id="tab-cotizaciones" class="tab-content <?php echo $tab_actual === 'cotizaciones' ? 'active' : ''; ?>">
                <div class="card">
                    <h3><i class="material-icons">description</i> Mis Proyectos (<?php echo $stats['total_cotizaciones']; ?>)</h3>
                    <?php if (empty($cotizaciones_recientes)): ?>
                        <div class="empty-state">
                            <i class="material-icons">description</i>
                            <p>No tienes cotizaciones aún</p>
                            <a href="crear_cotizacion.php" class="btn btn-primary">
                                <i class="material-icons">add</i> Crear Primera Cotización
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="projects-list">
                            <?php foreach ($cotizaciones_recientes as $cot): ?>
                                <div class="project-item">
                                    <div class="project-icon">
                                        <i class="material-icons">description</i>
                                    </div>
                                    <div class="project-info">
                                        <h4><?php echo htmlspecialchars($cot['titulo']); ?></h4>
                                        <p><i class="material-icons">business</i> <?php echo htmlspecialchars($cot['cliente']); ?></p>
                                        <p class="date"><i class="material-icons">event</i> Creado: <?php echo date('d/m/Y', strtotime($cot['creado_en'])); ?></p>
                                    </div>
                                    <div class="project-actions">
                                        <a href="editar_cotizacion.php?id=<?php echo $cot['id']; ?>" class="btn-icon" title="Editar">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <a href="ver_cotizacion.php?codigo=<?php echo $cot['codigo_unico']; ?>" target="_blank" class="btn-icon" title="Ver">
                                            <i class="material-icons">visibility</i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div style="margin-top: 1.5rem; text-align: center;">
                            <a href="dashboard.php" class="btn btn-secondary">
                                <i class="material-icons">list</i> Ver Todas las Cotizaciones
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tab: Analíticas -->
            <div id="tab-analiticas" class="tab-content <?php echo $tab_actual === 'analiticas' ? 'active' : ''; ?>">
                <div class="analytics-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="material-icons">description</i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['total_cotizaciones']; ?></h3>
                            <p>Total Cotizaciones</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="material-icons">event</i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['ultima_cotizacion'] ? date('d/m/Y', strtotime($stats['ultima_cotizacion'])) : 'N/A'; ?></h3>
                            <p>Última Cotización</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="material-icons">calendar_today</i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['primera_cotizacion'] ? date('d/m/Y', strtotime($stats['primera_cotizacion'])) : 'N/A'; ?></h3>
                            <p>Primera Cotización</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <h3><i class="material-icons">trending_up</i> Resumen de Actividad</h3>
                    <p style="color: #666; margin-top: 1rem;">
                        <?php if ($stats['total_cotizaciones'] > 0): ?>
                            Has creado <strong><?php echo $stats['total_cotizaciones']; ?></strong> cotización(es)
                            <?php if ($stats['primera_cotizacion']): ?>
                                desde <?php echo date('d/m/Y', strtotime($stats['primera_cotizacion'])); ?>.
                            <?php endif; ?>
                            <?php if ($stats['ultima_cotizacion']): ?>
                                Tu última cotización fue creada el <?php echo date('d/m/Y', strtotime($stats['ultima_cotizacion'])); ?>.
                            <?php endif; ?>
                        <?php else: ?>
                            Aún no has creado ninguna cotización. ¡Comienza ahora!
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- Tab: Seguridad (Cambiar contraseña) -->
            <div id="tab-seguridad" class="tab-content <?php echo $tab_actual === 'seguridad' ? 'active' : ''; ?>">
                <div class="card">
                    <h3><i class="material-icons">lock</i> Cambiar Contraseña</h3>
                    <form id="changePasswordForm" class="form">
                        <div class="form-group">
                            <label for="current_password">Contraseña Actual</label>
                            <input type="password" id="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">Nueva Contraseña</label>
                            <input type="password" id="new_password" required minlength="6">
                            <small>Mínimo 6 caracteres</small>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirmar Nueva Contraseña</label>
                            <input type="password" id="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons">save</i> Cambiar Contraseña
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tab: Eliminar Cuenta -->
            <div id="tab-eliminar" class="tab-content <?php echo $tab_actual === 'eliminar' ? 'active' : ''; ?>">
                <div class="card danger-card">
                    <h3><i class="material-icons">warning</i> Eliminar Cuenta</h3>
                    <div class="warning-box">
                        <p><strong>⚠️ Advertencia:</strong> Esta acción es permanente e irreversible.</p>
                        <ul>
                            <li>Se eliminarán todas tus cotizaciones</li>
                            <li>Se eliminará toda tu información personal</li>
                            <li>No podrás recuperar tu cuenta</li>
                        </ul>
                    </div>
                    <form id="deleteAccountForm" class="form">
                        <div class="form-group">
                            <label for="delete_password">Confirma tu contraseña para eliminar la cuenta</label>
                            <input type="password" id="delete_password" required>
                        </div>
                        <button type="submit" class="btn btn-danger">
                            <i class="material-icons">delete_forever</i> Eliminar Mi Cuenta Permanentemente
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/notifications.js"></script>
    <script>
        function cambiarTab(tab) {
            // Ocultar todos los tabs
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));

            // Mostrar tab seleccionado
            document.getElementById('tab-' + tab).classList.add('active');
            event.target.closest('.nav-tab').classList.add('active');

            // Actualizar URL sin recargar
            window.history.pushState({}, '', '?tab=' + tab);
        }

        // Cambiar contraseña
        document.getElementById('changePasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                showError('Las contraseñas no coinciden');
                return;
            }

            if (newPassword.length < 6) {
                showError('La contraseña debe tener al menos 6 caracteres');
                return;
            }

            try {
                const response = await fetch('../api/cambiar_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        current_password: currentPassword,
                        new_password: newPassword
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess('Contraseña cambiada exitosamente', 2000);
                    document.getElementById('changePasswordForm').reset();
                } else {
                    showError(data.message || 'Error al cambiar la contraseña');
                }
            } catch (error) {
                showError('Error al cambiar la contraseña. Por favor, intenta de nuevo.');
                console.error(error);
            }
        });

        // Eliminar cuenta
        document.getElementById('deleteAccountForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const password = document.getElementById('delete_password').value;

            showConfirm(
                '¿Estás ABSOLUTAMENTE seguro de eliminar tu cuenta? Esta acción es permanente e irreversible. Se eliminarán todas tus cotizaciones y datos.',
                'Eliminar Cuenta Permanentemente',
                'error'
            ).then(confirmed => {
                if (confirmed) {
                    fetch('../api/eliminar_cuenta.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            password: password
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccess('Cuenta eliminada exitosamente. Redirigiendo...', 2000);
                            setTimeout(() => {
                                window.location.href = '../index.php';
                            }, 2000);
                        } else {
                            showError(data.message || 'Error al eliminar la cuenta');
                        }
                    })
                    .catch(error => {
                        showError('Error al eliminar la cuenta. Por favor, intenta de nuevo.');
                        console.error(error);
                    });
                }
            });
        });
    </script>
</body>
</html>

