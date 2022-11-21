<?php

class settings extends Controller
{

    function __construct()
    {
        parent::__construct();
        Auth::handleLogin();
    }

    function index()
    {

        $this->view->sysMail = $this->model->getSysMailInfo();
        $this->view->mySQLVer = $this->model->getMysqlVer();
        $this->view->mailSendingStatus = $this->model->getMailSendingStatus();

        $this->view->js = array(
            'views/settings/js/settings.vue.js',
            'public/plugins/moment.min.js',
            'public/js/sha512.js'
        );

        $this->view->css = array(
            'views/settings/css/settings.css',
        );

        $this->view->title = "Settings";
        $this->view->render('settings/index');
    }

    public function changePwd()
    {
        $data = $this->handleRequest('POST', [
            'oldPwd' => 'string',
            'newPwd1' => 'string',
            'newPwd2' => 'string',
        ]);

        if ($data['newPwd1'] != $data['newPwd2']) {
            $this->jsonOutputError("New Passwords must match");
        }

        $userData = $this->model->searchUser(Session::get('id'));

        if ($userData) {
            $oldPwd = hash('sha512', $data['oldPwd'] . $userData['salt']);

            if ($oldPwd == $userData['password']) {
                $salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), true));
                $newPwd = hash('sha512', $data['newPwd1'] . $salt);
//                $this->log->notice("User #{$userData['id']} changes his password");
                $this->dbLogs->addLog([
                    ':severity' => 'NOTICE',
                    ':user_id' => $userData['id'],
                    ':param' => 'personal_password',
                    ':text' => "User #{$userData['id']} changes his password"
                ]);
                $this->model->setNewPwd($newPwd, $salt);
                $this->jsonOutput([]);
            } else {
                $this->jsonOutputError("Wrong old Password");
            }
        } else {
            $this->jsonOutputError("User not found");
        }
    }

    public function getUsers()
    {
        Auth::handleRole(); //admins only here!
        $data = $this->handleRequest('POST', [
            'sort' => 'string',
            'sort_dir' => 'int',
        ]);

        $dateFields = ['date_add']; // what fields has date value

        $sortDir = $data['sort_dir'] ? SORT_ASC : SORT_DESC;
        $list = $this->model->usersListJSON();

        if (in_array($data['sort'], $dateFields)) {
            array_multisort(array_map('strtotime', array_column($list, $data['sort'])), $sortDir, $list);
        } else {
            array_multisort(array_column($list, $data['sort']), $sortDir, $list);
        }

        $return['list'] = $list;
        $return['_vars'] = [
            'sort' => $data['sort'],
            'sort_direction' => $data['sort_dir'],
        ];


        $this->jsonOutput($return);
    }

    public function editUser()
    {
        Auth::handleRole(); //admins only here!
        $data = $this->handleRequest('POST', [
            'name' => 'string',
            'surname' => 'string',
            'role' => 'string',
            'email' => 'string',
            'id' => 'int',
        ]);

        $edit = $this->model->editUser($data['id'], $data['name'], $data['surname'], $data['email'], $data['role']);

        if (!$edit) {
            $this->jsonOutputError("NOT updated. Retry");
        }

//        $this->log->notice("User #" . Session::get('id') . " edited User #".$data['id']." data to: ".print_r($data,true));
        $this->dbLogs->addLog([
            ':severity' => 'NOTICE',
            ':user_id' => Session::get('id'),
            ':param' => 'admin_edit_user',
            ':text' => "edited User #".$data['id']." data to: ".print_r($data,true)
        ]);
        $this->jsonOutput([]);
    }

    public function addUser()
    {
        Auth::handleRole(); //admins only here!
        $data = $this->handleRequest('POST', [
            'firstName' => 'string',
            'lastName' => 'string',
            'email' => 'string',
        ]);

        $pwd = $this->randomPassword(12);
        $pwdHashed = hash('sha512', $pwd);

        $salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
        $password = hash('sha512', $pwdHashed . $salt);
        $res = $this->model->addUser($data['email'], $data['firstName'], $data['lastName'], $password, $salt);

        $mail = $this->mail->sendWelcome($data['email'], $data['firstName'], $pwd);
        
        $this->dbLogs->addLog([
            ':severity' => 'NOTICE',
            ':user_id' => Session::get('id'),
            ':param' => 'admin_user_added',
            ':text' => "added system User ".$data['email']
        ]);
        $this->jsonOutput($pwd);
    }

    public function pwdReset()
    {
        Auth::handleRole(); //admins only here!
        $data = $this->handleRequest('POST', [
            'uid' => 'int',
        ]);

        $pwd = $this->randomPassword(14);
        $hashedPwd = hash('sha512', $pwd);
        $salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
        $newPwd = hash('sha512', $hashedPwd . $salt);

        $res = $this->model->pwdReset($data['uid'], $newPwd, $salt);

        if ($res) {
            $this->mail->sendPwdReset($res['email'], $res['name'], $pwd);
        }
        
//        $this->log->notice("User #" . Session::get('id') . " resetted password of User #".$data['uid']);
        $this->dbLogs->addLog([
            ':severity' => 'NOTICE',
            ':user_id' => Session::get('id'),
            ':param' => 'admin_user_password_reset',
            ':text' => "resetted password of User #".$data['uid']
        ]);
        $this->jsonOutput([]);
    }

    public function getSysInfo()
    {
        Auth::handleRole(); //admins only here!
        $data = $this->handleRequest('POST', [
            'version' => 'string'
        ]);

        $return = [
            'mySqlVer' => $this->model->getMysqlVer(),
            'uname' => PHP_OS . " - " . php_uname(),
            'phpVer' =>  phpversion() . $data['version'],
            'openSslActive' => is_callable("openssl_random_pseudo_bytes") ? 'OK' : 'NO',
        ];

        $this->jsonOutput($return);
    }

    function toggleUserActivation()
    {
        Auth::handleRole(); //admins only here!
        $data = $this->handleRequest('POST', [
            'uid' => 'int'
        ]);


        $upd = $this->model->toggleUserActivation($data['uid']);

        if (!$upd) {
            $this->jsonOutputError('User NOT edited. Retry');
        }

//        $this->log->notice("User #" . Session::get('id') . " toggled blocking state of User #".$data['uid']);
        $this->dbLogs->addLog([
            ':severity' => 'NOTICE',
            ':user_id' => Session::get('id'),
            ':param' => 'admin_blocking_state_toggled',
            ':text' => "toggled blocking state of User #".$data['uid']
        ]);

        $this->jsonOutput([]);
    }

    private function randomPassword($length = 10)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyz_.$?!#%*-ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
} //END CLASS
