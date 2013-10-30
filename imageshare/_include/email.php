<!--
File Name:          email.php
Description:        class to assist with sending emails from the site
Original Author:    Nathan Hart
Created Date:       4/14/2011
****************************************************
 -->

<?php
include_once('Mail/Mail.php');
include_once('Mail_Mime/mime.php');
//include (Mail.php);
    
    define("from","Bowl For Kids' Sake 2012 <bowlforkidssake2012@gmail.com>");
    define("host","ssl://smtp.gmail.com");
    define("port","465");
    define("username","bowlforkidssake2012@gmail.com");
    define("password","hart490!");

class email {
    private $to;
    private $subject;
    private $body;
    
    function __construct() {
    }
    
    function sendEmail($recipient, $subjct, $textBody, $htmlBody) {
        $this->to = $recipient;
        $this->subject = $subjct;

        $headers = array ('From' => from,
                'To' => $this->to,
                'Subject' => $this->subject);
        
        $crlf = "\n";
        $mime = new Mail_mime($crlf);
        $mime->setTXTBody($textBody);
        $mime->setHTMLBody($htmlBody);
        $this->body = $mime->get();
        $headers = $mime->headers($headers);
        $smtp = Mail::factory('smtp',
                array ('host' => host,
                'port' => port,
                'auth' => true,
                'username' => username,
                'password' => password));

        $mail = $smtp->send($this->to, $headers, $this->body);

        if (PEAR::isError($mail)) {
                //echo("<p>" . $mail->getMessage() . "</p>");
                return false;
        } else {
                //echo("<p>Message successfully sent!</p>");
                return true;
        }
    }
}

?>