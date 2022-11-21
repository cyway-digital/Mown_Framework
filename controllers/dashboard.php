<?php

class dashboard extends Controller {

    function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    function index() {
        $this->view->js = array(
            'views/dashboard/js/dashboard.vue.js'
        );

        $this->view->title = 'Dashboard';
        $this->view->render('dashboard/index');
    }

}