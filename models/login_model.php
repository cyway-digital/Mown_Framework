<?php

class Login_Model extends Model
{
    public function __construct() {
        parent::__construct();
    }

    public function setSession($userID) {
       $this->refreshSession($userID);
    }

    public function setLastLogin($userID) {
        $this->db->update('users', array('last_login' => date("Y-m-d H:i:s",time())), 'id = '.$userID);
    }

    public function searchUser($email){
        $queryData = array(':email' => $this->dbSafe($email));
        return $this->db->select("id, email, password, salt, active FROM users WHERE email = :email LIMIT 1", $queryData, 'fetch');
    }
} //end class