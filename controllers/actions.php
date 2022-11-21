<?php

class Actions extends Controller
{

    function __construct()
    {
        parent::__construct();
        Auth::externalVisitor();
    }

    function logout()
    {
        Auth::handleLogin();
        $this->model->delCookies();

        //destroy session
        Session::destroy();
        header('location: ' . URL);
        exit;
    }

    function index()
    {
        //redirect to home
        header('location: ' . URL);
        exit;
    }

} // END Class