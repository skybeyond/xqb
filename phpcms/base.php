<?php
/**
 * Created by PhpStorm.
 * User: beyond
 * Date: 2018/5/23
 * Time: 14:12
 */

define('IN_PHPCMS',true);

define('PC_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);

define('CACHE_PATH',PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR);

define('SITE_URL',(isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:''));

Loader::load_sys_func('global');

class Loader{
    /**
     * 加载系统函数库
     * @param $func
     */
    public static function load_sys_func($func,$path=''){
        static $funcs = array();
        if(empty($path)) $path = 'libs'.DIRECTORY_SEPARATOR.'functions';
        $path .= DIRECTORY_SEPARATOR.$func.'.func.php';
        $key = md5($path);
        if(isset($funcs[$key])) return true;
        if(file_exists(PC_PATH.$path)){
            include PC_PATH.$path;
        }else{
            $funcs[$key] = false;
            return false;
        }
        $funcs[$key] = true;
        return true;
    }

    /**
     * 加载类
     * @param $classname 类名
     * @param $path
     * @param int $initialize 是否初始化
     */
    public static function load_sys_class($classname,$path='',$initialize = 1){
        static $classes = array();
        if(empty($path)) $path = 'libs'.DIRECTORY_SEPARATOR.'class';
        $key = md5($path.$classname);
        if(isset($classes[$key])){
            if(!empty($classes[$key])){
                return $classes[$key];
            }else{
                return true;
            }
        }

        if(file_exists(PC_PATH.$path.DIRECTORY_SEPARATOR.$classname.".class.php")){
            include PC_PATH.$path.DIRECTORY_SEPARATOR.$classname.".class.php";
            if($initialize){
                $classes[$key] = new $classname;
            }else{
                $classes[$key] = true;
            }
        } else{
            return false;
        }
    }


    /**
     * 运行程序
     */
    public static function run(){
        return self::load_sys_class('application');
    }
}