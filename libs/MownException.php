<?php
/**
 * Class mownException
 * Cyway
 */

class mownException extends Exception {

    public function errorMessage() {
        $msg = "Exception";
        if (SYS_STAGE != 'PROD') { $msg .= ": {$this->getMessage()}";}

        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            require 'controllers/error_page.php';
            (new Error_page())->error500($msg);
        } else {
            http_response_code(500);
            $this->jsonOutput(['error' => 500, 'error_msg'=>$msg]);
        }



//        $log = new Log();
//        $log->error($msg." | ".$this->getFile().":".$this->getLine());

//        $logFile = LOG_FILE_SYSTEM;
//        $var = fopen($logFile,"a");
//        fwrite($var, "[".date("Y-m-d H:i:s")."]"." [ERROR] ".$msg." | ".$this->getFile().":".$this->getLine()." \r");
//        fclose($var);

        exit;
    }

    public function unauthMessage() {
        $msg = "Exception";
        if (SYS_STAGE != 'PROD') { $msg .= ": {$this->getMessage()}";}

        http_response_code(401);

        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            require 'controllers/error_page.php';
            (new Error_page())->error401($msg);
        } else {
            $this->jsonOutput(['error' => 401, 'error_msg'=>$msg]);
        }

        exit;
    }

    public static function unhandledException($e) {
        $msg = "Unhandled Exception";

//        $trace = array_reverse(debug_backtrace());
//
//        foreach($trace as $item) {
//            echo '  ' . (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()' . "\n";
//        }
//        exit;

        if (SYS_STAGE != 'PROD') { $msg .= ": {$e->getMessage()}";}

        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            require 'controllers/error_page.php';
            (new Error_page())->error500($msg);
        } else {
            http_response_code(500);
            $output = ['error' => 1, 'error_msg'=>$msg];
            header('Content-type: application/json');
            $echo = json_encode($output, defined('JSON_UNESCAPED_UNICODE') ? JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES : JSON_PRETTY_PRINT);
            $echo = str_replace('\\/', '/', $echo);
            print $echo;

        }

        exit;

//        $log = new Log();
//        $log->error($msg);

//        $logFile = LOG_FILE_SYSTEM;
//        print_r($e);
//        $var = fopen($logFile,"a");
//        fwrite($var, "[".date("Y-m-d H:i:s")."]"." [ERROR] ".$msg." \r");
//        fclose($var);
    }

    private function jsonOutput($data) {
        $encoded = $this->utf8ize($data);
        $echo = json_encode($encoded, defined('JSON_UNESCAPED_UNICODE') ? JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES : JSON_PRETTY_PRINT);
        $echo = str_replace('\\/', '/', $echo);
        header('Content-type: application/json');
        print $echo;
    }

    private function utf8ize($d) {

        if (@mb_detect_encoding($d, 'UTF-8', true)) {
            return $d;
        }

        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize($v);
            }
        } else if (is_object($d)) {
            foreach ($d as $k => $v) {
                $d->$k = $this->utf8ize($v);
            }
        } else {
            return utf8_encode($d);
        }
        return $d;
    }

}