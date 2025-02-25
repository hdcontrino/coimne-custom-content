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

    public function clear_session()
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }

    public function destroy_session()
    {
        session_destroy();
    }
}
