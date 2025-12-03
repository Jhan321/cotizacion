-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 03-12-2025 a las 15:34:48
-- Versión del servidor: 11.8.3-MariaDB-log
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u891516864_sistema_cotiza`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizaciones`
--

CREATE TABLE `cotizaciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `cliente` varchar(100) NOT NULL,
  `desarrollador` varchar(100) NOT NULL,
  `validez` varchar(50) DEFAULT '30 días',
  `color_primario` varchar(7) DEFAULT '#002855',
  `color_secundario` varchar(7) DEFAULT '#97d700',
  `color_acento` varchar(7) DEFAULT '#fe5000',
  `logo_url` varchar(500) DEFAULT NULL,
  `contenido_requisitos` text DEFAULT NULL,
  `contenido_estilo` text DEFAULT NULL,
  `contenido_precios` text DEFAULT NULL,
  `contenido_terminos` text DEFAULT NULL,
  `info_contacto` text DEFAULT NULL,
  `codigo_unico` varchar(50) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cotizaciones`
--

INSERT INTO `cotizaciones` (`id`, `usuario_id`, `titulo`, `cliente`, `desarrollador`, `validez`, `color_primario`, `color_secundario`, `color_acento`, `logo_url`, `contenido_requisitos`, `contenido_estilo`, `contenido_precios`, `contenido_terminos`, `info_contacto`, `codigo_unico`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 'Game Quiz', 'WEST', 'Jhan Studios', '30 días', '#002855', '#97d700', '#fe5000', 'uploads/logos/logo_6924ef2d594059.89487066.png', '<ul class=\"requirements-list\"><li><strong>1. Desarrollo Frontend</strong><span>Interfaz de usuario responsive con HTML5, CSS3 y JavaScript</span></li><li><strong>2. Desarrollo Backend</strong><span>API RESTful con PHP 7.4+. Sistema de gestión de resultados, leaderboard y almacenamiento de datos. Integración con base de datos MySQL para persistencia de información.</span></li><li><strong>3. Sistema de Quiz Interactivo</strong><span>Lógica de juego con sistema de puntuación, cronómetro, validación de respuestas y retroalimentación inmediata. Manejo de estados del juego y flujo de navegación.</span></li><li><strong>4. Sistema de Leaderboard</strong><span>Clasificación de jugadores con filtros por tema y fecha. Visualización de estadísticas y rankings. Integración con base de datos para consultas optimizadas.</span></li><li><strong>5. Base de Datos</strong><span>Diseño e implementación de esquema de base de datos. Tablas optimizadas con índices para consultas rápidas. Scripts de migración y respaldo.</span></li><li><strong>6. Integración y Testing</strong><span>Pruebas de funcionalidad, compatibilidad cross-browser y responsive. Optimización de rendimiento y carga. Documentación técnica del proyecto.</span></li></ul>', '<ul><li>Diseño de interfaz corporativa con identidad visual</li><li>Paleta de colores corporativos (Azul #002855, Verde #97d700, Naranja #fe5000)</li><li>Iconografía y elementos gráficos personalizados</li><li>Diseño de cards y componentes UI consistentes</li><li>Animaciones y transiciones visuales</li><li>Optimización de imágenes y assets gráficos</li><li>Diseño responsive con breakpoints optimizados</li><li>Elementos visuales para feedback de usuario (éxito/error)</li><li>Diseño de modales y overlays</li><li>Branding y aplicación de logo corporativo</li></ul>', '<tr><td>Programación y Desarrollo</td><td>Desarrollo completo de la aplicación web, incluyendo frontend, backend, lógica de negocio, integración con base de datos y testing.</td><td>$1.000.000</td></tr><tr><td>Diseño Visual y Gráficos</td><td>Diseño de interfaz, elementos gráficos, iconografía, animaciones y aplicación de identidad visual corporativa según brief 10.1.</td><td>$1.000.000</td></tr><tr class=\"total-row\"><td colspan=\"2\"><strong>TOTAL</strong></td><td><strong>$2.000.000</strong></td></tr>', '<ul class=\"requirements-list\"><li><strong>Forma de Pago</strong><span>50% al inicio, 50% al finalizar</span></li><li><strong>Tiempo de Desarrollo</strong><span>Estimado: 1-2 semanas desde la aprobación y pago inicial.</span></li><li><strong>Entregables</strong><span>Código fuente completo, documentación técnica, base de datos configurada, y guía de instalación.</span></li><li><strong>Soporte Post-Entrega</strong><span>3 días de soporte técnico incluido para correcciones y ajustes menores.</span></li></ul>', '<p><strong>Jhan Martinez</strong> - Desarrollador Web Freelance</p><p><a href=\"https://jhanstudios.com/\" target=\"_blank\">Sitio web</a></p><p>webwebcontacto@jhanstudios.com | +57 314 717 7797</p><p>Bello, Antioquia, Colombia</p>', '62d935160c2a6c722cd6f97d44df6861', '2025-11-24 23:22:21', '2025-11-24 23:50:16'),
(2, 2, 'Landing page WordPress básica', 'Carlos Espinosa', 'Latte Company Group', '30 días', '#002855', '#97d700', '#fe5000', '', '<ul class=\"requirements-list\"><li><strong>1. Instalación y Configuración WordPress</strong><span>Instalación limpia de WordPress en servidor del cliente. Configuración inicial de permisos, seguridad básica y optimización de rendimiento. Configuración de base de datos y credenciales de acceso.</span></li><li><strong>2. Desarrollo de Landing Page</strong><span>Creación de página de aterrizaje responsive con diseño moderno y atractivo. Implementación de secciones: hero, servicios/productos, características, testimonios, call-to-action y formulario de contacto.</span></li><li><strong>3. Diseño Responsive y Mobile-First</strong><span>Diseño adaptativo que se visualiza correctamente en dispositivos móviles, tablets y desktop. Optimización de imágenes y carga rápida en todos los dispositivos.</span></li><li><strong>4. Integración de Formulario de Contacto</strong><span>Implementación de formulario de contacto funcional con validación. Integración con sistema de correo para recibir mensajes directamente en el email del cliente.</span></li><li><strong>5. Optimización SEO Básica</strong><span>Configuración de meta tags, títulos y descripciones. Optimización de imágenes con alt text. Estructura semántica HTML5 para mejor indexación.</span></li><li><strong>6. Configuración y Entrenamiento</strong><span>Capacitación básica para gestión de contenidos en WordPress. Configuración de usuario administrador y permisos. Documentación de uso y mantenimiento básico.</span></li></ul>', '<ul><li>Diseño de landing page limpio y profesional</li><li>Layout responsive con diseño mobile-first</li><li>Paleta de colores personalizada según identidad del cliente</li><li>Tipografía legible y moderna</li><li>Optimización de imágenes y recursos multimedia</li><li>Animaciones sutiles y transiciones suaves</li><li>Formulario de contacto con diseño atractivo</li><li>Botones de call-to-action destacados</li><li>Secciones bien estructuradas y fáciles de leer</li><li>Integración de redes sociales (opcional)</li></ul>', '<tr><td>Landing page WordPress básica</td><td>Desarrollo completo de landing page en WordPress, incluyendo instalación, configuración, diseño responsive, formulario de contacto, optimización SEO básica y capacitación.</td><td>$4.200.000</td></tr><tr class=\"total-row\"><td colspan=\"2\"><strong>TOTAL</strong></td><td><strong>$4.200.000</strong></td></tr>', '<ul class=\"requirements-list\"><li><strong>Forma de Pago</strong><span>50% al inicio del proyecto, 50% al finalizar y entregar el proyecto completo.</span></li><li><strong>Tiempo de Desarrollo</strong><span>Estimado: 5-7 días hábiles desde la aprobación y pago inicial.</span></li><li><strong>Entregables</strong><span>Landing page funcional en WordPress, acceso administrativo configurado, documentación de uso básico, y capacitación de 1 hora.</span></li><li><strong>Requisitos del Cliente</strong><span>El cliente debe proporcionar hosting con WordPress disponible, acceso FTP/cPanel, contenido (textos e imágenes), y especificaciones de diseño o referencias visuales.</span></li><li><strong>Soporte Post-Entrega</strong><span>3 días de soporte técnico incluido para correcciones y ajustes menores.</span></li><li><strong>Propiedad Intelectual</strong><span>El diseño y contenido desarrollado serán propiedad del cliente una vez completado el pago total.</span></li></ul>', '<p><strong>Andrés Felipe Charry</strong> - CTO</p><p><a href=\"https://www.latte.com.co\" target=\"_blank\">Sitio web</a> | <a href=\"https://www.instagram.com/lattecolombia/\" target=\"_blank\">instagram</a></p><p>experiencias@latte.com.co | +57 3004206001</p><p>Cra 42 Nro 3 sur 81 Edificio Milla de Oro - Medellín, Colombia</p>', 'd09325fc6166911e51e32627e39428cb', '2025-12-03 01:57:52', '2025-12-03 01:10:45'),
(3, 2, 'GAME QUIZ - ENTRENADOR FUERZA COMERCIAL', 'WEST QUÍMICA', 'Latte Company Group', '30 días', '#002855', '#97d700', '#fe5000', 'uploads/logos/logo_6924ef2d594059.89487066.png', '<ul class=\"requirements-list\"><li><strong>1. Desarrollo Frontend</strong><span>Interfaz de usuario responsive con HTML5, CSS3 y JavaScript</span></li><li><strong>2. Desarrollo Backend</strong><span>API RESTful con PHP 7.4+. Sistema de gestión de resultados, leaderboard y almacenamiento de datos. Integración con base de datos MySQL para persistencia de información.</span></li><li><strong>3. Sistema de Quiz Interactivo</strong><span>Lógica de juego con sistema de puntuación, cronómetro, validación de respuestas y retroalimentación inmediata. Manejo de estados del juego y flujo de navegación.</span></li><li><strong>4. Sistema de Leaderboard</strong><span>Clasificación de jugadores con filtros por tema y fecha. Visualización de estadísticas y rankings. Integración con base de datos para consultas optimizadas.</span></li><li><strong>5. Base de Datos</strong><span>Diseño e implementación de esquema de base de datos. Tablas optimizadas con índices para consultas rápidas. Scripts de migración y respaldo.</span></li><li><strong>6. Integración y Testing</strong><span>Pruebas de funcionalidad, compatibilidad cross-browser y responsive. Optimización de rendimiento y carga. Documentación técnica del proyecto.</span></li></ul>', '<ul><li>Diseño de interfaz corporativa con identidad visual</li><li>Paleta de colores corporativos (Azul #002855, Verde #97d700, Naranja #fe5000)</li><li>Iconografía y elementos gráficos personalizados</li><li>Diseño de cards y componentes UI consistentes</li><li>Animaciones y transiciones visuales</li><li>Optimización de imágenes y assets gráficos</li><li>Diseño responsive con breakpoints optimizados</li><li>Elementos visuales para feedback de usuario (éxito/error)</li><li>Diseño de modales y overlays</li><li>Branding y aplicación de logotipo corporativo</li></ul>', '<tr><td>Programación y Desarrollo</td><td>Desarrollo completo de la aplicación web, incluyendo frontend, backend, lógica de negocio, integración con base de datos y testing.</td><td>$8.600.000</td></tr><tr><td>Diseño Visual y Gráficos</td><td>Diseño de interfaz, elementos gráficos, iconografía, animaciones y aplicación de identidad visual corporativa según brief 10.1.</td><td>$3.900.000</td></tr><tr class=\"total-row\"><td colspan=\"2\"><strong>TOTAL</strong></td><td><strong>$12.500.000</strong></td></tr>', '<ul class=\"requirements-list\"><li><strong>Forma de Pago</strong><span>Factura con término de vencimiento 30 días calendario\nValores no incluyen IVA</span></li><li><strong>Tiempo de Desarrollo</strong><span>Estimado: 1-2 semanas desde la aprobación de interfaz y dinámica</span></li><li><strong>Entregables</strong><span>Código fuente completo, documentación técnica, base de datos configurada, y guía de instalación.</span></li><li><strong>Soporte Post-Entrega</strong><span>3 días de soporte técnico incluido para correcciones y ajustes menores.</span></li><li><strong>Alcance de la experiencia</strong><span>Integración de sistema de juego: Quién quiere ser Millonario\nImplementación de campos aleatorios de respuesta y preguntas hasta 100 variables\nAlmacenamiento local de bases de datos (Web server cliente) MySQL\nProgramación de formas de juego: Incentivos de tiempo\nAcompañamiento en definición de experiencia de juego previo a programación\nPruebas de usabilidad del juego en plataforma Elearning</span></li><li><strong>Condiciones</strong><span>No incluye compra de dominios, servidores o mejoras de planes actuales licenciados por el cliente\nNo incluye integraciones con APIs externas de otras plataformas\nAlmacenamiento en servidor del cliente optimizado para Web\nValidación en ambiente de pruebas previo a lanzamiento</span></li></ul>', '<p><strong>Andrés Felipe Charry</strong> - CTO</p><p><a href=\"https://www.latte.com.co\" target=\"_blank\">Sitio web</a> | <a href=\"https://www.instagram.com/lattecolombia/\" target=\"_blank\">instagram</a></p><p>experiencias@latte.com.co | +57 3004206001</p><p>Cra 42 Nro 3 sur 81 Edificio Milla de Oro - Medellín, Colombia</p>', '62d935160c2a5c722cd6f97d34df6861', '2025-11-24 23:22:21', '2025-12-03 01:10:40'),
(4, 2, 'Sitio web informativo con páginas adicionales', 'Carlos Espinosa', 'Latte Company Group', '30 días', '#002855', '#97d700', '#fe5000', NULL, '<ul class=\"requirements-list\"><li><strong>1. Desarrollo Frontend (HTML5, CSS3, JavaScript)</strong><span>Desarrollo de interfaz de usuario completa con HTML5 semántico, estilos CSS3 modernos y JavaScript (ES6+) para interactividad. Implementación de diseño responsive, animaciones, transiciones y efectos visuales. Optimización para dispositivos móviles, tablets y desktop con diseño mobile-first.</span></li><li><strong>2. Desarrollo Backend (PHP)</strong><span>Desarrollo de lógica de servidor con PHP 7.4+. Implementación de sistema de gestión de contenidos dinámicos, formularios de contacto, galerías, blog, y secciones interactivas. Integración con base de datos MySQL para almacenamiento y gestión de información.</span></li><li><strong>3. Múltiples Páginas Informativas</strong><span>Desarrollo de estructura de navegación y múltiples páginas informativas (Inicio, Nosotros, Servicios/Productos, Blog/Noticias, Galería, Contacto, etc.). Sistema de menú dinámico y navegación intuitiva entre páginas.</span></li><li><strong>4. Sistema de Gestión de Contenidos</strong><span>Panel administrativo básico para gestión de contenidos, actualización de textos, imágenes y secciones. Sistema de autenticación y permisos de usuario. Interfaz intuitiva para administración sin conocimientos técnicos avanzados.</span></li><li><strong>5. Formularios y Funcionalidades Interactivas</strong><span>Implementación de formularios de contacto con validación frontend y backend. Integración de mapas, galerías de imágenes, buscador interno, y otras funcionalidades interactivas según necesidades del cliente.</span></li><li><strong>6. Base de Datos y Optimización</strong><span>Diseño e implementación de esquema de base de datos optimizado. Tablas estructuradas con índices para consultas rápidas. Optimización de rendimiento, carga rápida de páginas y SEO técnico básico.</span></li><li><strong>7. Integración y Testing</strong><span>Pruebas exhaustivas de funcionalidad, compatibilidad cross-browser (Chrome, Firefox, Safari, Edge) y responsive. Optimización de imágenes, minificación de código, y validación de estándares web. Documentación técnica completa del proyecto.</span></li></ul>', '<ul><li>Diseño web moderno y profesional con identidad visual corporativa</li><li>Layout responsive con diseño mobile-first para todos los dispositivos</li><li>Paleta de colores personalizada según identidad del cliente</li><li>Tipografía legible y jerarquía visual clara</li><li>Iconografía y elementos gráficos personalizados</li><li>Animaciones y transiciones suaves para mejor experiencia de usuario</li><li>Optimización de imágenes y recursos multimedia</li><li>Diseño de componentes reutilizables (headers, footers, cards, botones)</li><li>Estructura de navegación intuitiva y menús desplegables</li><li>Diseño de formularios atractivos con validación visual</li><li>Galerías de imágenes con efectos lightbox o carrusel</li><li>Diseño de páginas de error personalizadas</li><li>Branding consistente en todas las páginas</li></ul>', '<tr><td>Desarrollo Frontend (HTML5, CSS3, JavaScript)</td><td>Desarrollo completo de interfaz de usuario responsive, interactividad, animaciones y optimización para todos los dispositivos.</td><td>$1.800.000</td></tr><tr><td>Desarrollo Backend (PHP)</td><td>Desarrollo de lógica de servidor, sistema de gestión de contenidos, formularios funcionales y integración con base de datos.</td><td>$1.600.000</td></tr><tr><td>Diseño Visual y Gráficos</td><td>Diseño de interfaz completa, elementos gráficos personalizados, iconografía, animaciones y aplicación de identidad visual corporativa.</td><td>$1.200.000</td></tr><tr><td>Múltiples Páginas y Contenidos</td><td>Desarrollo y estructuración de múltiples páginas informativas, sistema de navegación, y gestión de contenidos dinámicos.</td><td>$800.000</td></tr><tr class=\"total-row\"><td colspan=\"2\"><strong>TOTAL</strong></td><td><strong>$5.400.000</strong></td></tr>', '<ul class=\"requirements-list\"><li><strong>Forma de Pago</strong><span>50% al inicio del proyecto, 50% al finalizar y entregar el proyecto completo.</span></li><li><strong>Tiempo de Desarrollo</strong><span>Estimado: 3-4 semanas desde la aprobación y pago inicial, dependiendo de la cantidad de páginas y funcionalidades requeridas.</span></li><li><strong>Entregables</strong><span>Código fuente completo (HTML, CSS, JavaScript, PHP), base de datos configurada, panel administrativo funcional, documentación técnica, y guía de uso. Sitio web funcional desplegado en servidor del cliente.</span></li><li><strong>Requisitos del Cliente</strong><span>El cliente debe proporcionar hosting con PHP 7.4+ y MySQL, acceso FTP/cPanel, contenido completo (textos, imágenes, logotipos), especificaciones de diseño o referencias visuales, y definir número de páginas y funcionalidades deseadas.</span></li><li><strong>Revisiones y Modificaciones</strong><span>Incluye 2 rondas de revisiones y modificaciones menores durante el desarrollo. Modificaciones mayores o cambios estructurales pueden tener costos adicionales.</span></li><li><strong>Soporte Post-Entrega</strong><span>7 días de soporte técnico incluido para correcciones y ajustes menores después de la entrega.</span></li><li><strong>Propiedad Intelectual</strong><span>El código fuente, diseño y contenido desarrollado serán propiedad del cliente una vez completado el pago total.</span></li></ul>', '<p><strong>Andrés Felipe Charry</strong> - CTO</p><p><a href=\"https://www.latte.com.co\" target=\"_blank\">Sitio web</a> | <a href=\"https://www.instagram.com/lattecolombia/\" target=\"_blank\">instagram</a></p><p>experiencias@latte.com.co | +57 3004206001</p><p>Cra 42 Nro 3 sur 81 Edificio Milla de Oro - Medellín, Colombia</p>', 'bf9309d75ce6d0b0ceb0b8d649c38862', '2025-12-03 02:00:00', '2025-12-03 01:10:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones_remember`
--

CREATE TABLE `sesiones_remember` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expira_en` timestamp NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sesiones_remember`
--

INSERT INTO `sesiones_remember` (`id`, `usuario_id`, `token`, `expira_en`, `creado_en`) VALUES
(1, 1, '24b4bd9bcb9e4daa8dfbd5e2e7633437d71d59b48d14fd38055215c3a84e3da2', '2026-01-02 15:33:18', '2025-12-03 15:33:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `email`, `password`, `nombre_completo`, `creado_en`) VALUES
(1, 'admin', 'admin@jhanstudios.com', '$2y$10$Heot7ktX5.raCFzFvHwi3e3xF.AqYcI7ozoel76q1YRwkYBk8nt4q', 'Administrador', '2025-11-24 23:09:15'),
(2, 'andres_charry', 'experiencias@latte.com.co', '$2y$10$UmX6pcMfw73SIJrSmvIe9eRRZtqVUbZ52SXmIEXc7sQjk6eeYqd32', 'Andres Charry', '2025-11-25 00:20:17');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_unico` (`codigo_unico`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_codigo_unico` (`codigo_unico`),
  ADD KEY `idx_creado_en` (`creado_en`);

--
-- Indices de la tabla `sesiones_remember`
--
ALTER TABLE `sesiones_remember`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_expira_en` (`expira_en`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_usuario` (`usuario`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sesiones_remember`
--
ALTER TABLE `sesiones_remember`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `sesiones_remember`
--
ALTER TABLE `sesiones_remember`
  ADD CONSTRAINT `sesiones_remember_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
