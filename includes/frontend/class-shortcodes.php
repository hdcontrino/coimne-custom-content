<?php
class Coimne_Shortcodes
{
    public function __construct()
    {
        add_shortcode('coimne_login', [$this, 'render_login_form']);
    }

    public function render_login_form()
    {
        ob_start();
        include COIMNE_CUSTOM_CONTENT_DIR . 'templates/login-form.php';
        return ob_get_clean();
    }
}
