<?php
/**
 * Created by PhpStorm.
 * User: beyond
 * Date: 2018/5/30
 * Time: 12:21
 */
abstract class common{ //抽象类不能直接实例化

    public $model;


    public function __construct()
    {
        $this->model = new mysqlpdo(cnf('DB_HOST'),cnf('DB_NAME'),cnf('DB_USER'),cnf('DB_PWD'),'utf8');
    }

}