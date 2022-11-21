<?php

abstract class mownEngines {

    protected function __construct() {}

    protected function dbSafe($text) {
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
        $text = str_replace('0x57414954464F522044454C4159202730303A30303A313527', '', $text); // WAITFOR DELAY '00:00:15'
        return trim($text);
    }

    protected function respond($error = false,$msg = '',$errID = 200) {

        if ($error) {
            return [
                'http_code' => $errID,
                'error' => 1,
                'error_msg' => $msg,
            ];
        } else {
            return [
                'http_code' => $errID,
                'error' => 0,
                'msg' => $msg,
            ];
        }
    }

    /**
     * correctly json_decode a string handling errors
     *
     * @param $str string the string to decode
     * @return array the ooperation result
     */
    protected function mownJsonDecode($str) :array {
        $decoded = json_decode($str,true);
        $jsonError = json_last_error();

        //In some cases, this will happen.
        if(is_null($decoded) && $jsonError == JSON_ERROR_NONE){
            return $this->respond(1,'Could not decode JSON!',500);
        }

        //If an error exists.
        if($jsonError != JSON_ERROR_NONE){
            $error = 'Could not decode JSON! ';

            //Use a switch statement to figure out the exact error.
            switch($jsonError){
                case JSON_ERROR_DEPTH:
                    $error .= 'Maximum depth exceeded!';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $error .= 'Underflow or the modes mismatch!';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $error .= 'Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    $error .= 'Malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    $error .= 'Malformed UTF-8 characters found!';
                    break;
                default:
                    $error .= 'Unknown error!';
                    break;
            }
            return $this->respond(1,$error,500);
        } else {
            return $this->respond(0,$decoded);
        }
    }
}