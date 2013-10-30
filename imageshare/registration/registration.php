<!DOCTYPE HTML >
<html xmlns='http://www.w3.org/1999/xhtml'>

<?php
    //include_once ("../_include/SessionSecurity.php");
    include_once ("../_include/Globals.php");
    include_once ("../_include/database.php");
    include_once ("../_include/validation.php");
    include_once ("../_include/display.php");
    //include_once ("../_include/email.php");
    //include_once ("../_include/common.php");
    require_once('../recaptcha-php-1.11/recaptchalib.php');
?>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<link href='http://fonts.googleapis.com/css?family=Gabriela' rel='stylesheet' type='text/css'>
<link type="text/css" rel="stylesheet" media="all" href="../css/Main.css" />
<link type="text/css" rel="stylesheet" media="all" href="../css/registration.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="../scripts/jQuery.caret.js"></script>
<script src="../scripts/registration.js"></script>
<script src="../scripts/globals.js"></script>
 <script type="text/javascript">
 var RecaptchaOptions = {
    theme : 'clean'
 };
 </script>
<?php
    $style = (isset($_GET['verified']))?"My Account":"Register";
    Title($style); 
?>
</head>
<body>
<?php 
//validation (isset(page)
DisplayHeader();
if (isset($_GET['page'])) {
} else{   
 //DisplayHeader($displayName,$role); 
    
    echo "<div id='content'>";
        echo "<form name='regForm' id='regForm' action='VerifyRegistration.php' method='post'>";
           echo "<input type='text' class='rounded' id='fname' maxlength='255' title='Enter First Name' initalizedto='First Name' value='First Name'/>";    
           echo "<input type='text' class='rounded' id='lName' maxlength='255' title='Enter Last Name' initalizedto='Last Name' value='Last Name'/><br/>";
           echo "<input type='text' class='rounded' id='userName' maxlength='255' title='User Name' initalizedto='User Name' value='User Name'/><br/>";
           echo "<input type='text' class='rounded' id='Email' maxlength='255' title='Enter Email' initalizedto='Email' value='Email'/><br/>";
           echo "<input type='text' class='rounded' id='password1' maxlength='255' title='Enter Password' initalizedto='Password' value='Password' /><br/>";
           echo "<input type='text' class='rounded' id='password2' maxlength='255' title='Renter Password ' initalizedto='Confirm Password' value='Confirm Password' /><span id='passWordConfirm'></span><br/>";
           echo "<span id='fineprint'>Password must be at least 6 characters</span>";
           /*echo "<h1>Tearms and Conditions</h1>";
           echo "<div id='terms'>";
                echo "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ornare erat est, sed elementum nisl. Vestibulum fermentum fringilla facilisis. Fusce sit amet nisi sit amet diam imperdiet iaculis. Sed dignissim imperdiet libero, ut molestie quam viverra quis. Sed feugiat, nibh ut posuere ullamcorper, elit urna accumsan felis, ac sodales odio justo quis quam. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In aliquam libero eget quam aliquam dapibus. Cras tincidunt mollis pharetra. Nunc at nisl massa. Sed tincidunt, justo id malesuada hendrerit, est sapien consequat mi, et venenatis velit mi id sapien. Sed tincidunt, sapien quis dapibus tempor, massa orci euismod nisl, sed lacinia dui tortor eget neque. Aliquam augue turpis, iaculis vel faucibus iaculis, sagittis ullamcorper neque.";
                echo "Integer at eleifend nisl. Etiam volutpat nisi at libero accumsan ultrices. Mauris dictum hendrerit sollicitudin. Suspendisse potenti. Mauris lacinia facilisis elit vitae luctus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla dui mi, sollicitudin eget luctus vel, vestibulum cursus massa. Curabitur a nunc purus, quis eleifend nibh. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aenean pharetra ante nec elit adipiscing ut egestas quam imperdiet. Mauris a erat libero, dictum vehicula velit. In iaculis magna nec erat lacinia non sagittis tortor auctor. Fusce vestibulum laoreet lacinia. Curabitur a nulla velit, quis euismod nunc.";
                echo "Suspendisse potenti. Phasellus congue quam id nunc lacinia pulvinar. Nam facilisis dolor commodo massa fermentum ornare. Sed tristique justo ligula, nec ultrices dui. Nulla rutrum dui justo, vitae consectetur metus. Cras volutpat eros vitae ipsum viverra eget faucibus mi luctus. Nam venenatis tempus urna a imperdiet. Nulla ultricies diam quis enim fringilla eu ultrices turpis luctus. Suspendisse egestas consectetur lacinia. Aenean lacinia aliquam aliquet. Donec fermentum odio eu justo tempor viverra.";
                echo "Proin quam eros, suscipit euismod vestibulum in, ultricies nec lacus. Sed vel libero nibh. Nulla euismod, magna vel semper cursus, nibh leo elementum magna, non fermentum nunc augue quis dolor. Donec odio lacus, dictum ac mollis feugiat, congue id turpis. Aliquam imperdiet feugiat ultricies. Proin tempor aliquet tempus. Ut dapibus orci vitae justo volutpat ut egestas metus lacinia. Nam consequat, lacus eu aliquam blandit, orci sem lobortis risus, vel eleifend eros arcu ac sapien.";
                echo "Duis sit amet purus risus, facilisis euismod lacus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin at neque lorem. Praesent pharetra hendrerit sapien, non molestie tortor iaculis non. Mauris metus erat, viverra nec ornare ac, interdum a tortor. Vestibulum viverra faucibus orci sit amet rhoncus. Cras a metus in nulla suscipit pretium. Mauris in diam diam, non malesuada mauris. Nulla facilisi. Pellentesque et arcu neque. Etiam ut quam tristique mauris laoreet fermentum vel suscipit sem. Mauris eu sollicitudin libero. Vestibulum arcu purus, sodales et bibendum at, cursus congue arcu. Vivamus sit amet urna nibh. Sed et erat nibh, eget posuere elit.";
           echo "</div>";*/
           echo"<div id='captchaholder'>";
           $publickey = "6Lc8A98SAAAAAF5DoNlPi-lEeCukp9G9WO5JM_CV"; // you got this from the signup page
           echo recaptcha_get_html($publickey);
           echo "</div>";
           echo "<p>By clicking Sign up, you agree to our <a href=''>Terms and Conditions</a></p>";
           echo "<div id='submitWrapper'><input type='button' id='submitbtn' value='Sign up' /></div>";
           echo "<div id='ErrorDiv'></div>";
           echo "</form>";
    echo "</div>";
}
 DisplayFooter();?>
    
    </body>
</html>