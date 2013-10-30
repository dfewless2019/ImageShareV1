<?php
include_once "connectionString.php";
//include_once "databaseEmail.php";
//require_once '/Classes/PHPExcel.php';

//define("baseURL","http://localhost:8080/");
//include_once("../_include/email.php");
//include_once("../_include/common.php");

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of database
 *
 * @author Nathan
 */
class condition {
    public $column;
    public $value;
    public $condition;



    /*
    function __construct() {
        $this->column="";
        $this->value="";
        $this->condition="=";
    }
    */
    function __construct($col,$val) {
        $this->column = $col;
        $this->value = $val;
        $this->condition="=";

    }
    /*
    function setColumnValue($col,$val) {
        $this->column = $col;
        $this->value = $val;
    }
    
    function setColumn($col) {
        $this->column = $col;
    }
    
    function setValue($val) {
        $this->value = $val;
    }
    */
    function setCondition($con) {
        $this->condition = $con;
    }
}

class database {

    private $conn;

/*
    define("DB_SERVER","localhost:3306");
    define("DB_USER","php");
    define("DB_PASS","vBG7mw8zL4hDjPX3");
    define("DB_NAME","CS490");
    define("TBL_USERS","users");
*/
    function __construct() {
        $db = new db();
        $this->conn=$db->conn;
    }
    function close() {
        mysql_close($this->conn);
    }
    function startTransaction() {
        $q = "START TRANSACTION;";
        return mysql_query($q,$this->conn);
    }   
    function rollback() {
        $q = "ROLLBACK;";
        return mysql_query($q,$this->conn);
    }    
    function commit() {
        $q = "COMMIT;";
        return mysql_query($q,$this->conn);
    }   
    
    //put your code here
    public static function randomPassword() {
        $n = rand(10e16, 10e23);
        return base_convert($n, 10, 36);
    }   
    //use this function when you need to carry over the same ID across multiple queries
    function UUID() {
        $q = "SELECT UUID() AS GUID;";
        $guid = mysql_query($q,$this->conn);
        $uuid = "";
        while ($row = mysql_fetch_array($guid)) {
            $uuid = $row['GUID'];
            break;
        }
        return $uuid;
    }
    function getDate() {
        $q = "SELECT NOW() AS getDate;";
        $result = mysql_query($q,$this->conn);
        $now;
        while ($row = mysql_fetch_array($result)) {
            $now = $row['getDate'];
            break;
        }
        return $now;
    }   
    function getRole($roleName) {
        $rolePK = '';
        $columnArray = array('RolePK');
        $table = 'siteroles';
        $conditions = array(new condition('role',$roleName));
        $result = $this->getRow2($table, $columnArray, $conditions);
        while ($row = mysql_fetch_array($result)) {
            $rolePK = $row[0];
            break;
        }
        return $rolePK;
    }
    function checkString($str) {
        if (strlen(trim($str))==0) 
            $str = "NULL";
        else {
            if (!preg_match("/^(-+)?\d+$/", $str))
                $str = "'".$str."'";
        }
        return $str;
    }
    
