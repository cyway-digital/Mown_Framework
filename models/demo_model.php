<?php

class Demo_Model extends Model
{
    public function __construct() {
        parent::__construct();
    }

    public function getLogRotate() {
        return $this->db->select("name, value FROM options WHERE name = 'LOG_ROTATE_DATE'",[],'fetch');
    }

} //end class