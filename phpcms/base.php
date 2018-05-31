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
//自动加载所有函数
Loader::auto_load_func();

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
     * 加载所有公用函数库
     * @param string $path
     */
    public static function auto_load_func($path = ''){
        if(empty($path)) $path = 'libs'.DIRECTORY_SEPARATOR.'functions';
        $path .= DIRECTORY_SEPARATOR.'*.func.php';
        $auto_funcs = glob(PC_PATH.DIRECTORY_SEPARATOR.$path);
        if(!empty($auto_funcs) && is_array($auto_funcs)){
            foreach($auto_funcs as $func_path){
                include $func_path;
            }
        }
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
            return $classes[$key];
        } else{
            return false;
        }
    }


    /**
     * 自动加载类
     */
    public static function auto_class_load($classname){
        $classfile = PC_PATH.DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.$classname.'.class.php';
        if(file_exists($classfile)){
            include_once $classfile;
        }
    }

    /**
     * 自动公共类
     */
    public static function auto_commonclass_load($classname){
        $classfile = PC_PATH.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.$classname.'.class.php';
        if(file_exists($classfile)){
            include_once $classfile;
        }
    }





    /**
     * 运行程序
     */
    public static function run(){
        spl_autoload_register(array('Loader','auto_class_load'));
        spl_autoload_register(array('Loader','auto_commonclass_load'));
        return self::load_sys_class('application');
    }

    public static function load_config(){
        $hostname = gethostname();

        $host_config = PC_PATH."config".DIRECTORY_SEPARATOR.$hostname.".php";
        if(file_exists($host_config)){
            return include_once $host_config;
        }else{
            return include_once PC_PATH."config".DIRECTORY_SEPARATOR."default.php";
        }

    }
}