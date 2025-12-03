<?php
require_once 'includes/config.php';

// Eliminar token de "recordar sesión" si existe
if (isset($_COOKIE['remember_token'])) {
    eliminarRememberToken($_COOKIE['remember_token']);
}

// Destruir sesión
session_destroy();

header('Location: index.php');
exit;
