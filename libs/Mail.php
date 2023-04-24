<?php


class Mail
{

    private $_log = null;

    function __construct($logClass = false)
    {
        if ($logClass instanceof Log) {
            $this->_log = $logClass;
        }

        // $this->_mail = new PHPMailer;
        // $this->_mail->CharSet = 'UTF-8';

        // $this->_mailSettings = array(
        //     'SMTP_MAIL_ENABLE' => SMTP_MAIL_ENABLE,
        //     'SMTP_USER' => SMTP_USER,
        //     'SMTP_PASSWORD' => SMTP_PASSWORD,
        //     'SMTP_SERVER' => SMTP_SERVER,
        //     'SMTP_PORT' => SMTP_PORT,
        //     'SMTP_AUTH' => SMTP_AUTH,
        //     'SMTP_SECURE' => SMTP_SECURE,
        //     'SYS_MAIL' => SYS_MAIL
        // );

        // $c = new Crypt();
        // $this->_mailSettings['SMTP_PASSWORD'] = $c->decrypt($this->_mailSettings['SMTP_PASSWORD']);

    }

    function sendWelcome($recipient, $name, $password)
    {
        $subject = "Benvenuto in " . SYS_NAME;

        //Plain message
        $altBody = file_get_contents('views/templates/email/plain/welcomeUser.tpl');
        $altBody = str_replace("{:URL:}", URL . "login", $altBody);
        $altBody = str_replace("{:NAME:}", $name, $altBody);
        $altBody = str_replace("{:PWD:}", $password, $altBody);
        $altBody = str_replace("{:EMAIL:}", $recipient, $altBody);
        $altBody = str_replace("{:SYS_NAME:}", SYS_NAME, $altBody);

        return $this->sendMailgun($name,$recipient,$subject,$altBody);
    }

    function sendPwdReset($recipient, $name, $username, $password)
    {
        $subject = "La tua nuova password per " . SYS_NAME;

        //Plain message
        $altBody = file_get_contents('views/templates/email/plain/pwdReset.tpl');
        $altBody = str_replace("{:URL:}", URL . "login", $altBody);
        $altBody = str_replace("{:USERNAME:}", $username, $altBody);
        $altBody = str_replace("{:NAME:}", $name, $altBody);
        $altBody = str_replace("{:PWD:}", $password, $altBody);
        $altBody = str_replace("{:EMAIL:}", $recipient, $altBody);
        $altBody = str_replace("{:SYS_NAME:}", SYS_NAME, $altBody);

        return $this->sendMailgun($name,$recipient,$subject,$altBody);
    }

    function sendMailgun($toName, $toMail, $subject, $bodyPlain, $bodyHTML = false)
    {

        $data = [
            'from'=> 'HelpDesk <helpdesk@example>',
            'to'=> $toName.' <'.$toMail.'>',
            'subject'=> $subject,
            'html'=> $bodyHTML,
            'text'=> $bodyPlain,
        ];

        // TODO if dev remove TO

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, MAILGUN_URL.'/messages');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, "api:".MAILGUN_APIKEY);

        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        curl_close($ch);

        $return = [
            'success' => 1,
            'status' => $status_code,
            'content' => $result
        ];

        if ($status_code != 200) {
            $return['status'] = 0;
        } else {
            $this->_log->mailHistory($subject,$bodyPlain,$toMail);
        }

        return $return;

    }

}