    #region add
    function addRow($table,$columnArray) {
        $q = "INSERT INTO ".$table." VALUES (";
        $first = true;
        foreach ($columnArray AS $val) {
            if ($first) 
                $first = false;
            else 
                $q = $q.",";
            
            $q = $q.$this->checkString($val);
        }
        $q = $q.");";
        //echo $q."<br />";
        return mysql_query($q,$this->conn);
    }    
    function updateRow($table,$setColumn,$conditions, $operand = 'AND') {
        foreach ($setColumn AS $setCol) {
            $q = "UPDATE ".$table." SET ".$setCol->column."=".$this->checkString($setCol->value)." WHERE ";
            $first = true;
            foreach ($conditions AS $cond) {
                if ($first)
                    $first = false;
                else
                    $q = $q." ".$operand." ";

                $q = $q.$cond->column." ".$cond->condition." ".$this->checkString($cond->value);
            }
            $q = $q.";";
            //echo $q;
            mysql_query($q,$this->conn);
        }
    }    
    function deleteRow($table,$conditions, $operand = 'AND') {
        $q = "DELETE FROM ".$table." WHERE ";
        $first = true;
        foreach ($conditions AS $cond) {
            if ($first)
                $first = false;
            else
                $q = $q." ".$operand." ";
            
            $q = $q.$cond->column." ".$cond->condition." ".$this->checkString($cond->value);
        }
        $q = $q.";";
        mysql_query($q,$this->conn);
    }    
    function getRow1($table,$columnArray) {
        $q = "SELECT ";
        $first = true;
        foreach ($columnArray AS $col) {
            if ($first)
                $first = false;
            else
                $q = $q.",";
            
            $q = $q.$col;
        }
        
        $q = $q." FROM ".$table.";";
        return mysql_query($q,$this->conn);
    }    
    function getRow2($table,$columnArray,$conditions, $operand = 'AND') {
        $q = "SELECT ";
        $first = true;
        foreach ($columnArray AS $col) {
            if ($first)
                $first = false;
            else
                $q = $q.",";
            
            $q = $q.$col;
        }
        
        $q = $q." FROM ".$table." WHERE ";
        $first = true;
        foreach ($conditions AS $cond) {
            if ($first)
                $first = false;
            else
                $q = $q." ".$operand." ";
            
            $condition  = $cond->value;
            $q = $q.$cond->column." ".$cond->condition." ".$this->checkString($condition);
        }
        $q = $q.";";
        //echo $q;
        return mysql_query($q,$this->conn);
    }    
    function getRow3($table,$columnArray,$orderBy) {
        $q = "SELECT ";
        $first = true;
        foreach ($columnArray AS $col) {
            if ($first)
                $first = false;
            else
                $q = $q.",";
            
            $q = $q.$col;
        }
        
        $q = $q." FROM ".$table." ORDER BY ";
        
        $first = true;
        foreach ($orderBy AS $ord) {
            if ($first)
                $first = false;
            else
                $q = $q.",";
            
            $q = $q.$ord;
        }
        
        $q = $q.";";
//echo $q;
        return mysql_query($q,$this->conn);
    }   
    function getRow4($table,$columnArray,$conditions,$orderBy, $operand = 'AND') {
        $q = "SELECT ";
        $first = true;
        foreach ($columnArray AS $col) {
            if ($first)
                $first = false;
            else
                $q = $q.",";
            
            $q = $q.$col;
        }
        
        $q = $q." FROM ".$table." WHERE ";
        $first = true;
        foreach ($conditions AS $cond) {
            if ($first)
                $first = false;
            else
                $q = $q." ".$operand." ";

            $q = $q.$cond->column." ".$cond->condition." ".$this->checkString($cond->value);
        }
        
        $q = $q." ORDER BY ";
        $first = true;
        foreach ($orderBy AS $ord) {
            if ($first)
                $first = false;
            else
                $q = $q.",";
            
            $q = $q.$ord;
        }
        
        $q = $q.";";
        return mysql_query($q,$this->conn);
    }    
    function getQuery($q) {
        $q = $q.";";
        return mysql_query($q,$this->conn);
    }    
    function createBowler($email, $fname, $lname, $groupPK = '', $teamFK = '', $flightFK = '', $role = 'Bowler') {
        $rolePK = '';
        $table = 'siteRoles';
        $columnArray = array('RolePK');
        $conditions = array(new condition('role',$role),new condition('rolePK',$role));

        $result = $this->getRow2($table, $columnArray, $conditions, 'OR');
        while ($row = mysql_fetch_array($result)) {
            $rolePK = $row['RolePK'];
            break;
        }
        
        $userPK = $this->UUID();
        $passcode = $this->randomPassword();
        $table = 'users';
        $columnArray = array($userPK,$email,md5($passcode),'',$rolePK);
        $this->addRow($table, $columnArray);
        
        $personPK = $this->UUID();
        $table = 'person';
        $columnArray = array($personPK,$fname,$lname,'','','','','','','',$userPK,$groupPK);
        $this->addRow($table, $columnArray);
        
        if ($teamFK!='') {
            $table = 'teamBowler';
            $columnArray = array($teamFK,$personPK,'');
            $this->addRow($table,$columnArray);
        }
        
        if ($flightFK!='') {
            $table = 'personflightpreferences';
            $columnArray = array($personPK,$flightFK,1);
            $this->addRow($table, $columnArray);
        }
        
        $success = true;
        $mail = new databaseEmail();

        $emailSubject = "2012 Bowling For Kids' Sake - Bowler Registration!";
        $emailHTMLBody = "<html><body>
            You have been registered to bowl in this year's \"Bowling For Kids' Sake\"<p>
            <p>
            You need to complete your registration process, so that you can collect pledges and be eligible prizes!<p>
            <a href=\"".baseURL."registration/registration.php?user=".$userPK."\">Complete Registration!</a>
            <p>
            Your password, when requested, is ".$passcode.".  This can be changed after you complete the registration process.</body></html>
            ";
        $emailTextBody = 
"You have been registered to bowl in this year's \"Bowling For Kids' Sake\"

You need to complete your registration process, so that you can collect pledges and be eligible prizes!

Please copy and paste the following link into your browser.  If you did not register for Bowling For Kids' Sake 2012, please disregard this message and our apologies.
".baseURL."registration/registration.php?user=".$userPK."

Your password, when requested, is ".$passcode.".  This can be changed after you complete the registration process.";
        $success = $mail->sendEmail($fname." ".$lname." <".$email.">",$emailSubject,$emailTextBody,$emailHTMLBody);
        
        if (!$success) {
            //echo "Error sending email, creating file...";
            $myFile = "../Unsent Emails/".$userPK.".txt";
            if (!$fh = fopen($myFile, 'w')) {
                 //echo "Cannot open file ($myFile)";
            }
            else {
                $stringData = "To: ".$fname." ".$lname." <".$email.">"."\n";
                fwrite($fh, $stringData);
                $stringData = $emailSubject."\n";
                fwrite($fh, $stringData);
                $stringData = $emailTextBody."\n";
                fwrite($fh, $stringData);
                fclose($fh);
            }
        }
        
        $table = 'users';
        $setColumn = array(new condition('verified',1));
        $conditions = array(new condition('userPK',$userPK));
        $this->updateRow($table, $setColumn, $conditions);
        
        return $userPK;
    }    
    function createTeam($teamName, $flightFK, $recruiterFK, $groupFK = '') {
        $teamPK = $this->UUID();
        //echo "<br>".$teamPK;
        $table = 'team';
        $columnArray = array($teamPK, $teamName,'');
        $this->addRow($table,$columnArray);
        
        $table = 'teamFlight';
        $columnArray = array($teamPK, $flightFK);
        $this->addRow($table,$columnArray);
        
        $table = 'teamRecruiter';
        $columnArray = array($teamPK,$recruiterFK,$groupFK);
        $this->addRow($table,$columnArray);
        
        return $teamPK;
    }    
    function createTempUser($pk,$email,$firstName,$lastName,$role='Team Recruiter - Staff') {
        
        $table = 'users';
        $columnArray = array('userpk');
        $conditions = array(new condition('email',$email));
        $result = $this->getRow2($table, $columnArray, $conditions);
        $found = false;
        while ($row = mysql_fetch_array($result)) {
            $found = ($row[0]!=$pk);
            break;
        }
        
        if ($found)
            echo "<script type='text/javascript'> 
                    confirm('The email provided is already in use.  No changes made.!');
                    parent.window.location.reload();
                </script>";
        else {
            $newUser = true;

            $table = 'users';
            $columnArray = array('UserPK');
            $conditions = array(new condition('UserPK',$pk));
            $result = $this->getRow2($table, $columnArray, $conditions);
            while ($row = mysql_fetch_array($result)) {
                $newUser = false;
                break;
            }

            if ($newUser) {
                //create mode
                $table = 'tempuser';
                $setColumn = array(new condition('email',$email),new condition('fName',$firstName),new condition('lName',$lastName),new condition('role',$role));
                $conditions = array(new condition('tempUserPK',$pk));
                $this->updateRow($table, $setColumn, $conditions);

                $userPK = $this->createBowler($email, $firstName, $lastName, '', '', '', $role);

                $table = 'users';
                $setColumn = array(new condition('userPK',$pk));
                $conditions = array(new condition('userPK',$userPK));
                $this->updateRow($table, $setColumn, $conditions);

                $table = 'person';
                $setColumn = array(new condition('userFK',$pk));
                $conditions = array(new condition('userFK',$userPK));
                $this->updateRow($table, $setColumn, $conditions);

                $table = 'tempuser';
                $conditions = array(new condition('tempUserPK',$pk));
                $this->deleteRow($table, $conditions);
            }
            else {
                //echo "edit mode<br>";
                //edit mode
                $oEmail = "";$oFName = "";$oLName="";$oRoleFK="";
                $q = "SELECT U.email,P.fName,P.lName,U.roleFK FROM Users AS U INNER JOIN Person AS P ON U.UserPK=P.UserFK WHERE U.UserPK='".$pk."'";
                //echo $q;
                $result = $this->getQuery($q);
                while ($row = mysql_fetch_array($result)) {
                    $oEmail = $row[0];
                    $oFName = $row[1];
                    $oLName = $row[2];
                    $oRoleFK = $row[3];
                    break;
                }

                $roleFK = "";
                $table = 'siteroles';
                $columnArray = array('rolePK');
                $conditions = array(new condition('role',$role),new condition('rolePK',$role));
                $result = $this->getRow2($table, $columnArray, $conditions,'OR');
                while ($row = mysql_fetch_array($result)) {
                    $roleFK = $row[0];
                    break;
                }

                if ($oEmail!=$email || $oRoleFK!=$roleFK) {
                    $table = 'users';
                    $setColumn = array(new condition('email',$email),new condition('roleFK',$roleFK));
                    $conditions = array(new condition('userPK',$pk));
                    $this->updateRow($table, $setColumn, $conditions, 'OR');
                }
                if ($oFName!=$firstName || $oLName!=$lastName) {
                    $table = 'person';
                    $setColumn = array(new condition('fname',$firstName),new condition('lname',$lastName));
                    $conditions = array(new condition('userFK',$pk));
                    $this->updateRow($table,$setColumn,$conditions);
                }
            }
        }
    }    
    function getPersonPK($id) {
        $table = 'person';
        $columnArray = array('personPK');
        $conditions = array(new condition('userFK',$id));
        $result = $this->getRow2($table, $columnArray, $conditions);
        $personPK = "";
        while ($row = mysql_fetch_array($result)) {
            $personPK = $row[0];
            break;
        }
        return $personPK;
    }
    function getReports($role, $id, $report)
    {
        $q = "";
        $personpk = $this->getPersonPK($id);
        
        //Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set properties
        $objPHPExcel->getProperties()->setCreator("dablodgettstjohns.org")
            ->setLastModifiedBy("dablodgettstjohns.org")
            ->setTitle("Team Reports")
            ->setSubject("Office 2007 dablodgettstjohns.org")
            ->setDescription("dablodgettstjohns.org")
            ->setKeywords("dablodgettstjohns.org")
            ->setCategory("dablodgettstjohns.org");
        
        switch ($role)
        {
            //admin
            case "89c18686-623c-11e0-b944-66a920a5d950":
                switch($report)
                {
                    case "Unassigned":
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Bowler First Name')
                            ->setCellValue('B1', 'Bowler Last Name')
                            ->setCellValue('C1', 'Street Address')
                            ->setCellValue('D1', 'Apt/Suite')
                            ->setCellValue('E1', 'City')
                            ->setCellValue('F1', 'State')
                            ->setCellValue('G1', 'Zip')
                            ->setCellValue('H1', 'Email')
                            ->setCellValue('I1', 'Phone')
                            ->setCellValue('J1', 'First Flight Preference')
                            ->setCellValue('K1', 'Second Flight Preference')
                            ->setCellValue('L1', 'Third Flight Preference');
                        
                        $counter = 2;
                        $q = "
SELECT
     P.fName
    ,P.lName
    ,P.streetaddress1
    ,P.streetaddress2
    ,P.City
    ,IFNULL(S.State,'Michigan')
    ,P.Zip
    ,U.email
    ,P.phone    
    ,DATE_FORMAT(F1.flightTime,'%a %c/%d %l %p' )
    ,DATE_FORMAT(F2.flightTime,'%a %c/%d %l %p' )
    ,DATE_FORMAT(F3.flightTime,'%a %c/%d %l %p' )
FROM
    Person AS P
    INNER JOIN Users AS U ON P.UserFK=U.UserPK
    LEFT JOIN State AS S ON P.stateFK=S.StatePK
    LEFT JOIN PersonFlightPreferences AS PFP1 ON P.PersonPK=PFP1.PersonFK AND PFP1.preference=1
    LEFT JOIN Flight AS F1 ON PFP1.FlightFK=F1.FlightPK
    LEFT JOIN PersonFlightPreferences AS PFP2 ON P.PersonPK=PFP2.PersonFK AND PFP2.preference=2
    LEFT JOIN Flight AS F2 ON PFP2.FlightFK=F2.FlightPK
    LEFT JOIN PersonFlightPreferences AS PFP3 ON P.PersonPK=PFP3.PersonFK AND PFP3.preference=3
    LEFT JOIN Flight AS F3 ON PFP3.FlightFK=F3.FlightPK
WHERE
    NOT EXISTS (SELECT 1 FROM TeamBowler AS TB WHERE P.PersonPK=TB.BowlerFK)
    AND U.verified=1
    AND (
    PFP1.PersonFK IS NOT NULL
    OR EXISTS (SELECT 1 FROM Users AS U INNER JOIN SiteRoles AS SR ON U.RoleFK=SR.RolePK WHERE SR.Role='Bowler' AND P.UserFK=U.UserPK)
    )
order by 
    P.lname                         
";
                        
                        $result = $this->getQuery($q);
                        while ($row = mysql_fetch_array($result)) {
                            //out put the information
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$counter, $row[0])
                                ->setCellValue('B'.$counter, $row[1])
                                ->setCellValue('C'.$counter, $row[2])
                                ->setCellValue('D'.$counter, $row[3])
                                ->setCellValue('E'.$counter, $row[4])
                                ->setCellValue('F'.$counter, $row[5])
                                ->setCellValue('G'.$counter, $row[6])
                                ->setCellValue('H'.$counter, $row[7])
                                ->setCellValue('I'.$counter ,$row[8])
                                ->setCellValue('J'.$counter ,$row[9])
                                ->setCellValue('K'.$counter ,$row[10])
                                ->setCellValue('L'.$counter ,$row[11]);
                            $counter = $counter + 1;
                        }
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Unassigned Bowler Reports');
                        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                        $objPHPExcel->setActiveSheetIndex(0);
                        // Redirect output to a client’s web browser (Excel5)
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="UnassignedBowlerReports.xls"');
                        header('Cache-Control: max-age=0');
                        //out put to user download
                        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save('php://output');
                        
                        break;
                    case "Bowler":
                        // Add Header
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A1', 'Selected Time')
                                ->setCellValue('B1', 'Team Name')
                                ->setCellValue('C1', 'Bowler First Name')
                                ->setCellValue('D1', 'Bowler Last Name')
                                ->setCellValue('E1', 'Street Address')
                                ->setCellValue('F1','Apt/Suite')
                                ->setCellValue('G1','City')
                                ->setCellValue('H1','State')
                                ->setCellValue('I1' ,'Zip')
                                ->setCellValue('J1' ,'Email')
                                ->setCellValue('K1' ,'Phone')
                                ->setCellValue('L1' ,'Shirt Size');
                        
                        //used in while loop to determin the position to put the colmun
                        $counter=2;
                        //used to hold the sql
                        //create the Sql
                        $q = "
SELECT 
     DATE_FORMAT(F.flightTime,'%a %c/%d %l %p' ) as flightTime
    ,T.teamName
    ,P.fName
    ,P.lName
    ,P.streetAddress1
    ,P.streetAddress2
    ,P.city
    ,S.State
    ,P.zip
    ,U.email
    ,P.phone
    ,SS.size
FROM
    Users AS U
    INNER JOIN Person AS P ON U.UserPK=p.UserFK
    LEFT OUTER JOIN ShirtSize AS SS ON P.shirtsizeFK=SS.ShirtSizePK
    INNER JOIN TeamBowler AS TB ON P.PersonPK=TB.BowlerFK
    INNER JOIN Team AS T ON TB.TeamFK=T.TeamPK
    INNER JOIN TeamFlight AS TF ON T.TeamPK=TF.TeamFK
    INNER JOIN Flight AS F ON TF.FlightFK=F.FlightPK
    LEFT OUTER JOIN State AS S ON P.StateFK=S.StatePK
ORDER BY 
     F.FlightTime
    ,T.TeamName                            
";
                        //get the SQl from the data base throught the database class
                        $Bowlerinforamtion =$this->getQuery($q);
                        //loop thorough the data base to get the information
                        while ($row = mysql_fetch_array($Bowlerinforamtion))
                        {
                            //out put the information
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$counter, $row[0])
                                ->setCellValue('B'.$counter, $row[1])
                                ->setCellValue('C'.$counter, $row[2])
                                ->setCellValue('D'.$counter, $row[3])
                                ->setCellValue('E'.$counter, $row[4])
                                ->setCellValue('F'.$counter, $row[5])
                                ->setCellValue('G'.$counter, $row[6])
                                ->setCellValue('H'.$counter, $row[7])
                                ->setCellValue('I'.$counter ,$row[8])
                                ->setCellValue('J'.$counter ,$row[9])
                                ->setCellValue('K'.$counter ,$row[10])
                                ->setCellValue('L'.$counter ,$row[11]);
                            $counter = $counter + 1;
                        }
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Bowler Reports');
                        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                        $objPHPExcel->setActiveSheetIndex(0);
                        // Redirect output to a client’s web browser (Excel5)
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="BowlerReports.xls"');
                        header('Cache-Control: max-age=0');
                        //out put to user download
                        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save('php://output');
                        break;
                    case "Pledge":
                        // Add Header Data
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Pledge First Name')
                            ->setCellValue('B1', 'Pledge Last Name')
                            ->setCellValue('C1', 'Email')
                            ->setCellValue('D1', 'Bowler Name')
                            ->setCellValue('E1','Team Name')
                            ->setCellValue('F1','Organization')
                            ->setCellValue('G1','Amount Pledged');
                        
                        //counter to figure out position in excell sheet
                        $counter=2;
                        //holds the data set
                        $PledgeInformation="";
                        //holds the querry string
                        $q = "
SELECT
     P.fName AS FirstName
    ,P.lName AS LastName
    ,U.Email
    ,CONCAT(B.fName,' ',B.lName) AS Bowler
    ,T.teamName AS Team
    ,G.groupName
    ,PL.amount AS Amount
FROM
    Users AS U
    INNER JOIN Person AS P ON U.UserPK=P.UserFK
    INNER JOIN Pledge AS PL ON P.PersonPK=PL.PledgerFK
    LEFT OUTER JOIN Person AS B ON PL.BowlerFK = B.PersonPK
    LEFT OUTER JOIN Team AS T ON PL.TeamFK=T.TeamPK
    LEFT OUTER JOIN Groups AS G ON PL.GroupFK=G.GroupPK
ORDER BY
     LastName
    ,FirstName
    ,G.groupName
    ,T.TeamName
    ,B.lName
    ,B.fName
";
                        //get the data set from the data base class
                        $PledgeInformation = $this->getQuery($q);
                        // loop throug the rows out putting them
                        while ($row = mysql_fetch_array($PledgeInformation))
                        {
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$counter, $row[0])
                                ->setCellValue('B'.$counter, $row[1])
                                ->setCellValue('C'.$counter, $row[2])
                                ->setCellValue('D'.$counter, $row[3])
                                ->setCellValue('E'.$counter, $row[4])
                                ->setCellValue('F'.$counter, $row[5])
                                ->setCellValue('G'.$counter, $row[6]);
                            $counter = $counter + 1;
                        }
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Pledge Reports');
                        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                        $objPHPExcel->setActiveSheetIndex(0);
                        // Redirect output to a client’s web browser (Excel5)
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="PledgeReport.xls"');
                        header('Cache-Control: max-age=0');
                        //close all objects
                        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save('php://output');
                        break;
                    case "Recruiter Pledges":
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1','Recruiter Role')
                            ->setCellValue('B1','Organization')
                            ->setCellValue('C1','Recruiter')
                            ->setCellValue('D1','Team')
                            ->setCellValue('E1','Amount');
                        $counter=2;
                        
