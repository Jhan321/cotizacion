<?php
require_once '../includes/config.php';

// Si ya está logueado, redirigir al dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nombre_completo = trim($_POST['nombre_completo'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validaciones
    if (empty($usuario) || empty($email) || empty($nombre_completo) || empty($password)) {
        $error = 'Por favor completa todos los campos';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El email no es válido';
    } else {
        try {
            $pdo = getDB();

            // Verificar si el usuario ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ? OR email = ?");
            $stmt->execute([$usuario, $email]);
            if ($stmt->fetch()) {
                $error = 'El usuario o email ya está registrado';
            } else {
                // Crear usuario
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, email, password, nombre_completo) VALUES (?, ?, ?, ?)");
                $stmt->execute([$usuario, $email, $password_hash, $nombre_completo]);

                $success = 'Cuenta creada exitosamente. Por favor, inicia sesión.';
                // Opcional: auto-login después de registro
                // $_SESSION['usuario_id'] = $pdo->lastInsertId();
                // $_SESSION['usuario_nombre'] = $nombre_completo;
                // header('Location: dashboard.php');
            }
        } catch (Exception $e) {
            $error = 'Error al crear la cuenta: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Sistema de Cotizaciones</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../assets/css/registro.css">
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h1><i class="material-icons">person_add</i> Crear Cuenta</h1>
                <p>Únete a nuestro sistema de cotizaciones</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="material-icons">error</i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="material-icons">check_circle</i> <?php echo htmlspecialchars($success); ?>
                    <a href="login.php" class="btn-link">Iniciar Sesión</a>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nombre_completo"><i class="material-icons">person</i> Nombre Completo</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" value="<?php echo htmlspecialchars($nombre_completo ?? ''); ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label for="usuario"><i class="material-icons">badge</i> Usuario</label>
                    <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($usuario ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email"><i class="material-icons">email</i> Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password"><i class="material-icons">lock</i> Contraseña</label>
                    <input type="password" id="password" name="password" required minlength="6">
                    <small>Mínimo 6 caracteres</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password"><i class="material-icons">lock_outline</i> Confirmar Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="material-icons">person_add</i> Crear Cuenta
                </button>
            </form>

            <div class="register-footer">
                <p>¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a></p>
                <p style="margin-top: 0.5rem;"><a href="../index.php">← Volver al inicio</a></p>
            </div>
        </div>
    </div>
</body>
</html>

