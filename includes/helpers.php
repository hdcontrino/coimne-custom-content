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
}
