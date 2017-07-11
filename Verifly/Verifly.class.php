<?php
/*  
 * 用户名、密码、手机号码、邮箱的验证规则
 */
class Verify
{
    public $error = [];   
    //遍历错误信息
    public function showError()
    {
        $err_str = '';
        foreach ($this->error as $k=>$v){
            $err_str .= $v.'<br>';
        }
        return $err_str;
    }
    
    //验证用户名
    public function checkUser($username,$min=6,$max=30)
    {
        $reg = '/^[a-zA-Z]\w{'.($min-1).','.($max-1).'}$/';
        
        preg_match($reg, $username, $result);
        if($result){            
            return true;
        }else{
            $this->error[] = '6-30位的字母、数字、下划线组合，字母开头';
            //空数组的时候，不符合规则
            return false;
        }
    }
    
    //验证手机号码
    //参数：需要验证的电话号码
    public function checkPhone($phone)
    {
        $reg = '/^1[34578]\d{9}$/';        //11位的电话号码
        preg_match($reg, $phone, $result);
        if($result){
            return true;
        }else{
            $this->error[] = '手机号码格式不正确';
            //空数组的时候，不符合规则
            return false;
        }        
    }
    
    //验证密码
    //参数1，待验证的密码
    //参数2、3最少多少位、最多多少位
    public function checkPass($password,$min=6,$max=20)
    {
        //1. 先定义一个纯字母的规则，数量为6-20位
        $reg1 = '/^[a-zA-Z]{'.$min.','.$max.'}$/';
        
        //2. 纯数字的规则，数量为6-20位
        $reg2 = '/^\d{'.$min.','.$max.'}$/';
        
        //3. 字母、数字组合的规则，数量为6-20位
        $reg3 = '/^[a-zA-Z\d]{'.$min.','.$max.'}$/';
        
        //4. 字母、数字、特殊符号的组合
        $reg4 = '/^[a-zA-Z\d!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|\\:;\'\",\.<>\/\?]{'.$min.','.$max.'}$/';      
        
        preg_match($reg1, $password,$res1);
        preg_match($reg2, $password,$res2);
        preg_match($reg3, $password,$res3);
        preg_match($reg4, $password,$res4);
        
        if($res1 || $res2){
            //说明符合纯字母、纯数字的规则
            $this->error[] = '密码太弱了';
            return true;
        }else if($res3){
            //说明符合字母、数字组合的规则
            $this->error[] = '密码强度一般';
            return true;
        }else if($res4){
            //字母、数字、特殊符号的组合
            $this->error[] = '密码杠杠滴';
            return true;
        }else{
            $this->error[] = '密码不符合规则';
            return false;
        }
    }
    //邮箱验证
    public function checkEmail($email)
    {    
        $reg = '/^[\w\.\-]+@([a-zA-Z\d]+\.)?[a-zA-Z\d]+\.[a-zA-Z]{2,3}$/';
        $reg = '/^([a-zA-Z0-9_-])+@([a-zA-Z0-9])+(.[a-zA-Z0-9])+/';        
        //开始筛选
        preg_match($reg, $email,$result);        
        if($result){
            return true;
        }else{
            $this->error[] = '邮箱格式不正确';
            return false;
        }
    }
    
}
