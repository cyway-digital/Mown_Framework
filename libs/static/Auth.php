<?php

class Auth {

    //Check if visitor has been logged in, if is'nt redirect to login page
    public static function handleLogin() {
        if (!Session::check()) {
            Session::destroy();
            header('location: '.URL.'login');
            exit;
        }
    }

    // Check if visitor has been logged in, if it is redirect to home-page
    public static function redirect(){
        if (Session::check()) {
            header('location: '.URL.SYS_HOME);
            exit;
        }
    }

    public static function handleRole($roles = false){
        try {
            if (is_array($roles)) {
                if (!in_array(Session::get('role'), $roles)) {
                    throw new mownException('Unauthorized');
                }
            } else {
                if (Session::get('role') != "ADMIN") {
                    throw new mownException('Unauthorized');
                }
            }
        } catch (mownException $e) {
            $e->unauthMessage();
        }
    }

    public static function handleRight($right) {
        Self::handleLogin();

        if (!Roles::getRight(Session::get('role'),$right)) {
            Self::redirect();
        }
    }

    /**
     * @deprecated
     */

    public static function externalVisitor() {
//            Session::init();
    }

} //END CLASS