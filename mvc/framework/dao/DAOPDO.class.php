<?php
namespace framework\dao;
use framework\dao\I_DAO;
use \PDO;
class DAOPDO implements I_DAO
{
    //私有的静态属性保存当前类的实例
    private static $instance;
    
    //pdo对象属性
    private $pdo;
    
    //构造方法私有化，让类无法再类外面new
    private function __construct($option)
    {
        $host = isset($option['host'])?$option['host']:'';
        $dbname = isset($option['dbname'])?$option['dbname']:'';
        $port = isset($option['port'])?$option['port']:'';
        $user = isset($option['user'])?$option['user']:'';
        $pass = isset($option['pass'])?$option['pass']:'';
        $charset = isset($option['charset'])?$option['charset']:'';
        
        $dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=$charset";
        
        $this->pdo = new PDO($dsn,$user,$pass);
    }
    //让克隆方法私有化,让类无法克隆
    private function __clone()
    {
        
    }
    public static function getSingleton($option)
    {
        //每次实例化当前类的对象时，先看一下当前是否存在该实例
        if(!self::$instance instanceof self){
            self::$instance = new self($option);
        }
        return self::$instance;
    }
    //查询一条记录
    public function fetchOne($sql){
        $pdo_statement = $this->pdo->query($sql);
        //执行查询操作时，可能会出错
        if($pdo_statement == false){
            //打印错误信息
            $error_info = $this->pdo->errorInfo();
            $error_info = $error_info[2];
            $str = "SQL语句有错误，详细信息如下:<br>".$error_info;
            echo $str;
            return false;
        }        
        return $pdo_statement -> fetch(PDO::FETCH_ASSOC);
    }
    //查询所有的记录
    public function fetchAll($sql){
        $pdo_statement = $this->pdo->query($sql);
        //执行查询操作时，可能会出错
        if($pdo_statement == false){
            //打印错误信息
            $error_info = $this->pdo->errorInfo();
            $error_info = $error_info[2];
            $str = "SQL语句有错误，详细信息如下:<br>".$error_info;
            echo $str;
            return false;
        }
        return $pdo_statement -> fetchAll(PDO::FETCH_ASSOC);
    }
    //查询一个字段的值
    public function fetchColumn($sql){
        $pdo_statement = $this->pdo->query($sql);
        //执行查询操作时，可能会出错
        if($pdo_statement == false){
            //打印错误信息
            $error_info = $this->pdo->errorInfo();
            $error_info = $error_info[2];
            $str = "SQL语句有错误，详细信息如下:<br>".$error_info;
            echo $str;
            return false;
        }
        return $pdo_statement -> fetchColumn();
    }
    //执行增删改
    public function exec($sql){
        $result = $this->pdo->exec($sql);  
        //如果删除一条不存在的记录的时候，返回受影响的记录数就是0
        if($result === false){
            //获得错误信息
            $error_info = $this->pdo->errorInfo();
            $error_info = $error_info[2];
            $str = "SQL语句有错误，详细信息如下:<br>".$error_info;
            echo $str;
            return false;
        }
        return $result;
    }
    //引号转义并包裹
    public function quote($data){
        //针对sql注入
        return $this->pdo->quote($data);
    }
    //返回刚刚插入的记录的主键值
    public function lastId(){
        return $this->pdo->lastInsertId();
    }
}

// $option = array(
//     'host'      =>  '127.0.0.1',
//     'dbname'    =>  'php_8',
//     'port'      =>  3306,
//     'user'      =>  'root',
//     'pass'      =>  'root',
//     'charset'   =>  'utf8'
// );

// $dao1 = DAOPDO::getSingleton($option);
