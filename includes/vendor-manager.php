<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_Vendor_Manager
{
    public static function manejar_error($codigo)
    {
        $errores = [
            'NO_PHP_CLI' => ["PHP CLI no está disponible en el servidor.", 'error'],
            'NO_COMPOSER' => ["Composer no está disponible en el servidor.", 'error'],
            'VENDOR_NO_GENERADO' => ["Error crítico: vendor/autoload.php no fue generado. Es posible que Composer haya fallado.", 'error'],
            'INSTALACION_COMPOSER' => ["Instalando dependencias con Composer...", 'warning'],
            'VENDOR_NO_DESCARGADO' => ["ERROR: No se pudo descargar vendor.zip.", 'error'],
            'VENDOR_NO_EXTRAIDO' => ["ERROR: No se pudo extraer vendor.zip.", 'error'],
        ];

        if (isset($errores[$codigo])) {
            [$mensaje, $tipo] = $errores[$codigo];
            update_option('coimne_admin_error_notice', json_encode(['mensaje' => $mensaje, 'tipo' => $tipo]));
            error_log("[Coimne Custom Content] $mensaje");
        }
    }

    /**
     * Verifica si es necesario actualizar las dependencias en vendor/
     */
    public static function necesita_actualizar_vendor()
    {
        $composer_lock = COIMNE_CUSTOM_CONTENT_DIR . 'composer.lock';
        $hash_file = COIMNE_CUSTOM_CONTENT_DIR . 'vendor/.installed_hash';

        if (!file_exists($composer_lock)) {
            return false;
        }

        $actual_hash = hash_file('sha256', $composer_lock);

        if (!file_exists($hash_file) || file_get_contents($hash_file) !== $actual_hash) {
            return true;
        }

        return false;
    }

    /**
     * Instala o actualiza las dependencias de Composer en vendor/
     */
    public static function instalar_o_actualizar_composer()
    {
        $vendor_path = COIMNE_CUSTOM_CONTENT_DIR . 'vendor/';

        self::descargar_vendor();

        if (file_exists($vendor_path . 'autoload.php')) {
            delete_option('coimne_admin_error_notice');
            return;
        }

        $php_bin = self::obtener_php_bin();
        $composer_bin = self::obtener_composer_bin();

        if (!$php_bin) {
            self::manejar_error('NO_PHP_CLI');
            return;
        }

        if (!$composer_bin) {
            self::manejar_error('NO_COMPOSER');
            return;
        }

        if (self::necesita_actualizar_vendor()) {
            self::manejar_error('INSTALACION_COMPOSER');

            if (!is_dir($vendor_path)) {
                mkdir($vendor_path, 0755, true);
            }

            $command = "cd " . escapeshellarg(COIMNE_CUSTOM_CONTENT_DIR) . " && $php_bin $composer_bin install --no-dev --optimize-autoloader";
            $output = shell_exec($command . " 2>&1");
            error_log("[Coimne Custom Content] Salida de Composer: $output");

            if (!file_exists($vendor_path . 'autoload.php')) {
                self::manejar_error('VENDOR_NO_GENERADO');
            } else {
                file_put_contents($vendor_path . '.installed_hash', hash_file('sha256', COIMNE_CUSTOM_CONTENT_DIR . 'composer.lock'));
                delete_option('coimne_admin_error_notice');
            }
        }
    }

    /**
     * Descarga una versión precompilada de vendor si Composer no está disponible.
     */
    public static function descargar_vendor()
    {
        $vendor_zip = COIMNE_CUSTOM_CONTENT_DIR . 'vendor.zip';
        $vendor_url = 'https://github.com/hdcontrino/coimne-custom-content/releases/latest/download/vendor.zip';

        $response = wp_remote_get($vendor_url, [
            'timeout' => 30,
            'sslverify' => false
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            self::manejar_error('VENDOR_NO_DESCARGADO');
            return;
        }

        $file_contents = wp_remote_retrieve_body($response);
        if (empty($file_contents)) {
            self::manejar_error('VENDOR_NO_DESCARGADO');
            return;
        }

        if (file_put_contents($vendor_zip, $file_contents) === false) {
            self::manejar_error('VENDOR_NO_DESCARGADO');
            return;
        }

        $zip = new ZipArchive;
        if ($zip->open($vendor_zip) === TRUE) {
            $zip->extractTo(COIMNE_CUSTOM_CONTENT_DIR);
            $zip->close();
            unlink($vendor_zip);
        } else {
            self::manejar_error('VENDOR_NO_EXTRAIDO');
        }
    }

    /**
     * Muestra un mensaje de error en la administración de WordPress.
     */
    public static function mostrar_error_admin_notice()
    {
        $error_json = get_option('coimne_admin_error_notice');
        if ($error_json) {
            $error_data = json_decode($error_json, true);
            $mensaje = $error_data['mensaje'] ?? 'Error desconocido.';
            $tipo = $error_data['tipo'] ?? 'error';

            echo '<div class="notice notice-' . esc_attr($tipo) . '"><p><b>Coimne Custom Content - </b>' . esc_html($mensaje) . '</p></div>';
            delete_option('coimne_admin_error_notice');
        }
    }

    /**
     * Obtiene la ruta del ejecutable PHP CLI.
     */
    private static function obtener_php_bin()
    {
        $php_bin = PHP_BINARY;

        if (strpos($php_bin, 'php-fpm') !== false) {
            $php_bin = trim(shell_exec("command -v php"));
        }

        $posibles_rutas = [
            '/usr/bin/php',
            '/usr/local/bin/php',
            '/opt/php/bin/php',
            '/opt/local/bin/php',
            '/usr/sbin/php',
            '/opt/homebrew/bin/php'
        ];

        foreach ($posibles_rutas as $ruta) {
            if (is_executable($ruta)) {
                $php_bin = $ruta;
                break;
            }
        }

        return (is_executable($php_bin)) ? $php_bin : null;
    }

    /**
     * Obtiene la ruta del ejecutable Composer.
     */
    private static function obtener_composer_bin()
    {
        $composer_bin = trim(shell_exec("command -v composer"));

        $posibles_rutas = [
            '/usr/bin/composer',
            '/usr/local/bin/composer',
            '/opt/php/bin/composer',
            '/opt/local/bin/composer',
            '/usr/sbin/composer'
        ];

        foreach ($posibles_rutas as $ruta) {
            if (is_executable($ruta)) {
                $composer_bin = $ruta;
                break;
            }
        }

        return (is_executable($composer_bin)) ? $composer_bin : null;
    }
}

add_action('admin_notices', ['Coimne_Vendor_Manager', 'mostrar_error_admin_notice']);
