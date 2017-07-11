<?php
/**
 * @authors  Acer (y1wanghui@163.com)
 * @blog       
 * @date     2015年5月25日下午3:46:41   
 */

class Upload
{
    //允许的文件最大值
    private $max_size = 2 * 1024 * 1024;
    //文件名称的前缀
    private $prefix = 'itbull_';
    //上传的目标路径
    private $upload_path = './uploads/';
    //允许上传的文件类型
    private $allow_type = array('image/png','image/jpg','image/gif','image/jpeg');
    
    public function __set($p,$v)
    {
        if(property_exists($this, $p)){
            $this->$p = $v;
        }
    }
    public function __get($p)
    {
        if(property_exists($this, $p)){
            return $this->$p;
        }
    }
    
    //该方法实现文件上传的具体功能
    //参数：上传的文件信息：$_FILES[]
    public function doUpload($file)
    {
        date_default_timezone_set('PRC');
        //1. 上传的文件大小和 需求的进行比较
        if($file['size'] > $this->max_size){
            //说明上传的文件超出了限制
            echo '您上传的文件太大了';
            die;
        }
        //2. 防止目标文件名重复
        //参数1：前缀，生成的唯一的字符串的前缀
        //参数2：布尔值，true的话表示更具有唯一性
        $filename = uniqid($this->prefix,true);
        //获得文件的后缀：.png  .jpg等
        //参数1：$haystack草堆、草垛子
        //参数2： $needle针
        //查找字符串中最后一个字符后面的内容
        $ext = strrchr($file['name'], '.');
        
        $final_filename = $filename . $ext;
        
        
        //3. 为了便于图片的管理，我们采用日期的形式分目录存储
        $sub_path = date('Ymd').'/';   //./application/uploads/20170514/
        if(!is_dir($this->upload_path.$sub_path)){
            mkdir($this->upload_path.$sub_path,0777,true);
        }
        $this->upload_path .= $sub_path.$final_filename;
        
        //4. 判断用户上传的文件类型 是否在我们允许的范围内
        $allow_type = $this->allow_type;
        if(!in_array($file['type'], $allow_type)){
            echo '文件格式不正确';
            exit;
        }
        //5. 通过fileinfo扩展中提供的一个类：finfo()获得真实类型
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $type = $finfo ->file($file['tmp_name']);
        if(!in_array($type, $allow_type)){
            echo '文件格式不正确';
            exit;
        }
        if(move_uploaded_file($file['tmp_name'], $this->upload_path)){
            //echo '上传成功';
            //将服务器的文件地址返回,通常返回子目录后面的地址
            return $sub_path.$final_filename;
        }else{
            return false;
        }
    }
}