                        $q = "
SELECT
     A.Role
    ,A.GroupName
    ,A.Recruiter
    ,A.TeamName
    ,SUM(A.amount) AS Amount
FROM
    (SELECT
         SR.Role
        ,G.GroupName
        ,CONCAT(R.fName,' ',R.lName) AS Recruiter
        ,T.TeamName
        ,SUM(IFNULL(PL.amount,0)) AS amount
    FROM
        Person AS R
        INNER JOIN Users AS U ON R.UserFK=U.UserPK
        INNER JOIN SiteRoles AS SR ON U.RoleFK=SR.RolePK
        INNER JOIN TeamRecruiter AS TR ON R.PersonPK=TR.PersonFK
        LEFT OUTER JOIN Groups AS G ON TR.GroupFK=G.GroupPK
        INNER JOIN Team AS T ON TR.TeamFK=T.TeamPK
        INNER JOIN TeamBowler AS TB ON T.TeamPK=TB.TeamFK
        INNER JOIN Person AS B ON TB.BowlerFK=B.PersonPK
        LEFT OUTER JOIN Pledge AS PL ON B.PersonPK=PL.BowlerFK
    GROUP BY
         SR.Role
        ,G.GroupName
        ,CONCAT(R.fName,' ',R.lName)
        ,T.TeamName
        
    UNION

    SELECT
         SR.Role
        ,G.GroupName
        ,CONCAT(R.fName,' ',R.lName) AS Recruiter
        ,T.TeamName
        ,SUM(IFNULL(PL.amount,0)) AS amount
    FROM
        Person AS R
        INNER JOIN Users AS U ON R.UserFK=U.UserPK
        INNER JOIN SiteRoles AS SR ON U.RoleFK=SR.RolePK
        INNER JOIN TeamRecruiter AS TR ON R.PersonPK=TR.PersonFK
        LEFT OUTER JOIN Groups AS G ON TR.GroupFK=G.GroupPK
        INNER JOIN Team AS T ON TR.TeamFK=T.TeamPK
        LEFT OUTER JOIN Pledge AS PL ON T.TeamPK=PL.TeamFK
    GROUP BY
         SR.Role
        ,G.GroupName
        ,CONCAT(R.fName,' ',R.lName)
        ,T.TeamName) AS A
GROUP BY
     A.Role
    ,A.GroupName
    ,A.Recruiter
    ,A.TeamName
ORDER BY
     A.Role
    ,A.GroupName
    ,A.Recruiter
    ,A.TeamName                            
";
                        $result =$this->getQuery($q);
                        //loop though the data set to out put the row informaion
                        while ($row = mysql_fetch_array($result))
                        {
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$counter, $row[0])
                                ->setCellValue('B'.$counter, $row[1])
                                ->setCellValue('C'.$counter, $row[2])
                                ->setCellValue('D'.$counter, $row[3])
                                ->setCellValue('E'.$counter, $row[4]);
                            $counter = $counter + 1;
                        }
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Recruiter Pledge');
                        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                        $objPHPExcel->setActiveSheetIndex(0);
                        // Redirect output to a client’s web browser (Excel5)
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="RecruiterPledgeReport.xls"');
                        header('Cache-Control: max-age=0');
                        //close all objects
                        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save('php://output');
                        break;
                    case "Sponsor":
                        // Add Header
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Organization')
                            ->setCellValue('B1', 'Sponsor Name')
                            ->setCellValue('C1', 'Email')
                            ->setCellValue('D1', 'Package')
                            ->setCellValue('E1', 'Amount')
                            ->setCellValue('F1', 'Non-Monetary Donation');
                        //used to loop in while loop
                        $counter=2;
                        //holds dataset
                        $SponsorInforamtion="";
                        //holds querry string
                        $q = "
