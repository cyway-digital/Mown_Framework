<?php
 class Service_Model extends Model {
	
    public function __construct() {
        parent::__construct();
    }
    
    public function getEnv($cache) {
        // TODO cache redundant contents
        $return = [];

        $lastRefresh = $this->db->select("value FROM options WHERE name = 'LAST_REFRESHER'",[],'fetch',PDO::FETCH_COLUMN);

        if ($lastRefresh) {
            $lastRefreshData = new DateTime($lastRefresh);
            $return['lastRefreshLabel'] = $lastRefreshData->format('d/m/Y H:i');
            $return['lastRefresh'] = $lastRefresh;
        }

        return $return;
    }

} // END