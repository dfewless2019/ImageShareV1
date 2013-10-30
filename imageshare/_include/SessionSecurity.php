<?php
/*
File Name:          SessionSecurity
Description:        Make sure the user is loged in
Original Author:    Fewless
Created Date:       4/12/2011
****************************************************
4/12/2011 built Fewless - page is needed after user login on all pages. 
 */
$username="";
$sessionarray=array();
session_start();
//checks to make sure that the session is set
if (isset($_SESSION['displayName'])) {$displayName=$_SESSION["displayName"];} else {$displayName="";}
if (isset($_SESSION['id'])) {$id=$_SESSION["id"];} else {$id="";}
if (isset($_SESSION['role'])) {$role=$_SESSION["role"];} else {$role="";}
// if any session is not set then it will take you back to the log inpage.
if ((strlen($displayName) == 0) || (strlen($id) == 0)||(strlen($role) == 0))
{
  
    $sessionarray=explode('/',  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    //print_r($sessionarray);
    if(strlen($sessionarray[1])>0)
    {
        switch ($sessionarray[1])
        {
            case "admin":
                   header("Location: ../login/login.php");
                break;
            case "reports":
                   header("Location: ../login/login.php");
                break;
            case "teams":
                   header("Location: ../login/login.php");
                break;

            default:
                break;
        }
    }
}
else
{
}
?>
