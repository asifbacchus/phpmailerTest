<?php
// import PHPMailer
set_include_path (__DIR__);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

// global var for debug logging
$debugOutput = '';

function sendEmail($timeout, $hostname, $usePort, $useEncryption, $username, $password, $recipient, $replyTo, $body, $subject) {
    try {
        // instantiate PHPMailer instance (true = enable exceptions)
        $result = [];
        $mail = new PHPMailer(true);

        // server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; // enable verbose output
        $mail->Debugoutput = function($str){
            $GLOBALS['debugOutput'] .= "$str<br>";
        };
        $mail->Timeout = $timeout;
        $mail->isSMTP();
        $mail->Host = $hostname;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->Port = $usePort;
        // encryption setting
        if ($useEncryption === 'ssl'){
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($useEncryption === 'starttls'){
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($useEncryption === 'none'){
            $mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;
        }

        // recipients
        $mail->setFrom($replyTo);
        $mail->addAddress($recipient);
        $mail->addReplyTo($replyTo);

        // content
        $mail->Subject = $subject;
        //$mail->msgHTML($body);
        //$mail->isHtml(true);
        $mail->isHtml(false);
        $mail->Body = $body;

        // send message
        if (!$mail->send()) {
            $result = [
                'result' => false,
                'message' => $mail->ErrorInfo,
                'debug' => $GLOBALS['debugOutput']
            ];
        } else {
            $result = [
                'result' => true,
                'debug' => $GLOBALS['debugOutput']
            ];
        }
    } catch (Exception $e) {
        $result = [
            'result' => false,
            'errorMessage' => $e->getMessage(),
            'debug' => $GLOBALS['debugOutput']
        ];
    } finally {
        return $result;
    }
}
?>