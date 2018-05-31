<?php
/**
 * Created by PhpStorm.
 * User: beyond
 * Date: 2018/5/30
 * Time: 11:21
 */

class index extends common{
    public function init(){

        $data = $this->model->fetch("select * from xqb_cloumn");
        include template('index');
    }
}