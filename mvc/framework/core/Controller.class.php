<?php
namespace framework\core;
use \Smarty;
class Controller
{
    protected $smarty;
    public function __construct()
    {
        $this->initSmarty();
    }
    public function initSmarty()
    {
        $this->smarty = new Smarty();
        $this->smarty -> left_delimiter = '<{';
        $this->smarty -> right_delimiter = '}>';
        $this->smarty -> setTemplateDir('./view/');
        $this->smarty -> setCompileDir('./view/tpls_c/');
    }
}