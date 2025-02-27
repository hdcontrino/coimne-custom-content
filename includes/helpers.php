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
}
