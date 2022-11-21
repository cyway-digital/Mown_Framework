<?php

class Error_page {

    function loadModel(){}

    function index() {
        $this->error404();
    }

    function error404() {
        http_response_code(404);
        $this->title = '404 Error';
        $page = 'error/404';
        require 'views/templates/blank.php';
    }

    function error500($msg) {
        http_response_code(500);
        $this->title = '500 Error';
        $page = 'error/500';
        require_once APP_PATH.'public/themes/'.SYS_THEME.'/blank.php';
    }

    function error401($msg) {
        http_response_code(401);
        $this->title = '401 Error';
        $page = 'error/401';
        require 'views/templates/blank.php';
    }

}