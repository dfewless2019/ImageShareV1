<?php
include_once('Mail/Mail.php');
include_once('Mail_Mime/mime.php');
//include (Mail.php);
    
    define("dbFrom","Bowl For Kids' Sake 2012 <bowlforkidssake2012@gmail.com>");
    define("dbHost","ssl://smtp.gmail.com");
    define("dbPort","465");
    define("dbUsername","bowlforkidssake2012@gmail.com");
    define("dbPassword","hart490!");

class databaseEmail {
    private $to;
    private $subject;
    private $body;
    
    function __construct() {
    }
    
    function sendEmail($recipient, $subjct, $textBody, $htmlBody) {
        $this->to = $recipient;
        $this->subject = $subjct;

        $headers = array ('From' => dbFrom,
                'To' => $this->to,
                'Subject' => $this->subject);
        
        $crlf = "\n";
        $mime = new Mail_mime($crlf);
        $mime->setTXTBody($textBody);
        $mime->setHTMLBody($htmlBody);
        $this->body = $mime->get();
        $headers = $mime->headers($headers);
        $smtp = Mail::factory('smtp',
                array ('host' => dbHost,
                'port' => dbPort,
                'auth' => true,
                'username' => dbUsername,
                'password' => dbPassword));

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