SELECT
     G.groupName
    ,CONCAT(P.fName,' ',P.lName) AS Sponsor
    ,U.email
    ,SO.sponsorlevel
    ,IFNULL(SO.amount,CS.amount) AS amount
    ,CS.materialDonation
FROM
    Users AS U
    INNER JOIN Person AS P ON U.UserPK=P.UserFK
    INNER JOIN CorporateSponsor AS CS ON P.PersonPK=CS.RepFK
    LEFT OUTER JOIN Groups AS G ON CS.GroupFK=G.GroupPK
    LEFT OUTER JOIN SponsorshipOption AS SO ON CS.SponsorshipOptionFK=SO.SponsorshipOptionPK
ORDER BY
     amount DESC
    ,groupName
    ,Sponsor
    ,email
";
                        //sends querry to data base and returns a data set
                        $SponsorInforamtion =$this->getQuery($q);
                        //loop though the data set to out put the row informaion
                        while ($row = mysql_fetch_array($SponsorInforamtion))
                        {
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$counter, $row[0])
                                ->setCellValue('B'.$counter, $row[1])
                                ->setCellValue('C'.$counter, $row[2])
                                ->setCellValue('D'.$counter, $row[3])
                                ->setCellValue('E'.$counter, $row[4])
                                ->setCellValue('F'.$counter, $row[5]);
                            $counter = $counter + 1;
                        }
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Sponsor Reports');
                        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                        $objPHPExcel->setActiveSheetIndex(0);
                        // Redirect output to a client’s web browser (Excel5)
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="SponsorReport.xls"');
                        header('Cache-Control: max-age=0');
                        //close all objects
                        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save('php://output');
                        break;
                }
                break;
            //team recuriter
            case "89c16e60-623c-11e0-b944-66a920a5d950":
            //team recuriter staff
            case "89c17ba9-623c-11e0-b944-66a920a5d950":
                switch($report)
                {
                    case "Unassigned":
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Bowler First Name')
                            ->setCellValue('B1', 'Bowler Last Name')
                            ->setCellValue('C1', 'Street Address')
                            ->setCellValue('D1', 'Apt/Suite')
                            ->setCellValue('E1', 'City')
                            ->setCellValue('F1', 'State')
                            ->setCellValue('G1', 'Zip')
                            ->setCellValue('H1', 'Email')
                            ->setCellValue('I1', 'Phone')
                            ->setCellValue('J1', 'First Flight Preference')
                            ->setCellValue('K1', 'Second Flight Preference')
                            ->setCellValue('L1', 'Third Flight Preference');
                        
                        $counter = 2;
                        $q = "
SELECT
     P.fName
    ,P.lName
    ,P.streetaddress1
    ,P.streetaddress2
    ,P.City
    ,IFNULL(S.State,'Michigan')
    ,P.Zip
    ,U.email
    ,P.phone    
    ,DATE_FORMAT(F1.flightTime,'%a %c/%d %l %p' )
    ,DATE_FORMAT(F2.flightTime,'%a %c/%d %l %p' )
    ,DATE_FORMAT(F3.flightTime,'%a %c/%d %l %p' )
FROM
    Person AS P
    INNER JOIN Users AS U ON P.UserFK=U.UserPK
    LEFT JOIN State AS S ON P.stateFK=S.StatePK
    LEFT JOIN PersonFlightPreferences AS PFP1 ON P.PersonPK=PFP1.PersonFK AND PFP1.preference=1
    LEFT JOIN Flight AS F1 ON PFP1.FlightFK=F1.FlightPK
    LEFT JOIN PersonFlightPreferences AS PFP2 ON P.PersonPK=PFP2.PersonFK AND PFP2.preference=2
    LEFT JOIN Flight AS F2 ON PFP2.FlightFK=F2.FlightPK
    LEFT JOIN PersonFlightPreferences AS PFP3 ON P.PersonPK=PFP3.PersonFK AND PFP3.preference=3
    LEFT JOIN Flight AS F3 ON PFP3.FlightFK=F3.FlightPK
WHERE
    NOT EXISTS (SELECT 1 FROM TeamBowler AS TB WHERE P.PersonPK=TB.BowlerFK)
    AND U.verified=1
    AND (
    PFP1.PersonFK IS NOT NULL
    OR EXISTS (SELECT 1 FROM Users AS U INNER JOIN SiteRoles AS SR ON U.RoleFK=SR.RolePK WHERE SR.Role='Bowler' AND P.UserFK=U.UserPK)
    )
order by 
    P.lname                         
";
                        
                        $result = $this->getQuery($q);
                        while ($row = mysql_fetch_array($result)) {
                            //out put the information
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$counter, $row[0])
                                ->setCellValue('B'.$counter, $row[1])
                                ->setCellValue('C'.$counter, $row[2])
                                ->setCellValue('D'.$counter, $row[3])
                                ->setCellValue('E'.$counter, $row[4])
                                ->setCellValue('F'.$counter, $row[5])
                                ->setCellValue('G'.$counter, $row[6])
                                ->setCellValue('H'.$counter, $row[7])
                                ->setCellValue('I'.$counter ,$row[8])
                                ->setCellValue('J'.$counter ,$row[9])
                                ->setCellValue('K'.$counter ,$row[10])
                                ->setCellValue('L'.$counter ,$row[11]);
                            $counter = $counter + 1;
                        }
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Unassigned Bowler Reports');
                        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                        $objPHPExcel->setActiveSheetIndex(0);
                        // Redirect output to a client’s web browser (Excel5)
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="UnassignedBowlerReports.xls"');
                        header('Cache-Control: max-age=0');
                        //out put to user download
                        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save('php://output');
                        
                        break;
                    case "Recruiter Pledges":
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1','Team')
                            ->setCellValue('B1','Bowler')
                            ->setCellValue('C1','Amount');
                        $counter=2;
                        
                        $q = "
