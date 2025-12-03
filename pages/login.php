<?php
require_once '../includes/config.php';

// Si ya está logueado, redirigir al dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($usuario) && !empty($password)) {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT id, usuario, password, nombre_completo FROM usuarios WHERE usuario = ? OR email = ?");
        $stmt->execute([$usuario, $usuario]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nombre'] = $user['nombre_completo'];
            $_SESSION['usuario'] = $user['usuario'];

            // Si el usuario marcó "Recordar sesión", guardar token
            if (isset($_POST['remember']) && $_POST['remember'] === '1') {
                guardarRememberToken($user['id']);
            }

            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos';
        }
    } else {
        $error = 'Por favor complete todos los campos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Cotizaciones</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../assets/css/registro.css">
</head>

<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h1><i class="material-icons">login</i> Iniciar Sesión</h1>
                <p>Accede a tu cuenta para continuar</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="material-icons">error</i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="usuario"><i class="material-icons">person</i> Usuario o Email</label>
                    <input type="text" id="usuario" name="usuario" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password"><i class="material-icons">lock</i> Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer; font-weight: normal;">
                        <input type="checkbox" name="remember" value="1" style="margin-right: 8px; width: auto;">
                        <i class="material-icons" style="font-size: 18px; margin-right: 4px;">remember_me</i>
                        Recordar sesión
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="material-icons">login</i> Iniciar Sesión
                </button>
            </form>

            <div class="register-footer">
                <p>¿No tienes cuenta? <a href="registro.php">Regístrate gratis</a></p>
                <p style="margin-top: 0.5rem;"><a href="../index.php">← Volver al inicio</a></p>
            </div>
        </div>
    </div>
</body>

</html>
