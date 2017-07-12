<?php
namespace framework\core;
use framework\dao\DAOPDO;

class Model
{
    protected $dao;
    protected $true_table;
    protected $pk;
    
    public function __construct()
    {
        //初始化真实的表名
        $this->initTrueTable();
        
        //初始化dao对象
        $option = $GLOBALS['config'];
        
        $this->dao = DAOPDO::getSingleton($option);
        
    }
    public function initTrueTable()
    {
        $this->true_table = '`'.$GLOBALS['config']['table_prefix'].$this->logic_table.'`';
    }
    
    //封装自动插入数据的方法
    //实现的是：封装，INSERT INTO user(username,password) VALUES('zhangsan','admin123')
    //将来insert()只需要告诉我向哪个字段插入什么数据u即可
    //insert(array('字段名'=>'字段值','字段名'=>'字段值'));
    public function insert($data)
    {
        $sql = "INSERT INTO $this->true_table";
        
        //foreach
        $keys = [];
        $values = [];
        foreach ($data as $k=>$v){
            $keys[] = '`'.$k.'`';
            $values[] = $this->dao->quote($v);
        }
        $fields = implode(',', $keys);  // `username`,`password`
        $sql .= '('.$fields.')';        // (`username`,`password`)
        
        //拼接字段值
        $values = implode(',', $values);    //  'zhangsanfeng','admin123'
        $sql .= ' VALUES('.$values.')';
        
        $this->dao->exec($sql);
        return $this->dao->lastId();
    }
    //初始化一张数据表的字段结构
    public function initField()
    {
        $sql = "DESC $this->true_table";
        $result = $this->dao->fetchAll($sql);
        foreach($result as $k=>$v)
        {
            if($v['Key']=='PRI'){
                //说明这条记录就是主键字段
                $this->pk = $v['Field'];
            }
        }
    }
    //自动删除
    //参数主键的值
    //目标：DELETE FROM ask-question WHERE 主键字段=$pk
    public function delete($pk)
    {
        $sql = "DELETE FROM $this->true_table WHERE $this->pk=$pk";
        return $this->dao->exec($sql);
    }
    
    //自动更新
    //update(array('username'=>'lisiguang','password'=>'admin123'),array('username'=>'zhangsanfeng'))
    public function update($data,$where=array())
    {
        if(!$where){
            return false;
        }else{
            foreach($where as $k=>$v){
              $where_str = '`'.$k.'`'."='$v'";  
            }
        }
        //拼接更新的字段部分
        $keys = [];
        foreach($data as $k=>$v){
            //`username`='zhangsanfeng'   `password`='admin123'
            $keys[] = '`'.$k.'`='.$this->dao->quote($v);
        }
        $fields = implode(',', $keys);  //`username`='zhangsanfeng',`password`='admin123'
        
        $sql = "UPDATE $this->true_table SET $fields WHERE $where_str";
        return $this->dao->exec($sql);
        
    }
    
}