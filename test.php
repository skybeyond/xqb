<?php
/**
 * Created by PhpStorm.
 * User: beyond
 * Date: 2018/5/24
 * Time: 11:03
 */

/*闭包函数作用1*/

if(!function_exists('array_columns')) {
    function array_columns($arr, $key)
    {
        return array_map(function ($val) use ($key) {
            return $val[$key];
        }, $arr);
    }
}

$arr = array(array('id'=>1),array('id'=>2),array('id'=>3),array('id'=>4),array('id'=>5));

$m = array_columns($arr,'mm'); //与 array_column效果相同




var_dump($m);
