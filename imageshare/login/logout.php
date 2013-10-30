<!--
File Name:          Logout.php
Description:        Login page
Original Author:    Fewless
Created Date:       4/12/2011
****************************************************
4/12/2011 built Fewless
 -->
<?php include("../_include/SessionSecurity.php");
$_SESSION['displayName']="";
$_SESSION['id']="";
$_SESSION['roleID']="";
$_SESSION['role']="";
header("Location: ../index.php")
?>
