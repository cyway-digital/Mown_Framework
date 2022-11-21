<?php

class dbLogsEngine {

    private $db;
    public $table = 'events_log';

    function __construct($db) {
        if ($db instanceof Database) {
            $this->db = $db;
        } // else?
    }

    function addLog($data) {
        return $this->db->insert($this->table,$data);
    }
}