<?php

class Controller {

    function __construct(){

        if (Session::check() && Session::get('role') !== 'USER') {
            if (time() - Session::get('lastAct') > ADMIN_SESSION_MAX_INACTIVITY) {
                Session::destroy();
                header('location: '.URL.'login');
                exit;
            }

            Session::set('lastAct',time());
        }
    }

    public function loadComponents($name, $modelPath = 'models/') {
        //called by Routes Class
        $path = $modelPath . $name.'_model.php';
        
        if (file_exists($path)) {
            require $path;
            $modelName = $name . '_Model';
            try {
                $this->model = new $modelName();
            } catch (Exception $e) {
                require 'controllers/error_page.php';
                $msg= $e->getMessage();
                (new Error_page())->error500($msg);
                $logFile = LOG_FILE_SYSTEM;
                $var = fopen($logFile,"a");
                fwrite($var, "[".date("Y-m-d H:i:s")."]"." [ERROR] ".$msg."\r");
                fclose($var);
                exit(1);
            }
        }

        // Here you've $this->model->db

        try {
            $this->view = new View();
            $this->cache = new fileCache();
            $this->log = new Log(LOG_ROTATE_DAYS, $this->cache);
            $this->mail = new Mail($this->log);
            $this->dbLogs = new dbLogsEngine($this->model->db);
        } catch (mownException $e) {
            //TODO use throw new to invoke here?
            $e->errorMessage();
        }
    }

    public function loadModel($name, $modelPath = 'models/')
    {
        $path = $modelPath . $name.'_model.php';

        if (file_exists($path)) {
            require $path;
            $modelName = $name . '_Model';
            $this->model = new $modelName();
        }

    }

    public function handleRequest($method, $fields = []) {
        //TODO unset not present vars?

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
            $this->jsonOutputError('Method not allowed ('.$_SERVER['REQUEST_METHOD'].')',405);
        }

        if (!is_array($fields)) {
            $this->jsonOutputError('Cannot validate POST fields. Send an array.',412);
        }

        // check token
        if (!$this->checkToken()) {
            $this->jsonOutputError('Wrong Mown Token',401);
        }

        $requestData = json_decode(file_get_contents('php://input'), true);
        $method = strtoupper($method);

        if (!$requestData || empty($requestData)) {
            switch ($method) {
                case 'POST':
                    $requestData = $_POST;
                    break;
                case 'GET':
                    $requestData = $_GET;
                    break;
                default:
                    $this->jsonOutputError('You need to teach me how to handle method '.$method,412);
                    break;
            }
        }

        if (!empty($fields)) {
            if ($requestData && !empty($requestData)) {
                $filteredData = [];

                foreach ($fields as $field => $type) {
                    switch ($type) {
                        case 'int':
                            $filter = FILTER_SANITIZE_NUMBER_INT;
                            $flags=null;
                            break;
                        case 'float':
                            $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                            $flags=null;
                            break;
                        case 'string':
                        case 'string_e': // String that CAN be empty
                            $filter = FILTER_SANITIZE_STRING;
                            $flags=null;
                            break;
                        case 'html':
                            $filter = FILTER_UNSAFE_RAW; //TODO
                            $flags = null;
                            break;
                        case 'json':
                            $filter = 'json';
                            break;
                        default:
                            $this->jsonOutputError('Unknown var type '.$type,412);
                    }

                    if ($type === 'string_e') {
                        if (isset($requestData[$field])) {
                            $filteredData[$field] = filter_var(trim($requestData[$field]),$filter,$flags);
                        } else {
                            $this->jsonOutputError('Cannot find all needed Request fields. ('.$field.',1)',412);
                        }
                    } elseif ($filter !== 'json') {
                        if (isset($requestData[$field]) && $requestData[$field] !== '') {
                            $filteredData[$field] = filter_var(trim($requestData[$field]),$filter,$flags);
                        } else {
                            $this->jsonOutputError('Cannot find all needed Request fields. ('.$field.',2)',412);
                        }
                    } else {
                        if (is_array($requestData[$field])) {
                            $filteredData[$field] = $requestData[$field];
                        } else {
                            $filteredData[$field] = json_decode($requestData[$field],true);

                            if (json_last_error() != JSON_ERROR_NONE) {
                                $this->jsonOutputError($field." is not a valid JSON: ".json_last_error_msg(),412);
                            }
                        }
                    }
                }

                return $filteredData;
            } else {
                $this->jsonOutputError('Cannot find Request fields.',412);
            }
        }
    }

    function jsonOutput($data) {
        $encoded = $this->utf8ize($data);
//        $etag = md5( var_export($encoded,1) );

        $echo = json_encode($encoded, defined('JSON_UNESCAPED_UNICODE') ? JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES : JSON_PRETTY_PRINT);
        $echo = str_replace('\\/', '/', $echo);

        header('Content-type: application/json');

//        header("Etag: $etag");

//        ob_start('ob_gzhandler');
        print $echo;
//        ob_end_flush();

        exit;
    }

    function jsonOutputError($msg,$responseCode = 412) {
        http_response_code($responseCode);
        $this->jsonOutput(['error' => 1, 'error_msg' => $msg]);
    }

    protected function utf8ize($d) {
        if (is_string($d) && @mb_detect_encoding($d, 'UTF-8', true)) {
            return $d;
        }

        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize($v);
            }
        } elseif (is_object($d)) {
            foreach ($d as $k => $v) {
                $d->$k = $this->utf8ize($v);
            }
        } elseif (is_string($d)) {
            return utf8_encode($d ?? '');
        }
        return $d;
    }

    // checks if provided token is valid TODO: handle redirect if request coming from AJAX
    private function checkToken() {
        Session::init();

        if (isset($_SERVER['HTTP_X-MOWN-TOKEN']) && $_SERVER['HTTP_X-MOWN-TOKEN'] != $_SESSION['token']) {
            return false;
        }

        return true;
    }

}