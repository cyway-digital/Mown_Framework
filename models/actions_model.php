<?php

class Actions_Model extends Model {
	
	public function __construct() {
		parent::__construct();
	}
        
    public function delCookies() {
        setcookie("__use", "", time() -3600,'/');
        setcookie("__hash", "", time() -3600,'/');
    }
	
} // END