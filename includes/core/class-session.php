<?php
class Coimne_Session
{
    public function __construct()
    {
        add_action('init', [$this, 'start_session']);
    }

    public function start_session()
    {
        if (!session_id()) {
            session_start();
        }
    }

    public function set_session($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get_session($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function destroy_session()
    {
        session_destroy();
    }
}
