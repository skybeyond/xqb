<?php
/**
 * Created by PhpStorm.
 * User: beyond
 * Date: 2018/5/23
 * Time: 16:10
 */


function new_addslashes($string){
    if(!is_array($string)) return addslashes($string);
    foreach($string as $key => $val) $string[$key] = new_addslashes($val);
    return $string;
}