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

    function randomNumber($from, $to) {
        $from = trim(htmlspecialchars($from));
        $to = trim(htmlspecialchars($to));

        if (!is_numeric($from) || !is_numeric($to)) {
            $this->jsonOutputError("wrong interval, check your inputs");
        }

        $this->jsonOutput(['result' => rand($from,$to)]);
    }

    function getLogRotate() {
        $this->jsonOutput($this->model->getLogRotate());
    }

}