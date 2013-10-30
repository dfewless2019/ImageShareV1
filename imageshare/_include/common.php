<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of common
 *
 * @author Nathan
 */
class common {
    //put your code here
    public static function randomPassword() {
        $n = rand(10e16, 10e23);
        return base_convert($n, 10, 36);
    }
}

?>
