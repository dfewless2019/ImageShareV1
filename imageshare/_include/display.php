<?php
//include '/_css/Master.css';
/*
File Name:          masterDisplay.php
Description:        master display libary
Original Author:    Fewless
Created Date:       4/13/2011
****************************************************
4/13/2011 built Fewless
 */
//include_once("../_include/sessionSecurity.php");

function DisplayHeader($displayName="",$role="")
{

    echo ("<div class='headerBar'>");
    echo ("<div id='headerPadding'>&nbsp;</div>");
    echo ("<div id='headerimage'><h1>IMAGE SHARE</h1></div>");
    echo ("<div id='Logstatus'><div id='innerLogStatus'><p>");
    if(!validBlank($displayName))
    {
        echo "<a href=''>".$displayName." " . (!validBlank($role)? $role : "") ."</a>&nbsp;&nbsp;/&nbsp;&nbsp;";

    }
    else
    {
        echo ("<a href='../registration/registration.php'>Register</a>&nbsp;&nbsp;/&nbsp;&nbsp;");
    }

    if(!validBlank($displayName))
    {
        echo "<a href='../login/logOut.php'>Logout</a>";
    }
    else
    {
        echo "<a href='../login/login.php'>Login</a>";
    }

    echo ("</div></p></div>");
    
    echo ("</div>");
    
    

}
function DisplayFooter()
{
   echo "<did class='foot'><div id='footContainer'>Copyright (c) ".(date("Y")." davidfewless.com all rights reserved")."</div></div>";
}
function Title($string)
{
    echo "<title>".$string."</title>\n";
    //java enabled check redirect other wise
    echo "<noscript><meta HTTP-EQUIV='REFRESH' content='0; url='../noScript.php'></noscript>";
    //favicon image
    echo "<link rel='icon' type='image/png' href='../_images/favicon.PNG'>";
    

}
function checkSessionRedirect($uname)
{
   $Sessionarray=array();
   $Sessionarray= explode('/', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);//header("Location: /LoginMain/Login.php");
   switch ($Sessionarray[1])
   {
       case 'payment':
           if($uname=="")
           {
            header('Location : ../index.php');
           }
         break;

       default:
           break;
   }

}
function required($str) {
        return "<font color='red'>".$str."</font>";
    }
?>
