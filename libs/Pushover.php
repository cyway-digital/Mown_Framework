<?php

class Pushover {

    function __construct() {
    }

    public static function send($msg,$priority = null,$title = null,$monospace = 0){
        if ($title === null || $title === false) {
            $title = "Notifica";
        }

        if ($priority === null || $priority === false) {
            $priority = 0;
        }

        if (defined('SYS_STAGE') && SYS_STAGE != 'PROD') {
            $title = SYS_NAME." [".SYS_STAGE."] - ".$title;
        } else {
            $title = SYS_NAME." - ".$title;
        }

        $options = array(
            "token" => PUSHOVER_APP_TOKEN,
            "user" => PUSHOVER_USER_KEY,
            "message" => $msg,
            "monospace" => $monospace,
            "title" => $title,
            "priority" => $priority,
            "timestamp" => time()
        );

        if ($priority == 2) {
            $options["retry"] = 180;
            $options["expire"] = 3600;
        }

        curl_setopt_array($ch = curl_init(), array(
            CURLOPT_URL => PUSHOVER_ENDPOINT,
            CURLOPT_POSTFIELDS => $options,
            CURLOPT_RETURNTRANSFER => true,
        ));

        $response =  curl_exec($ch);
        curl_close($ch);

        return json_decode($response,true);

        /*
         * PRIORITY:
         * -2 to generate no notification/alert
         * -1 to always send as a quiet notification
         * 0 normal or -1 if quie thours
         * 1 to display as high-priority and bypass the user's quiet hours
         * 2 to also require confirmation from the user, continuous request
         */

        /*
        *   $push = Pushover::send("MAIL NON INVIATA: ".$this->_mail->ErrorInfo. " - ". print_r($this->_mailSettings,1),1);
            if ($push['status'] != 1){
                mail(SYS_MAIL, "[".SYS_NAME."] ERRORE MAILER", $this->_mail->ErrorInfo. " - ". print_r($this->_mailSettings,1));
            }
         */

    }

}