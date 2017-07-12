<?php
namespace home\controller;
use framework\core\Controller;
use framework\core\Factory;

class IndexController extends Controller
{
    public function indexAction()
    {
        //命令模型查询数据，查询问题列表
        $m_question = Factory::M('QuestionModel');
        
        var_dump($m_question);
        die;
        $data['question_title'] = 'MVC是什么？？？';
        $data['user_id'] = 2;
        $id = $m_question -> insert($data);
        var_dump($id);
        die;
        //命令视图显示数据
        $this->smarty->assign('list',$list);
        $this->smarty->display('question.html');
    }
}