SELECT
     A.TeamName
    ,A.Bowler
    ,SUM(A.amount) AS Amount
FROM
    (SELECT
         T.TeamName
        ,CONCAT(B.fName,' ',B.lName) AS Bowler
        ,SUM(IFNULL(PL.amount,0)) AS amount
    FROM
        Person AS R
        INNER JOIN Users AS U ON R.UserFK=U.UserPK
        INNER JOIN SiteRoles AS SR ON U.RoleFK=SR.RolePK
        INNER JOIN TeamRecruiter AS TR ON R.PersonPK=TR.PersonFK
        LEFT OUTER JOIN Groups AS G ON TR.GroupFK=G.GroupPK
        INNER JOIN Team AS T ON TR.TeamFK=T.TeamPK
        INNER JOIN TeamBowler AS TB ON T.TeamPK=TB.TeamFK
        INNER JOIN Person AS B ON TB.BowlerFK=B.PersonPK
        LEFT OUTER JOIN Pledge AS PL ON B.PersonPK=PL.BowlerFK
    WHERE
        R.PersonPK='".$personpk."'
    GROUP BY
         T.TeamName
        ,CONCAT(B.fName,' ',B.lName)
    
    /*
    UNION
    
    SELECT
         T.TeamName
        ,NULL
        ,SUM(IFNULL(PL.amount,0)) AS amount
    FROM
        Person AS R
        INNER JOIN Users AS U ON R.UserFK=U.UserPK
        INNER JOIN SiteRoles AS SR ON U.RoleFK=SR.RolePK
        INNER JOIN TeamRecruiter AS TR ON R.PersonPK=TR.PersonFK
        LEFT OUTER JOIN Groups AS G ON TR.GroupFK=G.GroupPK
        INNER JOIN Team AS T ON TR.TeamFK=T.TeamPK
        INNER JOIN TeamBowler AS TB ON T.TeamPK=TB.TeamFK
        INNER JOIN Person AS B ON TB.BowlerFK=B.PersonPK
        LEFT OUTER JOIN Pledge AS PL ON B.PersonPK=PL.BowlerFK
    WHERE
        R.PersonPK='".$personpk."'
    GROUP BY
         T.TeamName
    */
    UNION

    SELECT
         T.TeamName
        ,NULL
        ,SUM(IFNULL(PL.amount,0)) AS amount
    FROM
        Person AS R
        INNER JOIN Users AS U ON R.UserFK=U.UserPK
        INNER JOIN SiteRoles AS SR ON U.RoleFK=SR.RolePK
        INNER JOIN TeamRecruiter AS TR ON R.PersonPK=TR.PersonFK
        LEFT OUTER JOIN Groups AS G ON TR.GroupFK=G.GroupPK
        INNER JOIN Team AS T ON TR.TeamFK=T.TeamPK
        LEFT OUTER JOIN Pledge AS PL ON T.TeamPK=PL.TeamFK
    WHERE
        R.PersonPK='".$personpk."'
    GROUP BY
         T.TeamName) AS A
