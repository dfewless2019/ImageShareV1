<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db
 *
 * @author Nathan
 */
class db {
    public $conn;
    
    function __construct() {
        
        //Dev
        $DB_SERVER = "localhost";
        $DB_USER = "multidim_sa";
        $DB_PASS = "38Special";
        $DB_NAME = "multidim_imageshare";
       
        
        $this->conn = mysql_connect($DB_SERVER,$DB_USER,$DB_PASS) or die(mysql_error());
        mysql_select_db($DB_NAME,$this->conn) or die(mysql_error());
    }
}

?>