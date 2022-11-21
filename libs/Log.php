<?php

/**
 * MOWN Class Log
 * @author M. Vulcano - Cyway.it
 * @version 1.0
 *
 * Requires extra config params:
 *
 * define('LOG_FILE_SYSTEM', $workDir.'/logs/system.log'); //absolute
 * define('LOG_FILE_DB', $workDir.'/logs/db.log'); // DEV ONLY
 * define('LOG_ROTATE_DAYS', 7);
 * define('LOG_MAX_FILES', 20); // retemption for each LOG_FILE_*
 * define('LOG_MAX_WEIGHT', 5); // single log file max weight, in MB
 *
 */

class Log {

    private $_db;
    private $_lastRotation;
    private $_logRotateDays;
    private $cache;
    private $_logFiles;
    private $currentLog;

    function __construct($logRotateDays,$cacheClass = false) {
        if ($cacheClass === false) {
            try {
                if (class_exists('fileCache')) {
                    $this->cache = new fileCache();
                } else {
                    throw new mownException("Class fileCache not found in Log::__construct()");
                }
            } catch (mownException $e) {
                $e->errorMessage();
            }
        } else {
            $this->cache = $cacheClass;
        }

        if (is_numeric($logRotateDays)) { $this->_logRotateDays = $logRotateDays; }
        else { $this->_logRotateDays = 7; }

        if ($this->cache->check('logrotateday')) {
            $this->_lastRotation = $this->cache->get('logrotateday');
        } else {
            if (!$this->_db instanceof Database) {
                $this->_db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS,[]);
            }

            $this->_lastRotation = $this->_db->select("value FROM options WHERE name = 'LOG_ROTATE_DATE'", [], 'fetch', PDO::FETCH_COLUMN);
            $this->cache->set($this->_lastRotation,'logrotateday');
        }

        // Set defult log file
        $this->collectLogFiles();
        $this->setLogFile('system');
    }

    private function collectLogFiles() {
        $this->_logFiles = [
            'system' => LOG_FILE_SYSTEM,
            'db' => LOG_FILE_DB,
            'refresher' => APP_PATH.'logs/refresher.log',
            'mail' => APP_PATH.'logs/mail.log'
        ];
    }

    public function setLogFile($log) {
        $this->currentLog = $this->_logFiles[$log] ?? $this->_logFiles['system'];
    }

    public function warning($msg){
        $this->logWrite("WARNING",$msg);
    }

    public function error($msg){
        $this->logWrite("ERROR",$msg);
    }

    public function notice($msg){
        $this->logWrite("NOTICE",$msg);
    }

    public function access($call, $data) {
        $this->logWrite("ACCESS", $_SERVER['REMOTE_ADDR'].' | CALL: '.$call.'  |  PAYLOAD: '.$data);
    }

    public function pushHistory($msg, $title, $priority, $payload) {
        $this->logWrite("ACCESS", "PUSH-MESSAGE (Priority {$priority}) SENT: ".$title." - ".$msg." - PAYLOAD RESPONSE: ".$payload);
    }

    public function db($msg) {
        $this->logWrite("DB", $msg);
    }

    private function logWrite($type,$msg){
        // Do our log rotation routines first
        $this->rotation();

        $logFile = $this->_logFiles['system'];

        if ($type == 'DB') {
            $logFile = $this->_logFiles['db'];
        }

        if ($type == 'MAIL') {
            $logFile = $this->_logFiles['mail'];
        }

        $var = fopen($logFile,"a+");

        if ($var) {
            if (flock($var, LOCK_EX)) {
                fwrite($var, "[".date("Y-m-d H:i:s")."]"." [".$type."] ".$msg."\r\n");
                flock($var, LOCK_UN);
            }
        } else {
            //TODO Mail? Push?
        }

        fclose($var);
    }

    public function mailHistory($subject, $body, $recipients) {
        $this->logWrite("MAIL", "Message with subject '{$subject}' sent to: {$recipients}\n{$body}\n_________________________________________________");
    }

    public function write($msg){
        // Do our log rotation routines first
        $this->rotation();

        $var = fopen($this->currentLog,"a+");

        if ($var) {
            if (flock($var, LOCK_EX)) {
                fwrite($var, "[".date("Y-m-d H:i:s")."] ".$msg."\r\n");
                flock($var, LOCK_UN);
            }
        }

        fclose($var);
    }

    private function rotation() {
        $logRotDiff = (new DateTime($this->_lastRotation))->diff(new DateTime());

        if ($this->_logRotateDays >= 1 && $logRotDiff->days >= $this->_logRotateDays) {
            $rotationTime = new DateTime();
            $dirFiles = array_diff(scandir(APP_PATH.'logs/'), array('.', '..'));

            foreach ($this->_logFiles as $logFile) {
                if (file_exists($logFile) && filesize($logFile) >= LOG_MAX_WEIGHT * 1048576) {
                    // create another log file
                    $logFileExplode = explode('/',$logFile);
                    $fileName = end($logFileExplode);
                    $now = date('Y-m-d');
                    $rotatedFile = $logFile.'_'.$now.'.log';

                    if (file_exists($logFile)) { rename($logFile,$rotatedFile); }

                    $var = fopen($logFile,"a");
                    fwrite($var, "[".date("Y-m-d H:i:s")."]"." [LOG_ROTATION] $logFile continues from $rotatedFile\r");
                    fclose($var);

                    //remove old files
                    $dates = [];

                    foreach ($dirFiles as $k=>$f) {
                        $file = explode('_',$f);

                        if (count($file) > 1 && $file[0] == $fileName) {
                            $fileExplode = explode('.log',$file[1]);
                            $date = new DateTime($fileExplode[0]);

                            if ($date instanceof DateTime) {
                                $dates[] = array('date' => $date, 'file' => $f);
                            }
                        }
                    }

                    //sort by date - PHP7 Only here
                    usort($dates, function($a, $b) { return $a['date'] <=> $b['date']; });

                    if (count($dates) > LOG_MAX_FILES) {
                        $dates = array_slice($dates, 0, (count($dates) - LOG_MAX_FILES));

                        foreach ($dates as $d) {
                            unlink(APP_PATH.'logs/'.$d['file']);
                            $var = fopen($logFile,"a");
                            fwrite($var, "[".date("Y-m-d H:i:s")."]"." [LOG_ROTATION] Removed Log file ".$d['file']."\r");
                            fclose($var);
                        }
                    }
                }
            }

            if (!$this->_db instanceof Database) {
                $this->_db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
            }

            $this->_db->update('options', array('value' => $rotationTime->format("Y-m-d H:i:s")), "name='LOG_ROTATE_DATE'");
            $this->cache->invalidate('logrotateday');

        }
    }

}

