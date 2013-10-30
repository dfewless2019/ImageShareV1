<!DOCTYPE HTML >
<?php
//include_once ("../_include/SessionSecurity.php");
include_once ("../_include/Globals.php");
include_once ("../_include/database.php");
include_once ("../_include/validation.php");
include_once ("../_include/display.php");
//include_once("../_include/email.php");
include_once("../_include/common.php");
?>
<!--
File Name:          Login_reset.php
Description:        Login reset password or user name page
Original Author:    Fewless
Created Date:       4/12/2011
****************************************************
4/12/2011 built Fewless
 -->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href='http://fonts.googleapis.com/css?family=Gabriela' rel='stylesheet' type='text/css'>
        <link type="text/css" rel="stylesheet" media="all" href="../css/Main.css" />
        <link type="text/css" rel="stylesheet" media="all" href="../css/loginResetPassWord.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="../scripts/loginResetPassword.js"></script>
        <script src="../scripts/globals.js"></script>
        <?php Title("Password Reset") ?>
    </head>
    <body>
        <?php DisplayHeader(); 
        
        $passpage="";
        $passpage=request('Page');
        $passEmail;
        $passEmail=trim(request("txtEmail"));

        if($passpage=="update")
        {/*
            if(!validBlank($passEmail))
            {
                $passCode=common::randomPassword();
                $db = new database();
                $columns = array('userPK','email');
                $conditions = array(new condition('email',$passEmail));
                $emails = $db->getRow2('users', $columns, $conditions);

                while ($row = mysql_fetch_array($emails))
                {
                   // echo "some thing ";

                    $mail = new email();
                    $emailSubject = "2012 Bowl For Kids' Sake - Password Reset!";
                    $emailHTMLBody = "<html><body>
                        Thank you for using our online registration.<p>
                        <p>
                        Your New password is ".$passCode.".  This can be changed after you login. </body></html>
                        Thank you for using our online registration.
                        <a href='".baseURL."login/Login.php'>Click Here to log in</a>";
                        
                        $emailTextBody = "
                        Thank you for using our online registration.
                        Your New password is ".$passCode.".  This can be changed after you login.
                        ".baseURL."login/Login.php ";
                                    
                        $success = $mail->sendEmail($passEmail,$emailSubject,$emailTextBody,$emailHTMLBody);
                        $table="users";
                        
                        $setColumn=array(new condition('password', md5($passCode)));

                        $conditions = array(new condition('email', $passEmail));
                        $db->updateRow($table,$setColumn,$conditions);
                        $db->close();
                        if ($success)
                        {
                            header('Location: ../login/Login.php');
                        }
                        else
                        {

                        //echo "Error sending email, creating file...";
                        $myFile = "../Unsent Emails/".$row['userPK'].".txt";
                        if (!$fh = fopen($myFile, 'w')) {
                             echo "Cannot open file ($myFile)";
                        }
                        else
                            {
                            $stringData = "To:".$passEmail."\n";
                            fwrite($fh, $stringData);
                            $stringData = $emailSubject."\n";
                            fwrite($fh, $stringData);
                            $stringData = $emailTextBody."\n";
                            fwrite($fh, $stringData);
                            fclose($fh);

                            header('Location: ../login/Login.php');
                            }


                        }
                }
                echo "<div> The email that you entered is not linked to a registered user Please try again.<br>
                <a href='../login/loginResetPassword.php'>Enter a different Email</a>
                <div>";
                
               }*/
         }
        else
        {
        echo ("<div id='content'>");
            echo ("<form name='resetfrm' id='resetfrm' action='loginResetPassword.php?Page=update' method='post' style='margin:0px;padding:0px;'>");
                echo ("<p>Please enter your email address below login instructions will be sent to you.</p>");
                echo ("<input class='rounded' id='txtEmail' name='txtEmail' type='text' class='standard' style='width:370px;' maxlength='60' value='Email'>");
                echo ("<span id='spnEmail' name='spnEmail'></span></p>");
                echo ("<p>If require additional assistance,please email the web <a href='mailTo:dfewless2019@gmail.com'>Admin</a></p>");
                echo "<div id='submitWrapper'><input type='button' id='submitbtn' value='Get New Password' /></div>";    
            echo ("</form>");
        echo ("</div>");
        }
        DisplayFooter();
        ?>
    </body>
</html>