<?php

if (!defined('ABSPATH')) exit;

class Coimne_Helper
{
    public static function asset_url($path = '')
    {
        $file_path = plugin_dir_path(dirname(__FILE__)) . 'assets/' . ltrim($path, '/');
        $version = file_exists($file_path) ? filemtime($file_path) : '1.0.0';

        return plugin_dir_url(dirname(__FILE__)) . 'assets/' . ltrim($path, '/') . '?v=' . $version;
    }

    /**
     * Convierte una fecha de DD/MM/YYYY a YYYY-MM-DD
     * para ser utilizada en <input type="date">
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
     * Convierte una fecha estándar YYYY-MM-DD a DD-MM-YYYY
     * para que el backend la pueda entender.
     */
    public static function format_date_to_backend($date)
    {
        if (empty($date)) {
            return '';
        }

        $dateTime = DateTime::createFromFormat('Y-m-d', $date);
        return $dateTime ? $dateTime->format('d-m-Y') : '';
    }

    public static function in_the_builder()
    {
        return is_admin() 
            || (defined('ET_BUILDER_PLUGIN_ACTIVE') && ET_BUILDER_PLUGIN_ACTIVE) 
            || (function_exists('et_core_is_builder_active') && et_core_is_builder_active()) 
            || (defined('DOING_AJAX') && DOING_AJAX && isset($_REQUEST['et_fb']))
            || (isset($_GET['et_fb']) && $_GET['et_fb'] == 1)
            || !empty($_GET['et_pb_preview']);
    }

    public static function hidden_shortcode_notice($name = '')
    {
        $styles = "display: flex;justify-content: center;padding: 20px;border: 1px dashed #ccc;font-size: 18px;align-items: center;";
        return "<div style='$styles'><span class='dashicons dashicons-layout'></span> COIMNE $name Shortcode</div>";
    }
}
