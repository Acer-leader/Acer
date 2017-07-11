<?php
/**
 * @authors  Acer (y1wanghui@163.com)
 * @blog       
 * @date     2015年5月25日下午3:46:41   
 */

date_default_timezone_set('PRC');
//图片压缩
class Thumb
{
    //待压缩的图片
    private $file;//图片的路径 
    //创建图像资源函数，不同类型的函数用不同的函数
    private $create_func = array(
        'image/png'   => 'imagecreatefrompng',
        'image/jpeg'  => 'imagecreatefromjpeg',
        'image/gif'   => 'imagecreatefromgif',
        'image/jpg'   => 'imagecreatefromgif'
    );
    //定义保存图像的资源的函数
    private $output_func = array(
        'image/png'  => 'imagepng',
        'image/jpeg' => 'imagejpeg',
        'image/gif'  => 'imagegif',
        'image/jpg'  => 'imagejpg'        
    );
    //文件的的mime类型
    private $mime;    
    //压缩图片的保存路径
    private $thumb_path;
    
    public function __set($p,$v)
    {
        if (property_exists($this, $p))
        {
            $this->$p = $v;
        }
    }
   public function __get($p)
   {
       if (property_exists($this, $p))
       {
           return $this->$p;
       }
   }
   public function __construct($file)
   {
       if (!file_exists($file))
       {
           echo '文件无效，请重新选择图像';
           exit;
       }
       //文件有效
       $this->file = $file;
       //获得文件的mime类型
       $this->mime = getimagesize($file)['mime'];
   }
   //将图片压缩进行处理
   public function makeThumb($area_w,$area_h)
   {
       //原图
       $create_func = $this->create_func[$this->mime];
       $src_image = $create_func($this->file);
       //落笔点
       $dst_x = 0;
       $dst_y = 0;
       //从哪里采样
       $src_x = 0;
       $src_y = 0;
       
       //原图的高度和宽度 用函数获取
       $src_w = imagesx($src_image);
       $src_h = imagesy($src_image);
       
       //根据图像的资源压缩比例为100*300
       
       if($src_w / $area_w >= $src_h / $area_h){
           $scale = $src_w / $area_w;
       }else{
           $scale = $src_h / $area_h;
       }
       
       $dst_w = (int)$src_w / $scale;   //向上取整
       $dst_h = (int)$src_h / $scale;
       //画布
       $dst_image = imagecreatetruecolor($dst_w, $dst_h);
       //画布的宽度高度（目标的图像资源的宽度和高度）
       $color = imagecolorallocatealpha($dst_image, 255, 255, 255, 127);
       imagealphablending($dst_image, false);  //关闭混合模式    以便透明颜色能够覆盖画布
       //将白色转化成 透明色  imagecolortransparent — 将某个颜色定义为透明色
       $color = imagecolortransparent($dst_image,$color);  
       //在填充
       imagefill($dst_image, 0, 0, $color);
       imagesavealpha($dst_image, true);
       imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
       //将压缩的图片保存起来的路径
       $output_func = $this->output_func[$this->mime];
       ////确定压缩文件保存的路径
       $sub_path = date('Ymd').'/';
       if (!is_dir($this->thumb_path.$sub_path))
       {
           mkdir($this->thumb_path.$sub_path,0777,true);
       }
       //确定压缩文件 叫什么名字 不管文件前面是什么路径 只需要文件名称部分，通过basename 函数获得
       $thumb_name = 'thumb_'.basename($this->file);
       $output_func($dst_image,$this->thumb_path.$sub_path.$thumb_name);
       //不写参数直接在浏览器里面直接输出
       //返回压缩图片的地址
       //在浏览器中输出      
//        header("Content-Type:image/png");
//        imagepng($image);
       return $sub_path.$thumb_name;       
   }   
}

$thumb = new Thumb('C:\Users\Administrator\Pictures\Saved Pictures\yin.jpg');
$thumb -> thumb_path = 'thumb/';
$thumb -> makeThumb(720, 480);





















