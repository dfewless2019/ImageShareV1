<!DOCTYPE HTML>
<?php
include_once ("../_include/Globals.php");
//include_once ("../_include/SessionSecurity.php");
include_once ("../_include/database.php");
include_once ("../_include/validation.php");
include_once ("../_include/display.php");
?>

<!--
File Name:          Login.php
Description:        Login page
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
        <link type="text/css" rel="stylesheet" media="all" href="../css/login.css" />
        <?php Title("Login") ?>
    </head>
    <body>
        <?php 
            if (isset($_GET['page'])) {
                $passpage = $_GET['page'];
                $username=request('pass_username');
                $password=request('pass_password');
                //use this
                //header('Location: '.$passpage.'?pass_username='.$username.'&pass_password='.md5($password));
                //dev
                header('Location: '.$passpage.'?pass_username='.$username.'&pass_password='.$password);
            }
        ?>
        <!--empty-->
        <?php DisplayHeader(); ?>
        <div class="innerbody">
        <?php
        $username="";
        $errormessage="";
        $Error="";
        $type="";
        $username=request('username');
        $Error=request("Error");
        $type=request("type");

        if ($type=="new")
        {
            header("Location: ../registration/registration.php");
        }
        if(!validBlank($Error))
        {
            switch ($Error)
            {
                //Standard error
                case "Error":  $errormessage="*An email address must be entered*";    break;
                case "Error1": $errormessage="*Unable to authenticate user*";    break;
                case "Error2": $errormessage="*Password cannot be blank*";     break;
                default : $errormessage="*Email and password must be entered";
            }
        }
        echo "<div id='content'>";
        echo("<form action='login.php?page=loginauthorize.php' method='post'>");
               if(!validBlank($Error))
		{
		echo ($errormessage);
		}
		echo("<input class='rounded' tabindex='1' id='pass_username' name='pass_username' type='text' maxlength='255' value='Email'/><br/><br/>");
		echo("<input class='rounded' tabindex='2' id='pass_password' name='pass_password' type='text' maxlength='255' value='Password' /><br/><br/><br/>");
		echo("<div id='submitWrapper'><input id='submitbtn' tabindex='3' type='submit' value='Login' /></div><br/>");
		echo("Having trouble logging in?");
		echo("<a href='loginResetPassword.php'  >");
		        echo("Recover Password");
		echo("</a>");
		echo(" ?");
	echo("</form>");
        echo "</div>";
        DisplayFooter();
        ?>
            </div>
    </body>
</html>