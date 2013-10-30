<?php
    include_once ("../_include/SessionSecurity.php");
    include_once ("../_include/database.php");
    include_once ("../_include/validation.php");
    include_once ("../_include/display.php");
    //include_once ("../_include/email.php");
    include_once ("../_include/common.php");
    include_once('../recaptcha-php-1.11/recaptchalib.php');
    $privatekey = "6Lc8A98SAAAAAPxOPmYqynJpUuKHASvy8tK66gQu";
    $resp = recaptcha_check_answer ($privatekey,
    $_SERVER["REMOTE_ADDR"],
    $_POST["recaptcha_challenge_field"],
    $_POST["recaptcha_response_field"]);
    $fname = request("fname");
    $lName = request("lname");
    $userName = request("userName");
    $Email = request("Email");
    $password1 = request("password1");
    $password2 = request("password2");
  
  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
    die ("The reCAPTCHA was not entered correctly.  finish redirect.");
  } else {
      if(!validBlank($fname) && !validBlank($lName) && !validBlank($userName) && !validBlank($email) && !validBlank($password1) && !validBlank($password2) && ($password1== $password2))
      {          
      // insert in to data base.
         echo "finish insert into database and redirect to verification"; 
          
      }
      
  } 