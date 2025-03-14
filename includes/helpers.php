<?php

if (!defined('ABSPATH')) exit;

class Coimne_Helper
{
    /**
     * Obtiene la URL de un asset dentro del plugin, asegurando la versión más reciente 
     * basada en la fecha de modificación del archivo.
     * 
     * @param string $path Ruta relativa al archivo dentro de la carpeta "assets".
     * @return string URL del asset con el parámetro de versión.
     */
    public static function asset_url($path = '')
    {
        $file_path = plugin_dir_path(dirname(__FILE__)) . 'assets/' . ltrim($path, '/');
        $version = file_exists($file_path) ? filemtime($file_path) : '1.0.0';

        return plugin_dir_url(dirname(__FILE__)) . 'assets/' . ltrim($path, '/') . '?v=' . $version;
    }

    /**
     * Convierte una fecha de formato DD/MM/YYYY a YYYY-MM-DD
     * para ser utilizada en <input type="date"> en formularios HTML.
     * 
     * @param string $date Fecha en formato DD/MM/YYYY.
     * @return string Fecha en formato YYYY-MM-DD o una cadena vacía si la conversión falla.
     */
    public static function format_date_to_input($date)
    {
        if (empty($date)) {
            return '';
        }

        $dateTime = DateTime::createFromFormat('d/m/Y', $date);
        return $dateTime ? $dateTime->format('Y-m-d') : '';
    }

    /**
     * Convierte una fecha en formato estándar YYYY-MM-DD a DD-MM-YYYY
     * para que el backend pueda procesarla correctamente.
     * 
     * @param string $date Fecha en formato YYYY-MM-DD.
     * @return string Fecha en formato DD-MM-YYYY o una cadena vacía si la conversión falla.
     */
    public static function format_date_to_backend($date)
    {
        if (empty($date)) {
            return '';
        }

        $dateTime = DateTime::createFromFormat('Y-m-d', $date);
        return $dateTime ? $dateTime->format('d-m-Y') : '';
    }

    /**
     * Determina si el código se está ejecutando dentro de un constructor visual de WordPress.
     * 
     * Esto se usa para evitar ejecutar código complejo dentro de editores como Divi o Elementor,
     * mostrando en su lugar un marcador de posición.
     *
     * @return bool True si el código se ejecuta dentro del constructor, false en caso contrario.
     */
    public static function in_the_builder()
    {
        return is_admin() 
            || (defined('ET_BUILDER_PLUGIN_ACTIVE') && ET_BUILDER_PLUGIN_ACTIVE) 
            || (function_exists('et_core_is_builder_active') && et_core_is_builder_active()) 
            || (defined('DOING_AJAX') && DOING_AJAX && isset($_REQUEST['et_fb']))
            || (isset($_GET['et_fb']) && $_GET['et_fb'] == 1)
            || !empty($_GET['et_pb_preview']);
    }

    /**
     * Genera un marcador de posición visual para shortcodes cuando se renderizan en constructores visuales.
     * 
     * @param string $name Nombre del shortcode.
     * @return string HTML con el marcador de posición.
     */
    public static function hidden_shortcode_notice($name = '')
    {
        $styles = "display: flex;justify-content: center;padding: 20px;border: 1px dashed #ccc;font-size: 18px;align-items: center;";
        return "<div style='$styles'><span class='dashicons dashicons-layout'></span> COIMNE $name Shortcode</div>";
    }

    /**
     * Valida un NIF/NIE español.
     * 
     * - Un NIF válido tiene 8 números seguidos de una letra (Ejemplo: 12345678Z).
     * - Un NIE válido comienza con X, Y o Z, seguido de 7 números y una letra (Ejemplo: X1234567L).
     * - La letra final se calcula a partir del número con un algoritmo específico.
     * 
     * @param string $nif NIF o NIE a validar.
     * @return bool True si el NIF/NIE es válido, false en caso contrario.
     */
    public static function validar_nif($nif)
    {
        $dniRegex = '/^[0-9]{8}[A-Z]$/';
        $nieRegex = '/^[XYZ][0-9]{7}[A-Z]$/';

        if (!preg_match($dniRegex, $nif) && !preg_match($nieRegex, $nif)) {
            return false;
        }

        $num = substr($nif, 0, -1);
        $letra = substr($nif, -1);

        // Convertir NIE a número equivalente
        if ($nif[0] === 'X') $num = '0' . substr($nif, 1);
        if ($nif[0] === 'Y') $num = '1' . substr($nif, 1);
        if ($nif[0] === 'Z') $num = '2' . substr($nif, 1);

        $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        return $letra === $letras[$num % 23];
    }

    /**
     * Valida un número de teléfono español.
     * 
     * - Debe tener exactamente 9 dígitos.
     * - Solo se permiten números.
     * - Debe comenzar con 6, 7, 8 o 9.
     * - Se permite que el usuario ingrese el teléfono con espacios o guiones, los cuales serán eliminados.
     * 
     * @param string $telefono Número de teléfono a validar.
     * @return bool True si el teléfono es válido, false en caso contrario.
     */
    public static function validar_telefono($telefono)
    {
        $telefono = preg_replace('/[\s-]/', '', $telefono);

        return preg_match('/^[6789]\d{8}$/', $telefono);
    }
}
