<?php
require_once 'includes/config.php';

// Si ya está logueado, redirigir al dashboard
if (isLoggedIn()) {
    header('Location: pages/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema profesional de cotizaciones online. Crea, gestiona y comparte cotizaciones personalizadas con tu marca. Ideal para freelancers y empresas.">
    <meta name="keywords" content="cotizaciones, presupuestos, facturación, gestión de proyectos, freelancers, empresas">
    <meta name="author" content="Sistema de Cotizaciones">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Sistema de Cotizaciones - Crea y Gestiona Cotizaciones Profesionales">
    <meta property="og:description" content="Plataforma completa para crear, editar y compartir cotizaciones personalizadas con tu marca.">
    <meta property="og:type" content="website">
    <title>Sistema de Cotizaciones - Crea y Gestiona Cotizaciones Profesionales</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="assets/css/landing.css">
</head>

<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="material-icons">description</i>
                    <span>Sistema de Cotizaciones</span>
                </div>
                <nav class="main-nav">
                    <a href="#caracteristicas">Características</a>
                    <a href="#beneficios">Beneficios</a>
                    <a href="pages/registro.php" class="btn-nav">Crear Cuenta</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Crea Cotizaciones Profesionales en Minutos</h1>
                    <p class="hero-subtitle">Sistema completo para gestionar tus cotizaciones. Personaliza colores, agrega tu logo y comparte con tus clientes de forma profesional.</p>
                    <div class="hero-features">
                        <div class="feature-item">
                            <i class="material-icons">check_circle</i>
                            <span>100% Personalizable</span>
                        </div>
                        <div class="feature-item">
                            <i class="material-icons">check_circle</i>
                            <span>Comparte por URL</span>
                        </div>
                        <div class="feature-item">
                            <i class="material-icons">check_circle</i>
                            <span>Descarga PDF</span>
                        </div>
                    </div>
                    <div class="hero-cta">
                        <a href="pages/registro.php" class="btn btn-primary">
                            <i class="material-icons">person_add</i> Crear Cuenta Gratis
                        </a>
                        <a href="pages/login.php" class="btn btn-secondary">
                            <i class="material-icons">login</i> Iniciar Sesión
                        </a>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="hero-card">
                        <i class="material-icons">description</i>
                        <h3>Cotización Ejemplo</h3>
                        <div class="hero-card-content">
                            <div class="hero-card-item">
                                <i class="material-icons">check</i>
                                <span>Personalizable</span>
                            </div>
                            <div class="hero-card-item">
                                <i class="material-icons">check</i>
                                <span>Profesional</span>
                            </div>
                            <div class="hero-card-item">
                                <i class="material-icons">check</i>
                                <span>Fácil de usar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Características -->
    <section id="caracteristicas" class="features-section">
        <div class="container">
            <h2 class="section-title">Características Principales</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="material-icons">palette</i>
                    </div>
                    <h3>Personalización Total</h3>
                    <p>Personaliza colores primarios, secundarios y de acento. Agrega tu logo de empresa para mantener tu identidad visual.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="material-icons">share</i>
                    </div>
                    <h3>Comparte Fácilmente</h3>
                    <p>Genera URLs únicas para cada cotización. Comparte con tus clientes mediante un simple enlace.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="material-icons">picture_as_pdf</i>
                    </div>
                    <h3>Descarga en PDF</h3>
                    <p>Descarga tus cotizaciones en formato PDF con un solo clic. Perfecto para archivar o enviar por email.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="material-icons">edit</i>
                    </div>
                    <h3>Editor Intuitivo</h3>
                    <p>Interfaz fácil de usar sin necesidad de conocimientos técnicos. Crea cotizaciones profesionales en minutos.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="material-icons">dashboard</i>
                    </div>
                    <h3>Gestión Centralizada</h3>
                    <p>Organiza todas tus cotizaciones desde un dashboard central. Ordena y busca fácilmente.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="material-icons">preview</i>
                    </div>
                    <h3>Vista Previa en Tiempo Real</h3>
                    <p>Vista previa de tu cotización antes de guardar. Asegúrate de que todo esté perfecto.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Beneficios -->
    <section id="beneficios" class="benefits-section">
        <div class="container">
            <h2 class="section-title">¿Por qué elegir nuestro sistema?</h2>
            <div class="benefits-list">
                <div class="benefit-item">
                    <i class="material-icons">speed</i>
                    <div>
                        <h3>Rápido y Eficiente</h3>
                        <p>Crea cotizaciones profesionales en minutos, no en horas.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <i class="material-icons">security</i>
                    <div>
                        <h3>Seguro y Privado</h3>
                        <p>Tus datos están protegidos. Solo tú tienes acceso a tus cotizaciones.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <i class="material-icons">devices</i>
                    <div>
                        <h3>Acceso desde Cualquier Lugar</h3>
                        <p>Funciona en cualquier dispositivo. Accede desde tu computadora, tablet o móvil.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <i class="material-icons">attach_money</i>
                    <div>
                        <h3>Gratis y Sin Límites</h3>
                        <p>Crea tantas cotizaciones como necesites sin restricciones.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>¿Listo para comenzar?</h2>
                <p>Crea tu cuenta gratuita y comienza a generar cotizaciones profesionales hoy mismo.</p>
                <a href="pages/registro.php" class="btn btn-primary btn-large">
                    <i class="material-icons">person_add</i> Crear Cuenta Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4><i class="material-icons">description</i> Sistema de Cotizaciones</h4>
                    <p>Plataforma completa para crear y gestionar cotizaciones profesionales.</p>
                </div>
                <div class="footer-section">
                    <h4>Enlaces</h4>
                    <ul>
                        <li><a href="#caracteristicas">Características</a></li>
                        <li><a href="#beneficios">Beneficios</a></li>
                        <li><a href="pages/registro.php">Crear Cuenta</a></li>
                        <li><a href="pages/login.php">Iniciar Sesión</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Funcionalidades</h4>
                    <ul>
                        <li>Personalización de colores</li>
                        <li>Logo de empresa</li>
                        <li>Descarga PDF</li>
                        <li>Compartir por URL</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Sistema de Cotizaciones. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll para los enlaces del menú
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>
