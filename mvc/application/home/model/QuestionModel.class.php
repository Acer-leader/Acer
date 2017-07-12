<?php
namespace home\model;
use framework\core\Model;

class QuestionModel extends Model
{
    protected $logic_table = 'question';
    
    public function getAllQuestions()
    {
        $sql = "SELECT * FROM ask_question";
        return $this->dao->fetchAll($sql);
    }
    public function question_add()
    {
        $sql = "INSERT INTO ask_question VALUES()";
    }
}