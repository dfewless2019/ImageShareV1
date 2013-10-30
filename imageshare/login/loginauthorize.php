<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
include_once ("../_include/Globals.php");
include_once ("../_include/SessionSecurity.php");
include_once ("../_include/database.php");
include_once ("../_include/validation.php");
include_once ("../_include/display.php");
?>
<!--
File Name:          Login_authorize.php
Description:        Login authorize
Original Author:    Fewless
Created Date:       4/12/2011
****************************************************
4/12/2011 built Fewless
 -->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $username="";
        $password="";
        //gets passed in user name and password
        $username=request("pass_username");
        $password=request("pass_password");
        //makes sure they are not blank
        //echo $username.'/'.$password;
        //exit();
        if((!validBlank($username))&&(!validBlank($password)))
        {
          echo "finish database check";
          //a bit of database protection remove html tags, single quotes and such
          $username=encodeStripTags($username);
          $username=encodeAddSQLQuotes($username);
          $username=encodeStringHTML($username);

          $password=encodeStripTags($password);
          $password=encodeAddSQLQuotes($password);
          $password=encodeStringHTML($password);
           

          //Data base check here for user name, fullname id, role
          //sql pull to find user  and load information into session
           //if user found for if statement below
           //$Database=1;
          /*$db = new database();
            $q = "SELECT  `userID` ,  `userName` ,  `passWord` ,  `roleFk` ,  `lName` ,  `fName` ".
            "FROM  `_user` ".
            "WHERE userName =  '".$username."'".
            "AND  'passWord' =  '".$password."'";
            //$q = "SELECT  `userID` ,  `userName` ,  `passWord` , `roleFk` FROM '_user' WHERE userName = AND 'passWord' = ;";
            //echo $q;
            $result = $db->getquery($q);
           if($result){
                while ($row = mysql_fetch_array($result)) {
                  $userPK = $row['userID'];
                    $_SESSION['displayName']=$row['userName'];
                    $_SESSION['id']=$row['userID'];
                    $role;
                    
                    if(!validBlank($row['roleFk'])){
                        $role=$row['roleFk'];
                        $q = "SELECT  `role` ".
                        "FROM  `_role`". 
                        "WHERE  `roleId` =  '".$role."'";
                        $result = $db->getquery($q);
                        if($result){
                            while ($row = mysql_fetch_array($result)) {
                                $_SESSION['roleID']=$role;
                                $_SESSION['role']=$row['role'];
                            }
                        }
                    }else{
                        $_SESSION['roleID']="0";
                        $_SESSION['role']="user";
                    }
                }
           
            $db->close();
            if (isset($_SESSION['displayName']) && isset($_SESSION['id'])){
              // echo  $_SESSION['displayName'];
              // echo $_SESSION['id'];
              // echo $_SESSION['role'];
//echo $_SESSION['displayName'];                
                header("Location: ../dashboard/MyDashboard.php");
            }else{
//echo "not set";               
                header("Location: ../Login/Login.php?Error=Error1");
            }
            // if not valid blank 
            // set sessoin variables, 
            // send to dashboard. 
            
        }*/
        }

           else
           {  //failed authication user not found in data base redirect to login and displays error
               header("Location: ../Login/Login.php?Error=Error1");
           }


        ?>
    </body>
</html>