GROUP BY
      A.TeamName
     ,A.Bowler
ORDER BY
     A.TeamName     
    ,A.Bowler                            
";
                        $result =$this->getQuery($q);
                        //loop though the data set to out put the row informaion
                        while ($row = mysql_fetch_array($result))
                        {
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$counter, $row[0])
                                ->setCellValue('B'.$counter, $row[1])
                                ->setCellValue('C'.$counter, $row[2]);
                            $counter = $counter + 1;
                        }
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Recruiter Pledge');
                        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                        $objPHPExcel->setActiveSheetIndex(0);
                        // Redirect output to a client’s web browser (Excel5)
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="RecruiterPledgeReport.xls"');
                        header('Cache-Control: max-age=0');
                        //close all objects
                        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save('php://output');
                        break;
                    case "Bowler":
                        // Add Header
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A1', 'Selected Time')
                                ->setCellValue('B1', 'Team Name')
                                ->setCellValue('C1', 'Bowler First Name')
                                ->setCellValue('D1', 'Bowler Last Name')
                                ->setCellValue('E1', 'Street Address')
                                ->setCellValue('F1','Apt/Suite')
                                ->setCellValue('G1','City')
                                ->setCellValue('H1','State')
                                ->setCellValue('I1' ,'Zip')
                                ->setCellValue('J1' ,'Email')
                                ->setCellValue('K1' ,'Phone')
                                ->setCellValue('L1' ,'Shirt Size');
                        
                        //used in while loop to determin the position to put the colmun
                        $counter=2;
                        //used to capture the information from the data base
                        $Bowlerinforamtion="";
                        //used to hold the sql
                        //create the Sql
                        $q = "
