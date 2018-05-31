<?php
defined('IN_PHPCMS') or exit('infiltration..');
/**
 * Created by PhpStorm.
 * User: beyond
 * Date: 2018/5/30
 * Time: 14:22
 */
final class mysqlpdo{
    public $pdo;

    public function __construct($db_host,$db_name,$db_username,$db_password,$db_characset)
    {
        try {
            $this->pdo = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_username, $db_password);

            $this->pdo->exec("SET CHARACTER SET " . $db_characset);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->query("set names " . $db_characset);
        }catch(PDOException $e){
            echo "error" .$e->getMessage();
            $this->log("error".$e->getMessage(),'');
        }
    }


    public function fetch($sql){
        $this->log($sql);
        $res = $this->pdo->query($sql);
        $result = $res->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetch_all($sql){
        $this->log($sql);
        $sel = $this->pdo->query($sql);
        $sel->execute();
        $result = $sel->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
     * 执行sql查询
     */
    public function query($sql){
        try {
            $this->log($sql);
            $stmt = $this->pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "";
            $this->log("Error!: " . $e->getMessage() . "",'');
        }
    }





    public function __destruct() {
        $this->pdo = null;
    }


    public  function log($sql,$execute=array()){
        static_logs($sql,'pdosql');
    }
}