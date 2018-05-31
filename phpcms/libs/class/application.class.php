<?php
/**
 * Created by PhpStorm.
 * User: beyond
 * Date: 2018/5/23
 * Time: 16:23
 */
class application{
    public function __construct()
    {
        /*路由*/
        $param = Loader::load_sys_class('param');

        define('ROUTE_M',$param->route_m());
        define('ROUTE_C',$param->route_c());
        define('ROUTE_A',$param->route_a());
        $this->init();
    }


    private function init(){

        $controller = $this->load_controller();
        if (method_exists($controller, ROUTE_A)) {
            if (preg_match('/^[_]/i', ROUTE_A)) {
                exit('You are visiting the action is to protect the private action');
            } else {
                call_user_func(array($controller, ROUTE_A));
            }
        } else {
            exit('Action does not exist.');
        }
    }


    private function load_controller($filename='',$m=''){
        if(empty($filename)) $filename = ROUTE_C;
            if(class_exists($filename)){
                return new $filename;
            }else{
                exit('Class does not exist..');
            }

    }
}