<?php
/**
 * Cache manager for Mown
 *
 * @package MOWN FRAMEWORK
 * @author mike@cyway.it
 *
 * Requires extra config params. For example:
 *
 * define('CACHE_PATH', $workDir.'/cache/'); //absolute
 * define('CACHE_DEFAULT_TIME', 1800); // 900 sec = 15 min
 */

class fileCache
{
    private $_cacheDir;
    private $_cacheTime;
    private $_splitPlaceHolder = '#';

    function __construct() {
        $this->_cacheTime = CACHE_DEFAULT_TIME;
        $this->_cacheDir = CACHE_PATH;
    }

    function get($filename) {
        $fileData = $this->splitName($filename);

        if (!file_exists($this->_cacheDir.$fileData['name'])) {
            return false;
        }

        if (filemtime($this->_cacheDir.$fileData['name']) > (time() - $this->_cacheTime )) {
            return unserialize(file_get_contents($this->_cacheDir.$fileData['name']));
        }

        return false;

    }

    function set($data,$filename,$time = CACHE_DEFAULT_TIME) {
        if (!file_exists($this->_cacheDir.$filename)) {
            $fileo = fopen($this->_cacheDir . $filename, 'w');
            fclose($fileo);
        }

//        file_put_contents($this->_cacheDir.$filename, json_encode($data), LOCK_EX);
        file_put_contents($this->_cacheDir.$filename, serialize($data), LOCK_EX);
        chmod($this->_cacheDir.$filename, 0777);
    }

    function check($filename) {
        if (file_exists($this->_cacheDir.$filename) && (filemtime($this->_cacheDir.$filename) > (time() - $this->_cacheTime ))) {
            //is cached
            return true;
        }

        return false;
    }

    function invalidate($filename) {
        if (!file_exists($this->_cacheDir.$filename)) {
            return false;
        }

        return unlink($this->_cacheDir.$filename);
    }

    private function splitName($filename) {
        $data = explode($this->_splitPlaceHolder,$filename);

        if (!$data) {
            return [
                'name' => $filename,
                'time' => 0
            ];
        }

        if (count($data) == 1) {
            return [
                'name' => $filename,
                'time' => 0
            ];
        }

        return [
            'name' => $data[0],
            'time' => $data[1],
        ];

    }

}
