<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
include("../_include/database.php");
include("../_include/validation.php");
include("../_include/display.php");

?>

<!--**********************************************************
File Name:          register.php
Description:        Second step of registration, validating account. Have form 
                    for password and verify.  Otherwise, inform the user that a 
                    verification email is en route and should be received within 
                    the next 12 hours.
Original Author:    Nathan Hart
Created Date:       04/15/2011
**********************************************************
04/18/2011 - Nathan  - Added conditional statements to direct traffic based on data passed in.  Conditions - no user sent, display email en route text.  User sent, but not in database, user not found.  User sent, but already verified, link to login page.  User sent, no password, display form to get password.  User sent, along with password, confirm password and user, verify account.
04/19/2011 - Nathan  - Adding form to collect password and submit for verification.
-->

<HTML xmlns="http://www.w3.org/1999/xhtml">
    <HEAD>
	<META http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" media="all" href="/_css/Master.css" />
        <?php Title("Verify Account");?>
        <script type="text/javascript" src="../_javascript/registrationValidation.js"></script>
        <script type="text/javascript">
            function validateVerify() {
                submit = true;
                var pwd = document.getElementById('txtPassword');
                if (pwd.value.length<=0) {
                    submit = false;
                }
                
                if (submit)
                    document.frmRegister.submit();
                else
                    document.getElementById('passwordCheck').innerHTML='<img src=\'../_images/led-icons/cross.png\' title=\'Enter Password\'/>';
            }
        </script>
    </HEAD>
    <BODY>
            <?php DisplayHeader(); ?>
            <?php
                    if (isset($_GET['user'])) {
                        //verify account
                        $userPK = request('user');
                        $username = "";
                        $db = new database();
                        $table = 'users';
                        $columnArray = array('email','verified');
                        $conditions = array(new condition('UserPK',$userPK));
                        $result = $db->getRow2($table, $columnArray, $conditions);
                        $verified = "";
                        while ($row = mysql_fetch_array($result)) {
                            $username = $row[0];
                            $verified = $row['verified'];
                            if ($verified=="" || $verified==0) 
                                $verified="-1";
                            break;
                        }
                        $db->close();
                        //user exists, user is already verified, verify user
                        if ($verified=="-1") {
                            //user if not yet verified
                            $valid = false;
                            if (isset($_POST['txtPassword'])) {
                                //update database
                                $password = request('txtPassword');
                                $db = new database();
                                $table = 'users';
                                $columnArray = array('UserPK');
                                $conditions = array(new condition('password',md5($password)),new condition('UserPK',$userPK));
                                $result = $db->getRow2($table, $columnArray, $conditions);
                                
                                while ($row = mysql_fetch_array($result)) {
                                    $valid = true;
                                    break;
                                }
                                
                                if ($valid) {
                                    //passwords match, verify account
                                    $table = 'users';
                                    $setColumn = array(new condition('verified',1));
                                    $conditions = array(new condition('UserPK',$userPK));
                                    $db->updateRow($table, $setColumn, $conditions);
                                    $db->close();
                                    //treat this as a login, redirect to "dashboard"
                                    
                                    header('Location: ../login/loginauthorize.php?pass_username='.$username.'&pass_password='.md5($password));
                                    //exit($username." ".$password);
                                }
                                else {
                                    $db->close();
                                    echo "The password provided does not match our records. <br />\n";
                                    //password does not match, do not verify account, refresh page
                                }
                                
                            }
                            //password is not set
                            if (!isset($_POST['txtPassword']) || !$valid) {
                                //display form to get password
                                echo "<form method='POST' id='frmRegister' name='frmRegister' action='register.php?user=$userPK'><br />\n";
                                echo "Enter Password to verify account <input name='txtPassword' id='txtPassword' type='password' onKeyUp=\"validateField('txtVerify','spnPassword')\" />&nbsp;<span id='spnPassword'></span><br />\n";
                                echo "<input type='button' name='btnSubmit' id='btnSubmit' onclick=\"validateVerify();\" value='Verify Your Account!' /><br />\n";
                                //submit form to same page, keep user in the url query and pass password as part of the post
                            }
                        }
                        else if ($verified=="") {
                            echo "Could not find the account specified.  If you followed a link, please confirm that it is entered correctly.";
                            echo "If you believe you have received this message in error, please contact support. <br />\n";
                            //user does not exist, email or call for support
                        }
                        //user already verified
                        else {
                            echo "This account has already been verified.  To access your account, please log in.";
                            //tell user the account is already verified
                            //link to the login page
                        }
                    }
                    //user is not set
                    else {
                        echo "Thank you for registering for 2012 Bowl for Kids' Sake!  We have sent you an email to verify your account.  Please 
                            follow the link provided to complete your registration process.";
                        //tell user that a verification email is en route
                    }
                    
                    DisplayFooter();
            ?>
            </div>
    </BODY>
</HTML>