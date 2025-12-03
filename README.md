# Sistema de Cotizaciones

Sistema web completo para crear, editar, gestionar y compartir cotizaciones personalizadas. Desarrollado con PHP, MySQL y tecnologías web modernas.

## 👨‍💻 Desarrollador

**Jhan Martinez** - Desarrollador Web Freelance

- 🌐 **Sitio Web:** [jhanstudios.com](https://jhanstudios.com/jhanstudios/)
- 💻 **GitHub:** [@Jhan321](https://github.com/Jhan321)
- 📧 **Email:** contacto@jhanstudios.com
- 📱 **Teléfono:** +57 314 717 7797
- 📍 **Ubicación:** Bello, Antioquia, Colombia

## ✨ Características

- ✅ Sistema de registro y autenticación seguro
- ✅ **Recordar sesión** (autologin con cookies seguras)
- ✅ Dashboard con gestión completa de cotizaciones
- ✅ Editor de cotizaciones con personalización de colores
- ✅ **Duplicar cotizaciones** con un solo clic
- ✅ Subida y gestión de logos personalizados
- ✅ URLs compartibles únicas para cada cotización
- ✅ Vista previa en tiempo real
- ✅ Descarga de cotizaciones en PDF
- ✅ Sistema de ordenamiento avanzado
- ✅ Gestión de perfil de usuario
- ✅ Notificaciones y alertas interactivas
- ✅ Diseño responsive y moderno
- ✅ Base de datos MySQL optimizada

## 🚀 Estructura del Proyecto

```
cotizacion/
├── api/                          # APIs backend
│   ├── guardar_cotizacion.php
│   ├── obtener_cotizacion.php
│   ├── eliminar_cotizacion.php
│   ├── duplicar_cotizacion.php
│   ├── cambiar_password.php
│   ├── eliminar_cuenta.php
│   └── upload_logo.php
├── assets/                       # Recursos estáticos
│   ├── css/
│   │   ├── landing.css
│   │   ├── login.css
│   │   ├── registro.css
│   │   ├── dashboard.css
│   │   ├── editor.css
│   │   ├── profile.css
│   │   └── notifications.css
│   └── js/
│       ├── editor.js
│       ├── preview.js
│       └── notifications.js
├── includes/                      # Archivos de configuración
│   └── config.php
├── pages/                        # Páginas principales
│   ├── login.php
│   ├── registro.php
│   ├── dashboard.php
│   ├── crear_cotizacion.php
│   ├── editar_cotizacion.php
│   ├── ver_cotizacion.php
│   └── perfil.php
├── sql/                          # Scripts de base de datos
│   └── u891516864_sistema_cotiza.sql
├── uploads/                      # Archivos subidos
│   └── logos/
├── index.php                     # Página de inicio/landing
├── logout.php                    # Cerrar sesión
└── install.php                   # Instalador (opcional)
```

## 📦 Instalación

### Requisitos Previos

- PHP 7.4 o superior
- MySQL 5.7 o superior (o MariaDB 10.3+)
- Servidor web (Apache/Nginx)
- Extensiones PHP: PDO, PDO_MySQL, GD (para imágenes)

### Pasos de Instalación

1. **Clonar o descargar el repositorio:**

   ```bash
   git clone https://github.com/Jhan321/sistema-cotizaciones.git
   cd sistema-cotizaciones
   ```

2. **Configurar Base de Datos:**

   - Crear una base de datos MySQL
   - Importar el archivo `sql/u891516864_sistema_cotiza.sql` en phpMyAdmin o MySQL
   - O ejecutar los scripts SQL manualmente

3. **Configurar la Aplicación:**

   - Editar `includes/config.php` y ajustar:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'tu_usuario');
     define('DB_PASS', 'tu_contraseña');
     define('DB_NAME', 'sistema_cotizaciones');
     ```
   - Ajustar `APP_URL` con la URL de tu instalación:
     ```php
     define('APP_URL', 'http://localhost/cotizacion');
     ```

4. **Permisos de Carpetas:**

   - Asegurar que la carpeta `uploads/logos/` tenga permisos de escritura (chmod 755 o 777)

5. **Acceder al Sistema:**
   - Navegar a `http://localhost/cotizacion`
   - Crear una cuenta nueva desde la página de registro
   - O usar las credenciales de administrador si existen

## 🎯 Uso

### Crear una Cotización

1. Inicia sesión en el sistema
2. Haz clic en "Nueva Cotización" desde el dashboard
3. Completa los campos:
   - **Información general:** Título, cliente, desarrollador, validez
   - **Personalización:** Colores primario, secundario y acento
   - **Logo:** Sube un logo personalizado (opcional)
   - **Contenido HTML:**
     - Requisitos de desarrollo
     - Estilo visual y gráficos
     - Tabla de precios
     - Términos y condiciones
     - Información de contacto
4. Usa la vista previa en tiempo real para verificar el resultado
5. Guarda la cotización

### Duplicar una Cotización

1. Desde el dashboard, haz clic en el menú de 3 puntos (⋮) de cualquier cotización
2. Selecciona "Duplicar"
3. Se creará una copia exacta con el título modificado agregando "(- copia)"
4. La nueva cotización tendrá su propio ID y código único

### Compartir una Cotización

1. Desde el dashboard, haz clic en "Ver" en la cotización deseada
2. Copia la URL única de la cotización
3. Comparte la URL con tu cliente
4. La URL es pública y no requiere autenticación

### Gestión de Perfil

- Cambiar contraseña desde el perfil
- Subir y actualizar logo personalizado
- Ver información de la cuenta
- Eliminar cuenta (con confirmación de contraseña)

### Recordar Sesión

- Al iniciar sesión, marca la casilla "Recordar sesión"
- Tu sesión se mantendrá activa por 30 días
- Se restaurará automáticamente al visitar el sitio

## 🎨 Personalización

### Colores

Cada cotización puede tener colores personalizados:

- **Color Primario:** Color principal de la marca (#002855 por defecto)
- **Color Secundario:** Color de acentos y elementos destacados (#97d700 por defecto)
- **Color Acento:** Color para llamadas a la acción y precios (#fe5000 por defecto)

### Contenido HTML

El sistema permite usar HTML básico en los campos de contenido:

**Ejemplo para requisitos:**

```html
<ul class="requirements-list">
  <li>
    <strong>1. Desarrollo Frontend</strong>
    <span>Interfaz de usuario responsive con HTML5, CSS3 y JavaScript</span>
  </li>
  <li>
    <strong>2. Desarrollo Backend</strong>
    <span>API RESTful con PHP 7.4+ y MySQL</span>
  </li>
</ul>
```

**Ejemplo para precios:**

```html
<table>
  <tr>
    <td>Concepto</td>
    <td>Descripción</td>
    <td>$1.000.000</td>
  </tr>
  <tr class="total-row">
    <td colspan="2"><strong>TOTAL</strong></td>
    <td><strong>$1.000.000</strong></td>
  </tr>
</table>
```

## 🔒 Seguridad

- ✅ Contraseñas hasheadas con bcrypt
- ✅ Prevención de SQL injection con prepared statements
- ✅ Validación de sesiones y tokens seguros
- ✅ Sistema de "recordar sesión" con tokens encriptados
- ✅ URLs únicas para cada cotización
- ✅ Validación de permisos de usuario
- ✅ Protección CSRF en formularios
- ✅ Sanitización de inputs

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 7.4+
- **Base de Datos:** MySQL/MariaDB
- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Iconos:** Google Material Icons
- **Estilos:** CSS personalizado con diseño moderno
- **APIs:** RESTful con JSON

## 📱 Diseño Responsive

El sistema está completamente optimizado para:

- 📱 Dispositivos móviles
- 📱 Tablets
- 💻 Desktop
- 🖥️ Pantallas grandes

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## 📞 Soporte

Para problemas, sugerencias o consultas:

- 📧 **Email:** contacto@jhanstudios.com
- 🌐 **Web:** [jhanstudios.com](https://jhanstudios.com/jhanstudios/)
- 💻 **GitHub:** [@Jhan321](https://github.com/Jhan321)
- 📱 **Teléfono:** +57 314 717 7797

## 🙏 Agradecimientos

Gracias por usar el Sistema de Cotizaciones. Si te resulta útil, considera darle una ⭐ en GitHub.

---

**Desarrollado con ❤️ por [Jhan Martinez](https://jhanstudios.com/jhanstudios/)**

_Desarrollando el futuro, una línea de código a la vez._ 🚀
