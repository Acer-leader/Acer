<?php
namespace framework\dao;
interface I_DAO
{
    //查询一条记录
    public function fetchOne($sql);
    //查询所有的记录
    public function fetchAll($sql);
    //查询一个字段的值
    public function fetchColumn($sql);
    //执行增删改
    public function exec($sql);
    //引号转义并包裹
    public function quote($data);
    //返回刚刚插入的记录的主键值
    public function lastId();
}