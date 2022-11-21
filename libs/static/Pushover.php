<?php

/**
 * Class Pushover
 *
 *
 * needs:
 * // PUSHOVER
 * define('PUSHOVER_ENDPOINT','https://api.pushover.net/1/messages.json');
 * define('PUSHOVER_APP_TOKEN','aoidv4aaaaaaaaaadiaojo2pyt1c');
 * define('PUSHOVER_USER_KEY','ukvuoxidaaaaaaaadndt77verttv1x');
 */

class Pushover  {

    function __construct() {
    }

    public static function send($msg,$priority = null,$title = null,$log = null){
        if ($title === null || $title === false) {
            $title = "Noty";
        }

        if ($priority === null || $priority === false) {
            $priority = -1;
        }

        $options = array(
            "token" => PUSHOVER_APP_TOKEN,
            "user" => PUSHOVER_USER_KEY,
            "message" => $msg,
            "title" => SYS_NAME." - ".$title,
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
            CURLOPT_SAFE_UPLOAD => true, // only from PHP 5.5. Default true on 5.6
            CURLOPT_RETURNTRANSFER => true,
        ));

        $response =  curl_exec($ch);
        curl_close($ch);

        if ($log instanceof Log) {
            $log->pushHistory($msg,$title,$priority,$response);
        }

        return json_decode($response,true);

        /*
         * PRIORITY:
         * -2 to generate no notification/alert
         * -1 to always send as a quiet notification
         * 1 to display as high-priority and bypass the user's quiet hours
         * 2 to also require confirmation from the user, continuous request
         */

    }
}