SELECT 
     DATE_FORMAT(F.flightTime,'%a %c/%d %l %p' ) as flightTime
    ,T.teamName
    ,P.fName
    ,P.lName
    ,P.streetAddress1
    ,P.streetAddress2
    ,P.city
    ,S.State
    ,P.zip
    ,U.email
    ,P.phone
    ,SS.size
FROM
    Users AS U
    INNER JOIN Person AS P ON U.UserPK=p.UserFK
    LEFT OUTER JOIN ShirtSize AS SS ON P.shirtsizeFK=SS.ShirtSizePK
    INNER JOIN TeamBowler AS TB ON P.PersonPK=TB.BowlerFK
    INNER JOIN Team AS T ON TB.TeamFK=T.TeamPK
    INNER JOIN TeamFlight AS TF ON T.TeamPK=TF.TeamFK
    INNER JOIN Flight AS F ON TF.FlightFK=F.FlightPK
    LEFT OUTER JOIN State AS S ON P.StateFK=S.StatePK
WHERE
    EXISTS (SELECT 1 FROM TeamRecruiter AS RT WHERE RT.TeamFK=T.TeamPK AND RT.PersonFK='".$personpk."')
ORDER BY 
     F.FlightTime
    ,T.TeamName 
";
                        //get the SQl from the data base throught the database class
                        $Bowlerinforamtion =$this->getQuery($q);
                        //loop thorough the data base to get the information
                        while ($row = mysql_fetch_array($Bowlerinforamtion))
                        {
                            //out put the information
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$counter, $row[0])
                                ->setCellValue('B'.$counter, $row[1])
                                ->setCellValue('C'.$counter, $row[2])
                                ->setCellValue('D'.$counter, $row[3])
                                ->setCellValue('E'.$counter, $row[4])
                                ->setCellValue('F'.$counter, $row[5])
                                ->setCellValue('G'.$counter, $row[6])
                                ->setCellValue('H'.$counter, $row[7])
                                ->setCellValue('I'.$counter ,$row[8])
                                ->setCellValue('J'.$counter ,$row[9])
                                ->setCellValue('K'.$counter ,$row[10])
                                ->setCellValue('L'.$counter ,$row[11]);
                            $counter = $counter + 1;
                        }
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Bowler Reports');
                        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                        $objPHPExcel->setActiveSheetIndex(0);
                        // Redirect output to a client’s web browser (Excel5)
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="BowlerReports.xls"');
                        header('Cache-Control: max-age=0');
                        //out put to user download
                        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save('php://output');
                        break;
                    case "Pledge":
                        // Add Header Data
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Pledge First Name')
                            ->setCellValue('B1', 'Pledge Last Name')
                            ->setCellValue('C1', 'Email')
                            ->setCellValue('D1', 'Bowler Name')
                            ->setCellValue('E1','Team Name')
                            ->setCellValue('F1','Organization')
                            ->setCellValue('G1','Amount Pledged');
                        
                        //counter to figure out position in excell sheet
                        $counter=2;
                        //holds the data set
                        $PledgeInformation="";
                        //holds the querry string
                        $q = "
