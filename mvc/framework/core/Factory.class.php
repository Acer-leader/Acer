<?php
namespace framework\core;

class Factory
{
    public static function M($modelName)
    {        
        static $model_list = array();
        if(!isset($model_list[$modelName])){
            $modelName = MODULE.'\model\\'.$modelName;  //new admin\model\UserModel
            $model_list[$modelName] = new $modelName;
        }
        return $model_list[$modelName];
    }
}