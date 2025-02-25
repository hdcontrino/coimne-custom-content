=== Coimne Custom Content ===
Contributors: hdcontrino
Author: Daniel Contrino
Author URI: https://coheda.com
Tags: login, custom content, API, widgets, shortcodes
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.1
License: Propietaria
License URI: https://coheda.com/license

== Descripción ==
Coimne Custom Content es un plugin que permite mostrar contenido personalizado para ciertos usuarios mediante shortcodes y widgets. 
No utiliza las sesiones de WordPress, sino una gestión propia basada en una API externa para validar y mantener sesiones activas.

Características:
- Formulario de login como shortcode y widget.
- Comunicación con una API externa para validar usuarios.
- Gestión de sesiones independiente de WordPress.
- Contenido dinámico basado en el estado de sesión del usuario.

== Instalación ==
1. Descarga el plugin y súbelo a la carpeta `/wp-content/plugins/`.
2. Activa el plugin desde el menú "Plugins" en WordPress.
3. Usa el shortcode `[coimne_login]` para agregar el formulario de login donde lo necesites.
4. Configura los widgets desde la sección de "Widgets" en WordPress.

== Uso ==
- `[coimne_login]`: Muestra el formulario de login.
- `[coimne_content]`: Muestra contenido especial basado en el estado de sesión del usuario.

== Preguntas Frecuentes ==
= ¿El plugin gestiona usuarios de WordPress? =
No, este plugin usa su propio sistema de sesión basado en una API externa.

= ¿Puedo personalizar el diseño del formulario? =
Sí, el formulario de login se adapta al tema actual, pero también puedes modificar los estilos en la carpeta `/assets/css/`.

= ¿Cómo se almacenan las sesiones de usuario? =
Las sesiones se manejan mediante cookies y validación con la API en cada carga de página.

== Changelog ==
= 1.0.1 =
- Implementado un sistema de actualizaciones automáticas desde un repositorio externo.
- Creado un helper para la gestión de rutas de assets y evitar problemas de caché con `filemtime()`.
- Agregado un saludo dinámico debajo del avatar en `menu.php`, con soporte para traducciones futuras.
- Refactorizada la carga de estilos y scripts para que se encolen dinámicamente solo cuando son necesarios.
- Ahora el menú en `dashboard-menu.php` carga dinámicamente el contenido sin recargar la página.
- Creada la estructura de templates `dashboard-<nombre>.php` en lugar de `content-<nombre>.php` para mayor coherencia.
- Agregada validación de sesión para detectar tokens inválidos y redirigir al usuario si la sesión expira.
- Incorporado reCAPTCHA v2 en el formulario de login con validación en frontend y backend.
- Añadida una sección de configuración con pestañas en el administrador de WordPress, similar a WooCommerce.
- Creado un switch en la configuración para habilitar el modo de desarrollo, permitiendo manejar `sslverify`.
- Agregado un control de redirección post-login configurable por el administrador.
- Implementado un sistema de menús dinámicos (`students_dashboard` y `collegiate_dashboard`) según el tipo de usuario.
- Creado un shortcode `[coimne_dashboard]` que muestra el menú y el contenido dinámico.
- Ahora los shortcodes y su funcionalidad están organizados en clases separadas para mayor escalabilidad.
- Se centralizó la estructura de configuraciones para facilitar futuras expansiones del plugin.
- Optimizada la estructura de `class-ajax.php` para gestionar mejor las solicitudes dinámicas.
- Mejorada la compatibilidad con maquetadores como Elementor asegurando que el diseño sea más flexible.

= 1.0.0 =
- Versión inicial del plugin.

== Licencia ==
Este plugin es software propietario. Para más información, visita [https://coheda.com/license](https://coheda.com/license).