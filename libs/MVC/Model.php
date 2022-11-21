<?php

class Model {

    public $db;

    function __construct() {
        if (defined('SYS_STAGE') && SYS_STAGE != 'PROD') {
            $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        } else {
            $options = [];
        }
        $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS, $options);
    }

    public function refreshSession($userID) {
        $sessData = $this->db->select("id, name, surname, username, email, role FROM users WHERE id = :id", array(':id' => $this->dbSafe($userID)), 'fetch');
        //todo error handle here
        foreach ($sessData as $name => $value){
            Session::set($name, $value);
        }
        Session::set('lastAct',time());
    }

    public function dbSafe($text) {
        $text = preg_replace('/\t+/', '', $text);
        $text = preg_replace('/\n+/', '', $text);
        $text = preg_replace("/INFORMATION_SCHEMA/i", " ", $text);
        $text = preg_replace("/GRANT ALL PRIVILEGES ON /i", " ", $text);
        $text = preg_replace("/(DROP|CREATE) USER /i", " ", $text);
        $text = preg_replace("/etc(\/|%2f)passwd/i", " ", $text);
        $text = preg_replace("/TABLE_SCHEMA/i", " ", $text);
        $text = preg_replace("/union all select UNHEX/i", " ", $text);
        $text = preg_replace("/\@\@(version|datadir|hostname)/i", ' ', $text);
        $text = preg_replace("/Union Select/i", " ", $text);
        $text = preg_replace("/(0x5e5b7d7e|0x2128265E29284023)/i", " ", $text);
        $text = preg_replace("/ (RLIKE|REGEX) /i", '', $text);
        $text = str_replace('0x57414954464F522044454C4159202730303A30303A313527', '', $text); # WAITFOR DELAY '00:00:15'
        return trim($text); 
    }

    function getDb() {
        return $this->db;
    }

    function getMapIds() {
        return $this->db->select("old, new from ids_map",[],'fetchAll',PDO::FETCH_KEY_PAIR);
    }
}