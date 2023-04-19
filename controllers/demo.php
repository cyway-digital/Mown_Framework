<?php

class demo extends Controller {

    function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    function index() {
        $this->view->js = array(
            'views/demo/js/demo.vue.js'
        );

        $this->view->title = 'Demo Page';
        $this->view->render('demo/index');
    }

}