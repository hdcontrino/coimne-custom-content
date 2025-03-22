=== Coimne Custom Content ===
Contributors: hdcontrino
Author: Daniel Contrino
Author URI: https://coheda.com
Tags: login, custom content, API, widgets, shortcodes
Requires at least: 6.0
Tested up to: 6.7.2
Requires PHP: 7.4
Stable tag: 1.0.13
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
= 1.0.13 =
* Se reubicaron las plantillas del dashboard en subdirectorios (profile/, account/, projects/, courses/, menu/).
* Se eliminaron archivos de plantilla antiguos que fueron reemplazados por los reorganizados.
* Se actualizó la lógica de paginación y carga dinámica en coimne-dashboard.js, evitando duplicación.
* Se corrigió el manejo de errores y resultados vacíos en la carga de cursos.

= 1.0.12 =
- Nueva funcionalidad "Mis Cursos":
  - Formulario de búsqueda con filtros por nombre y fechas.
  - Carga dinámica desde la API usando AJAX.
  - Resultados renderizados con `<template>` HTML.
  - Paginación incluida y comportamiento expandible en mobile.
- Nuevo endpoint AJAX: `coimne_get_user_courses`.
- Eliminación del endpoint `coimne_get_locs` y su lógica asociada.
  - Se quitó el `<select>` de localidad en los formularios de perfil.
  - Ahora se usa un `<input>` (`LOC`) editable o de solo lectura.
- Reorganización de menús en `Coimne_Menu`:
  - Se reemplazaron las constantes `COIMNE_MENU_C_ITEMS`, `E_ITEMS`, y `O_ITEMS` por arrays estáticos públicos.
- Estilos actualizados:
  - Nuevas clases utilitarias `.coimne-justify-*`, `.coimne-page-btn`, etc.
  - Estilos para contenedor de cursos, resultados y paginación.

= 1.0.11 =
- Refactorización de respuestas de la API, reemplazando `desc` por `data`.
- Mejoras en la validación de respuestas y gestión de errores en la API.
- Correcciones en Select2 para evitar problemas con valores `null` en los selectores.
- Ajustes en la carga dinámica de plantillas del Dashboard para manejar nombres en minúsculas.
- Compatibilidad actualizada con WordPress 6.7.2.

= 1.0.10 =
* Integración de Select2 para búsqueda de empresas en el perfil.
* Refactorización de llamadas a la API y mejoras en gestión de errores.
* Se agregó un formulario de inscripción con validaciones.
* Se mejoró la gestión de cursos en el Dashboard.
* Se optimizó la carga de scripts y estilos.
* Ajustes en los shortcodes y correcciones en la estructura del código.

= 1.0.9 =
* Se agregó soporte para la configuración de URL de privacidad y términos y condiciones en los ajustes generales.
* Se actualizaron las funciones `coimne_register_general_settings()` y `coimne_dashboard_url_callback()` para incluir las nuevas opciones de URL de privacidad y términos.
* Se corrigieron inconsistencias en la estructura de los formularios del dashboard (`profile-c.php`, `profile-e.php`, `profile-o.php`).
* Se mejoró la validación de los datos del usuario en `class-api.php`, asegurando que el tipo de usuario (`userType`) sea asignado correctamente.
* Se eliminó `class-dashboard.php`, `class-login.php`, `class-menu.php` y `class-widgets.php` al ser redundantes con la nueva estructura de frontend.
* Se corrigió la referencia a `COIMNE_CUSTOM_TEMPLATES_DIR` en `constants.php` para mejorar la organización de las plantillas.
* Se eliminó `vendor-manager.php`, moviendo la gestión de dependencias a una estrategia más eficiente.
* Se mejoró el manejo de localidades en `coimne-dashboard.js`, agregando eventos a los nuevos elementos `loc` y `emp_loc`.
* Se corrigió la validación de `COIMNE_OPTION_DASHBOARD_URL` y `COIMNE_OPTION_LOGIN_URL` en los ajustes generales.
* Se actualizó la estructura del `init.php` para reflejar los cambios en la carga de clases.
* Se mejoró la estructura de `dashboard-parts` eliminando referencias obsoletas a `EMP`, reemplazándolas por `EMP_COI` donde corresponde.

