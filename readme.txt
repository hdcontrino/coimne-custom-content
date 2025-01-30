=== Coimne Custom Content ===
Contributors: hdcontrino
Author: Daniel Contrino
Author URI: https://coheda.com
Tags: login, custom content, API, widgets, shortcodes
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
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
= 1.0.0 =
- Versión inicial del plugin.

== Licencia ==
Este plugin es software propietario. Para más información, visita [https://coheda.com/license](https://coheda.com/license).