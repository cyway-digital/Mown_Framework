<?php

class Settings_Model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // searches for a given user
    public function searchUser($userID)
    {
        $queryData = array(':id' => $userID);
        return $this->db->select("id, email, password, salt FROM 
                users WHERE id = :id LIMIT 1", $queryData, 'fetch');
    }

    // sets the new password for the user
    public function setNewPwd($newPwd, $salt)
    {
        $this->db->update('users', array('password' => $newPwd, 'salt' => $salt), "id = " . Session::get('id'));
    }

    // gets the system email configured
    public function getSysMailInfo()
    { //TODO: admin check?
        return $this->db->select("value FROM options WHERE name = 'system_email'", array(), 'fetch', PDO::FETCH_COLUMN);
    }

    // gets the system email sendind status
    public function getMailSendingStatus()
    { //TODO: admin check?
        return $this->db->select("value FROM options WHERE name = 'EMAIL_SENDING'", array(), 'fetch', PDO::FETCH_COLUMN);
    }

    // returns a full list of users configured
    public function usersListJSON()
    {
        $objs = $this->db->select("id, name, surname, email, last_login, date_add, role, active FROM users", array(), 'fetchAll');

        foreach ($objs as $key => $value) {
            $objs[$key]['date_add_iso'] = date('c', strtotime($objs[$key]['date_add'] ?? ''));
            $objs[$key]['last_login_iso'] = date('c', strtotime($objs[$key]['last_login'] ?? ''));
        }
        return $objs;
    }

    public function editUser($uid, $name, $lastName, $email, $role)
    {
        $queryData = array(
            'email' => $email,
            'name' => $name,
            'surname' => $lastName,
            'role' => $role,
        );

        return $this->db->update('users', $queryData, "id = " . $uid);
    }

    public function addUser($mail, $firstname, $lastame, $pwd, $salt)
    {
        $queryData = array(
            ':name' => $firstname,
            ':surname' => $lastame,
            ':email' => $mail,
            ':role' => 'OPERATOR',
            ':password' => $pwd,
            ':salt' => $salt,
            ':date_add' => date("Y-m-d H:i:s", time()),
            ':active' => 1
        );

        return $this->db->insert("users", $queryData);
    }

    public function pwdReset($userID, $newPwd, $salt)
    {
        $queryData = array(
            'password' => $newPwd,
            'salt' => $salt
        );
        $this->db->update('users', $queryData, "id = '$userID'");

        return $this->db->select('name, email FROM users WHERE id= "' . $userID . '"', array(), 'fetch', PDO::FETCH_ASSOC);
    }

    public function getMysqlVer()
    {
        return $this->db->select('version()', [], 'fetch', PDO::FETCH_COLUMN);
    }

    public function toggleUserActivation($uid)
    {
        $status = $this->db->select("active,role FROM users where id = :uid", [':uid' => $uid], 'fetch');

        if ($status['role'] == 'ADMIN') {
            return false;
        }

        $newStatus = 0;

        if ($status['active'] == 0) {
            $newStatus = 1;
        }

        return $this->db->update('users', ['active' => $newStatus], 'id = ' . $uid);
    }
}