= 1.0.8 =
* Correcciones menores en el paquete de actualización para WordPress.

= 1.0.7 =
* Mejoras en el diseño responsivo del formulario en el dashboard.
* Corrección de clases y estilos en los formularios para mejorar la alineación y distribución de los campos.
* Se actualizó el proceso de autenticación para que use `coimne_login_form` en lugar de `coimne_login`.
* Se corrigió la redirección después del login para que utilice `dashboardUrl` en lugar de `redirectUrl`.
* Se refactorizó el sistema de configuración, reemplazando nombres de opciones de configuración con constantes (`COIMNE_OPTION_*`).
* Se optimizó la estructura de los shortcodes, agregando `[coimne_login_btn]` para mostrar el botón de login.
* Se reorganizó la estructura de archivos del plugin para mejorar la mantenibilidad.
* Se implementó `throwError()` en `Coimne_API` para manejar errores de API de forma más consistente.
* Se agregó soporte para obtener localidades (`get_locs()`) en el perfil del usuario.
* Se corrigieron errores en la estructura del menú de WordPress en `class-menu.php`.
* Se mejoró la validación de reCAPTCHA en el formulario de login.
* Se actualizaron las rutas de las plantillas en el dashboard para una mayor coherencia.

= 1.0.6 =
- Se mejoró la compatibilidad con constructores visuales, creando el método `Coimne_Helper::in_the_builder()` para detectar entornos de edición y evitar la ejecución de shortcodes en el backend.
- Se implementó `Coimne_Helper::hidden_shortcode_notice()` para mostrar una vista previa de los shortcodes en los constructores en lugar de ejecutar el contenido real.
- Se refactorizaron los shortcodes del dashboard (`dashboard_shortcode`, `dashboard_menu_shortcode`, `dashboard_profile_shortcode`) para utilizar `Coimne_Helper::in_the_builder()`.
- Se corrigió el posicionamiento de `.coimne-dashboard-info`, asegurando una alineación adecuada con `align-items: flex-start;`.
- Se eliminaron estilos innecesarios en `.coimne-login-card` para mejorar la integración del formulario de login.
- Se optimizó la carga de datos en `class-dashboard.php`, eliminando la conversión innecesaria de fechas y llamadas redundantes a la API.
- Se implementaron pestañas en el formulario de perfil dentro del dashboard, facilitando la navegación entre secciones.
- Se añadieron nuevas plantillas en `templates/dashboard-parts/` con mejoras en la presentación de la información del usuario.
- Se creó la plantilla `account-e.php` y `account-o.php` para permitir la actualización de credenciales del usuario.
- Se optimizó la gestión de países, provincias y poblaciones en el perfil del usuario, mejorando la carga dinámica de datos.
- Se corrigieron problemas con la inicialización de eventos en `coimne-dashboard.js`, evitando pérdidas tras la carga dinámica de contenido.

= 1.0.5 =
- Reestructuración del dashboard, permitiendo la carga dinámica de sus secciones.
- Implementación de pestañas en el formulario de perfil para mejorar la navegación.
- Se agregó un formulario de recuperación de contraseña con efecto de giro (`flip`).
- Nueva función `coimne_forgot_password` para gestionar la recuperación de contraseñas vía API.
- Se optimizó la inicialización de eventos en `coimne-dashboard.js` para evitar pérdidas tras la carga dinámica.
- Ahora `vendor-manager.php` intentará descargar `vendor.zip` precompilado antes de ejecutar Composer.
- Se reemplazó `file_get_contents` por `wp_remote_get()` en la gestión de dependencias para mejorar compatibilidad y seguridad.
- Eliminada la dependencia de `PHP_BINARY` en la instalación automática de `vendor/`.
- Se mejoró la gestión de menús dinámicos, reorganizando `class-menu.php` y eliminando archivos redundantes.
- Refactorización de shortcodes, reemplazando `[coimne_user_data]` por `[coimne_dashboard_profile]`.
- Correcciones en `coimne-menu.js` para asegurar la correcta inicialización del dashboard tras cambios dinámicos.
- Se mejoró la validación de respuestas de la API en `class-api.php`, añadiendo mensajes de error más detallados.
- Optimización en la conversión de fechas con `format_date_to_backend()` en `helpers.php`.
- Correcciones menores en `coimne-dashboard.js` y `coimne-login.js` para mejorar estabilidad y rendimiento.

