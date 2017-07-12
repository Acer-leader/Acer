<?php
namespace framework\core;

class Framework
{
    public function __construct()
    {
        //初始化自动加载
        $this->initAutoload();
        
        //合并覆盖配置文件
        $config1 = $this->loadFrameworkConfig();
        $config2 = $this->loadCommonConfig();
        $GLOBALS['config'] = array_merge($config1,$config2);
        
        //初始化MCA
        $this->initMCA();
        $config3 = $this->loadModuleConfig();
        $GLOBALS['config'] = array_merge($GLOBALS['config'],$config3);
               
        //开始执行
        $this->initDispatch();
    }
    public function loadFrameworkConfig()
    {
        $file = './framework/config/config.php';
        return require_once $file;
    }
    public function loadCommonConfig()
    {
        $file = './application/common/config/config.php';
        return require_once $file;
    }
    
    public function loadModuleConfig()
    {
        //具体模块的配置：当前请求的是admin 还是home,保存到常量里面
        $file = './application/'.MODULE.'/config/config.php';
        
        return require_once $file;
    }

    public function initAutoload()
    {
        //根据提示信息，做自动加载，在没有找到类的时候，提供最后一次机会
        //参数是一个回调函数，因为将来当我们需要一个类，没有找到这个类的时候，就会调用该函数
        //并且将需要的类名传递进去
        spl_autoload_register(array($this,"userAutoload"));
    }    
    public function userAutoload($className)
    {
        echo '需要的类：'.$className.'<br>';
        if($className=='Smarty'){
            require_once './framework/vendor/smarty/Smarty.class.php';
            return;
        }
        //1. 根据\将字符串拆分成数组
        $arr = explode('\\', $className);
    
        //命名空间的规则：admin\controller   home\controller    framework\core
        if($arr[0] == 'framework'){
            //加载的根根目录就是framework
            $base_path = './';
        }else{
            //加载的根根目录就是application
            $base_path = './application/';
        }
        //2. 将类的命名空间的\转换成/，home\controller   ----》    home/controller
        $sub_path = str_replace('\\', '/', $className);
    
        //最后判断后缀是 .interface.php 还是.class.php
        if(substr($arr[count($arr)-1],0,2)=='I_'){
            //说明是接口文件，例如：framwork\dao\I_DAO
            $last_fix = '.interface.php';
        }else{
            //说明是类文件，例如：admin\controller\CategporyController
            $last_fix = '.class.php';
        }
    
        $class_file = $base_path.$sub_path.$last_fix;
        //表示符合我们规则：application/admin/xxxController    framework/core/xxx.class
        if(file_exists($class_file)){
            require_once $class_file;
        }
    }
    
    //初始化MCA
    public function initMCA()
    {
        //接收地址栏传递的mca
        $m = isset($_GET['m'])?$_GET['m']:$GLOBALS['config']['default_module'];
        define('MODULE',$m);
        $c = isset($_GET['c'])?$_GET['c']:$GLOBALS['config']['default_controller'];
        define('CONTROLLER',$c);
        $a = isset($_GET['a'])?$_GET['a']:$GLOBALS['config']['default_action'];
        define('ACTION',$a);
    }
    //开始执行
    public function initDispatch()
    {
        $controllerName = CONTROLLER.'Controller';
        //先加载控制器类，并实例化对象
        $controllerName = MODULE.'\controller\\'.$controllerName;
        $controller = new $controllerName;
        
        //调用控制器对象的方法
        $a = ACTION;
        $controller -> $a();
    }
    
}