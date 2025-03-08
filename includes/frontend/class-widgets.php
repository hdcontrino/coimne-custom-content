<?php
class Coimne_Widgets extends WP_Widget
{
    public function __construct()
    {
        parent::__construct('coimne_widget', 'Coimne Custom Content', ['description' => 'Muestra contenido especial']);
    }

    public function widget($args, $instance)
    {
        echo do_shortcode('[coimne_login_form]');
    }
}

// Registrar el widget
function coimne_register_widgets()
{
    register_widget('Coimne_Widgets');
}
add_action('widgets_init', 'coimne_register_widgets');
