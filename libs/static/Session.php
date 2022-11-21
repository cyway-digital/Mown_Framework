<?php

class Session
{
    public static function init()
    {
        // WARNING: Use this only in login
        if ( php_sapi_name() !== 'cli' && session_status() == PHP_SESSION_NONE) {
            session_name('MOWNSID');
            session_start();
        }
    }

    public static function check() {
        if ( php_sapi_name() !== 'cli' && isset($_SESSION['token'])) {
            return true;
        } else {
            return FALSE;
        }
    }
    
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            return htmlentities($_SESSION[$key]);
        }
    }

    public static function del($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        //remove PHPSESSID from browser
        if ( isset( $_COOKIE['MOWNSID'] ) ) {
            setcookie(session_name(), "", time() - 7200, " / ");
        }

        //clear session from globals
        $_SESSION = array();

        //clear session from disk
        if ( php_sapi_name() !== 'cli' && isset($_SESSION['token'])) {
            session_destroy();
        }
    }
}
