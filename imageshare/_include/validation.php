<!--
Document Name(File Name): masterValidation.php
Description: Validation functions
Original Author: David Fewless
Created Date: 4/1/2011
****************************************************
04/08/2011 David Fewless - Create page
-->
<?php

/* Master  Validation class*/

#---------------------------------------------------
#master function
#---------------------------------------------------
//Request passed Variables in in a string or returns ""
function request($str) {
    if(isset ($_REQUEST[$str])){
        return trim($_REQUEST[$str]);
    }
 else{
        return "";
    }
}

// replace single quotes with double and replace percent for wild card with text reperesentation
 function encodeAddSQLQuotes($str) {
    $str=str_replace("'", "''", $str);
    $str=str_replace("%", "[%]", $str);
    $str=str_replace("\\","", $str);
    return $str;
}

//strip sctipt tags <> from string to protect against injections
function encodeStripTags($str) {
    return strip_tags($str);
}

function databaseClean($str) {
    $str = encodeAddSQLQuotes($str);
    $str = encodeStripTags($str);
    return $str;
}

// converts all html tags to strings (plain text) and returns
function encodeHTMLString($str) {
    return html_entity_decode($str);
}

//converts special characters to a string
#$new = htmlspecialchars("<a href='test'>Test</a>", ENT_QUOTES);
#echo $new; // &lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;
function encodeStringHTML($str) {
        return htmlentities($str, ENT_QUOTES);
}

# **************************************************************************
#  CLEANING
# **************************************************************************

//strips out all characters ecsept numbers "%$." are all removed
function cleanInteger($str) {
        $str=trim($str);
        $str=preg_replace('/\D/', '', $str);
        return $str;
}



# **************************************************************************
#  VALIDATION return true or false
# **************************************************************************
//
//validate is a string is a string and not html tags does not check if strin is blank
// use in combination with validBlank
function validString($str) {
        if ((strlen($str))!=(strlen(strip_tags($str)))) {
                return false;
        }else{
                return true;
        }
}
// checks to make sure a string is not a blank. checks for null and string len must be greater than 0
// usfull
//$pasVar=request('var')
//if(validBlank($pasVar){ fail }else {pass}
function validBlank($str) {

        if (is_null($str)) return true;

        if ((!is_array($str)) && (!is_object($str))) {
                $str=trim($str);
        }

        if (strlen($str)==0) return true;
        if (is_int($str)) {
                if ($str==0) return false;
        }
        if (empty($str)) return true;

        return false;
}

//validate a string using the two valid string functions together.
function validateString($str) {
    if (!validBlank($str)) {
        if (validString($str))
            return true;
    }
    return false;
}

//are two strings equal?
function matchString($str1,$str2) {
    return ($str1==$str2);
}

//validates string int makes sure the string is an int
function validInteger($str) {

        if (validBlank($str)) return false;

        if (preg_match("/^(-+)?\d+$/", $str)) {
                return true;
        } else {
                return false;

        }

}
//validates string floats makles sure the string is a float
function validFloat($str) {
    if (validBlank($str)) return false;

    // The "i" after the pattern delimiter indicates a case-insensitive search
    if (preg_match("/^[-+]?\d*\.?\d*$/i", $str)) {
            return true;
    } else {
            return false;
    }
}
// SQL server function ints can be larger than 2147483647
function validSQLInteger($str) {
    if (validBlank($str)) return false;

    if (validInteger($str)) {
            if (($str<=2147483647) && ($str>=-2147483647)) {
                    return true;
            } else {
                    return false;
            }
    }else{
            return false;
    }
}
//validate email address could use some more functionality but i think it is good.
function validEmail($str) {
if (validBlank($str)) return false;
// The "i" after the pattern delimiter indicates a case-insensitive search
    if (preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i", $str))
    {
        if (strpos($str, ".."))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
    return false;
    }
}

function validateLength($str,$len) {
    if (strlen($str)>=$len) return true;
    return false;
}

?>



