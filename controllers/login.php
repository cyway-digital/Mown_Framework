<?php

class login extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::redirect();
    }
    
    public function index()
    {
        $this->view->renderLogin();
    }

    public function run() {
        $data = $this->handleRequest('POST',[
            'u' => 'string',
            'p' => 'string'
        ]);

        if ($this->login($data['u'], $data['p'])) {
            $ret['response'] = 1;
            $ret['redirectURL'] = URL . SYS_HOME;
            echo json_encode($ret);
        } else {
            $ret['response'] = 0;
            $ret['error'] = 'fail [f33]';
            http_response_code(401);
            echo json_encode($ret);
            return false;
        }
    }

    private function login($username, $password) {
        $userData = $this->model->searchUser($username);
        $password = hash('sha512', $password . $userData['salt']);

        if (!$userData || !isset($userData['active']) || $userData['active'] != 1) {
            return false;
        }

        if ($password != $userData['password']) {
            return false;
        }
        Session::init();
        Session::set('token', bin2hex(openssl_random_pseudo_bytes(16)));
        $this->model->setSession($userData['id']);
        $this->model->setLastLogin($userData['id']);
        return true;
    }

} //End Class
