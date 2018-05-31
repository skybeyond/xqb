<?php
/**
 * Created by PhpStorm.
 * User: beyond
 * Date: 2018/5/24
 * Time: 10:25
 */
class param{

    private $route = '';
    public function __construct()
    {
        if(isset($_SERVER['PATH_INFO'])){
            $pathinfo = array_values(array_filter(explode("/",preg_replace('/\.(html|htm)$/','',$_SERVER['PATH_INFO']))));
            $this->route['m'] = !empty($this->safe_deal($pathinfo[0]))?$this->safe_deal($pathinfo[0]):'index';
            $this->route['c'] = !empty($this->safe_deal($pathinfo[1]))?$this->safe_deal($pathinfo[1]):'index';
            $this->route['a'] = !empty($this->safe_deal($pathinfo[2]))?$this->safe_deal($pathinfo[2]):'init';
        }else{
            $this->route['m'] = isset($_GET['m'])?addslashes($_GET['m']):'index';
            $this->route['c'] = isset($_GET['c'])?addslashes($_GET['c']):'index';
            $this->route['a'] = isset($_GET['a'])?addslashes($_GET['a']):'init';
        }
        return true;
    }


    public function route_m(){
        return $this->route['m'];
    }

    public function route_c(){
        return $this->route['c'];
    }

    public function route_a(){
        return $this->route['a'];
    }


    /**
     * 安全处理函数
     * 处理m,a,c
     */
    private function safe_deal($str) {
        return str_replace(array('/', '.'), '', $str);
    }
}