<?php

class View {

    function __construct() {}

    public function render($page, $blank = false) {
        // $page is used in template files
        if (!$blank) {
            require THEME_PATH.'/main.php';
        } else {
            require THEME_PATH.'/blank.php';
        }
    }

    public function renderLogin() {
        require 'views/login/index.php';
    }
    
}