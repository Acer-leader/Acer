<?php
/**
 * @authors  Acer (y1wanghui@163.com)
 * @blog       
 * @date     2016年5月25日上午11:42:56   
 */
class Captcha
{
    //验证图像的宽度
    private $width = 150;
    //验证图像的高度
    private $height = 40;
    //验证码的大小
    private  $font_size = 25;
    //验证码的个数
    private $number = 5;
    //验证码的字体 
    private $font_file = 'STCAIYUN.TTF';    
//生成验证码图像 （图片里绘制文字 和线条）
    public function makeImage()
    {
        //创建画布
        $image = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($image, mt_rand(100,250), mt_rand(100,250), mt_rand(100,250));        
        imagefill($image, 0, 0, $color);
        //向画布中输入随机的的5个字符
        $code = $this->makeCode();
        //将生成的验证码保存起来
        session_start(); //缓存启动
        $_SESSION['code']= $code;//缓存 藏在$code 
        
        $color = imagecolorallocate($image, mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));
        //绘制字体
        for ($i=0;$i<strlen($code);$i++)
        {
            $x = ($this->width/$this->number)*$i+5;
            imagettftext($image, $this->font_size, mt_rand(-30,30), $x, 25, $color, $this->font_file, $code[$i]);            
        }
        //绘制200个像素点
        $color = imagecolorallocate($image, mt_rand(0,50), mt_rand(0,50), mt_rand(0,50));
        for ($i=0;$i<200;$i++)
        {
            imagesetpixel($image, mt_rand(0,$this->width), mt_rand(0,$this->height), $color);            
        }
        //绘制15个干扰线条
        $color = imagecolorallocate($image, mt_rand(0,50), mt_rand(0,50), mt_rand(0,50));
        for ($i=0;$i<15;$i++)
        {
            imageline($image, mt_rand(0,$this->width), mt_rand(0,$this->height), mt_rand(0,$this->width), mt_rand(0,$this->height), $color);
        }
        //在浏览器中输出
        header("Content-Type:image/png");
        imagepng($image);
    }
    //生成随机的字符
    public function makeCode()
    {
        $super = range('A','Z');
        $lower = range('a','z');
        $lower= range(3,8);
        //$font = array('社','会','你','会','哥','最','帅');
        //将上面的合成一个数组
        $arr = array_merge($super,$lower,$lower);
        //打乱数组排序，引用传递
        shuffle($arr);
        //再从这个数组中取出5个随机的数字
        $str = '';
        for ($i=0;$i<$this->number;$i++)
        {
            $str .=$arr[$i];
        }
        return $str;
    }
}
$captcha = new Captcha();
$captcha->makeImage();
//var_dump($captcha->makeImage());






    