SELECT
     P.fName AS FirstName
    ,P.lName AS LastName
    ,U.Email
    ,CONCAT(B.fName,' ',B.lName) AS Bowler
    ,T.teamName AS Team
    ,G.groupName
    ,PL.amount AS Amount
FROM
    Users AS U
    INNER JOIN Person AS P ON U.UserPK=P.UserFK
    INNER JOIN Pledge AS PL ON P.PersonPK=PL.PledgerFK
    LEFT OUTER JOIN Person AS B ON PL.BowlerFK = B.PersonPK
    LEFT OUTER JOIN Team AS T ON PL.TeamFK=T.TeamPK
    LEFT OUTER JOIN Groups AS G ON PL.GroupFK=G.GroupPK
WHERE
    B.PersonPK = '".$personpk."'
    OR EXISTS (SELECT 1 FROM TeamRecruiter AS TR WHERE TR.TeamFK=T.TeamPK AND TR.PersonFK='".$personpk."')
    OR EXISTS (SELECT 1 FROM Person AS R WHERE R.GroupFK=G.GroupPK AND R.PersonPK='".$personpk."')
    OR EXISTS (SELECT 1 FROM TeamBowler AS TB INNER JOIN TeamRecruiter AS TR ON TB.TeamFK=TR.TeamFK WHERE TB.BowlerFK=B.PersonPK AND TR.PersonFK='".$personpk."')
ORDER BY
     LastName
    ,FirstName
    ,G.groupName
    ,T.TeamName
    ,B.lName
    ,B.fName";
                        //get the data set from the data base class
                        $PledgeInformation =$this->getQuery($q);
                        // loop throug the rows out putting them
                        while ($row = mysql_fetch_array($PledgeInformation))
                        {
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$counter, $row[0])
                                ->setCellValue('B'.$counter, $row[1])
                                ->setCellValue('C'.$counter, $row[2])
                                ->setCellValue('D'.$counter, $row[3])
                                ->setCellValue('E'.$counter, $row[4])
                                ->setCellValue('F'.$counter, $row[5])
                                ->setCellValue('G'.$counter, $row[6]);
                            $counter = $counter + 1;
                        }
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Pledge Reports');
                        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                        $objPHPExcel->setActiveSheetIndex(0);
                        // Redirect output to a client’s web browser (Excel5)
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="PledgeReport.xls"');
                        header('Cache-Control: max-age=0');
                        //close all objects
                        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save('php://output');
                        break;
                }
                break;
            //bowler
            case "89c148a4-623c-11e0-b944-66a920a5d950":
                switch($report)
                {
                    case "Pledge":
                        // Add Header Data
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Pledge First Name')
                            ->setCellValue('B1', 'Pledge Last Name')
                            ->setCellValue('C1', 'Email')
                            ->setCellValue('D1', 'Bowler Name')
                            ->setCellValue('E1','Team Name')
                            ->setCellValue('F1','Organization')
                            ->setCellValue('G1','Amount Pledged');
                        
                        //counter to figure out position in excell sheet
                        $counter=2;
                        //holds the data set
                        $PledgeInformation="";
                        //holds the querry string
                        $q = "
       SELECT
     P.fName AS FirstName
    ,P.lName AS LastName
    ,U.Email
    ,CONCAT(B.fName,' ',B.lName) AS Bowler
    ,T.teamName AS Team
    ,G.groupName
    ,PL.amount AS Amount
FROM
    Users AS U
    INNER JOIN Person AS P ON U.UserPK=P.UserFK
    INNER JOIN Pledge AS PL ON P.PersonPK=PL.PledgerFK
    LEFT OUTER JOIN Person AS B ON PL.BowlerFK = B.PersonPK
    LEFT OUTER JOIN Team AS T ON PL.TeamFK=T.TeamPK
    LEFT OUTER JOIN Groups AS G ON PL.GroupFK=G.GroupPK
WHERE
    B.PersonPK = '".$personpk."'
ORDER BY
     LastName
    ,FirstName
    ,G.groupName
    ,T.TeamName
    ,B.lName
    ,B.fName";
                        //get the data set from the data base class
                        $PledgeInformation =$this->getQuery($q);
                        // loop throug the rows out putting them
                        while ($row = mysql_fetch_array($PledgeInformation))
                        {
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$counter, $row[0])
                                ->setCellValue('B'.$counter, $row[1])
                                ->setCellValue('C'.$counter, $row[2])
                                ->setCellValue('D'.$counter, $row[3])
                                ->setCellValue('E'.$counter, $row[4])
                                ->setCellValue('F'.$counter, $row[5])
                                ->setCellValue('G'.$counter, $row[6]);
                            $counter = $counter + 1;
                        }
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Pledge Reports');
                        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                        $objPHPExcel->setActiveSheetIndex(0);
                        // Redirect output to a client’s web browser (Excel5)
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="PledgeReport.xls"');
                        header('Cache-Control: max-age=0');
                        //close all objects
                        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save('php://output');
                        break;
                }
                break;
        }
    }
}


?>
