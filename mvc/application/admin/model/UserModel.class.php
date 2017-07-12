<?php
namespace admin\model;
use framework\core\Model;

class UserModel extends Model
{    
    public function user_add()
    {
        $sql = "INSERT INTO user VALUES(null,'zhangsan','admin123')";
        $this->dao->insert($sql);   
    }
    public function user_select()
    {
        $sql = "SELECT * FROM user";
        
    }
}