<?php

if (!defined('ABSPATH')) {
    exit;
}

function coimne_settings_page()
{
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
?>
    <div class="wrap">
        <h1><?php _e('Ajustes de Coimne Custom Content', 'coimne-custom-content'); ?></h1>

        <h2 class="nav-tab-wrapper">
            <a href="?page=coimne-settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
                <?php _e('General', 'coimne-custom-content'); ?>
            </a>
            <a href="?page=coimne-settings&tab=api" class="nav-tab <?php echo $active_tab == 'api' ? 'nav-tab-active' : ''; ?>">
                <?php _e('API', 'coimne-custom-content'); ?>
            </a>
            <a href="?page=coimne-settings&tab=recaptcha" class="nav-tab <?php echo $active_tab == 'recaptcha' ? 'nav-tab-active' : ''; ?>">
                <?php _e('reCAPTCHA v2', 'coimne-custom-content'); ?>
            </a>
        </h2>

        <div class="coimne-settings-content">
            <?php
            if ($active_tab == 'general') {
                echo '<form method="post" action="options.php">';
                settings_fields('coimne_settings_group_general');
                do_settings_sections('coimne-settings-general');
                submit_button();
                echo '</form>';

                // Agregar la lista de shortcodes después del formulario
                coimne_display_shortcodes();
            } elseif ($active_tab == 'api') {
                echo '<form method="post" action="options.php">';
                settings_fields('coimne_settings_group_api');
                do_settings_sections('coimne-settings-api');
                submit_button();
                echo '</form>';
            } elseif ($active_tab == 'recaptcha') {
                echo '<form method="post" action="options.php">';
                settings_fields('coimne_settings_group_recaptcha');
                do_settings_sections('coimne-settings-recaptcha');
                submit_button();
                echo '</form>';
            }
            ?>
        </div>
    </div>

    <style>
        .coimne-shortcodes-section {
            margin-top: 30px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .coimne-shortcodes-section h2 {
            margin-top: 0;
        }

        .coimne-shortcodes-section ul {
            list-style-type: none;
            padding: 0;
        }

        .coimne-shortcodes-section li {
            margin-bottom: 5px;
            font-family: monospace;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #0073aa;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }
    </style>
<?php
}
?>