= 1.0.4 =
- Implementada la carga dinámica de provincias y poblaciones en función del país seleccionado en el formulario de perfil.
- Se añadieron indicadores de carga en los selectores mientras se obtienen los datos de la API.
- Se optimizó la gestión de sesión en `Coimne_API`, consolidando el almacenamiento del token y la información del usuario.
- Se mejoró la validación y sanitización de datos en `Coimne_Ajax` y `Coimne_API` para evitar datos inconsistentes.
- Reorganizado el formulario de perfil en `dashboard-profile.php`, agregando nuevos campos:
  - `FCH_NAC` (fecha de nacimiento), con conversión automática al formato `YYYY-MM-DD` mediante `Coimne_Helper::format_date_to_input()`.
  - Situación laboral y estado civil ahora son solo de lectura.
  - Datos de contacto y dirección de la empresa ahora dependen del país y provincia seleccionados.
- Se corrigió un problema donde las provincias y poblaciones no se actualizaban correctamente al cambiar de país.
- Se mejoró la experiencia del usuario deshabilitando temporalmente los inputs mientras se envían datos.
- Solucionado un error donde los datos del formulario podían enviarse incorrectamente si la API tardaba en responder.
- Se optimizó el código en `coimne-dashboard.js`, `coimne-login.js` y `coimne-menu.js`, mejorando la carga de datos y reduciendo redundancias.
- Refactorizada la conexión con la API en `Coimne_API` para mejorar el rendimiento y reducir código repetitivo.
- Se agregaron comentarios en las funciones clave para facilitar el mantenimiento y futuras mejoras.

= 1.0.3 =
- Implementado un sistema automático de releases en GitHub para gestionar actualizaciones sin intervención manual.
- Ahora el plugin detecta nuevas versiones mediante `updates.json` y las instala automáticamente en WordPress.
- Eliminada la necesidad de tags manuales en GitHub, reemplazado por un workflow automatizado.
- Refactorizada la gestión de `vendor/` para manejar la instalación de dependencias de Composer sin requerir comandos manuales.
- Creada una verificación dinámica de `PHP CLI` y `Composer`, con fallback a una descarga alternativa de `vendor.zip` si no están disponibles.
- Centralizado el manejo de errores en `Coimne_Vendor_Manager`, permitiendo mostrar notificaciones en el administrador de WordPress según el tipo de problema detectado.
- Corrección en la limpieza de notificaciones en WordPress después de la instalación exitosa de `vendor/`, evitando que mensajes como "Instalando dependencias con Composer..." queden visibles indefinidamente.
- Ajustes en `update-checker.php` para garantizar la compatibilidad con `plugin-update-checker v5.5`, eliminando el uso de métodos obsoletos y asegurando que WordPress detecte correctamente las actualizaciones.
- Mejoras en la estructura y legibilidad del código en `vendor-manager.php`, separando funciones de verificación de rutas para `PHP CLI` y `Composer`, asegurando compatibilidad con distintos entornos.
- Ahora el sistema de actualización del plugin maneja automáticamente la instalación y configuración de `vendor/` después de cada actualización, garantizando estabilidad sin intervención del usuario.

= 1.0.2 =
- Implementada verificación automática de dependencias en `vendor/` después de una actualización del plugin.
- Ahora el plugin instalará `vendor/` automáticamente si no existe, sin requerir intervención manual.
- Si `vendor/` cambia entre versiones, el plugin detectará la diferencia y actualizará automáticamente las dependencias.
- Se eliminó `vendor/` del repositorio, siguiendo buenas prácticas de gestión de dependencias con Composer.
- Corrección en la carga de actualizaciones del plugin, evitando errores si `vendor/` no está presente.
- Mejoras en la estructura del código para mayor estabilidad y escalabilidad.

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