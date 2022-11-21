<?php

class Service extends Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        header('Location: '.URL);
    }

    function getRights() {
        Auth::handleLogin();
        $this->handleRequest('GET');
        $this->jsonOutput(Roles::getRightDefinition(Session::get('role')));
    }

    function getEnv() {
        $data = $this->model->getEnv($this->cache);

        if (Session::check()) {
            $data['user'] = [
                'uid' => Session::get('id'),
                'firstname' => Session::get('name'),
                'lastname' => Session::get('surname'),
            ];
        }

        $this->jsonOutput($data);
    }

} // END Class