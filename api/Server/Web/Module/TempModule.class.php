<?php

class TempModule
{
    public function __construct()
    {
        @session_start();
    }

    /**
     * 获取项目列表
     * @param $projectType int 项目类型 [-1/0/1/2/3]=>[全部/Web/App/PC/其他]
     * @param $projectName string 项目名
     * @return bool|array
     */
    public function getList(&$lang = "cn", &$pageNo = 1, &$pageSize = 10)
    {
        $dao = new TempDao;
        return $dao->getList($lang, $pageNo, $pageSize);
    }

}

?>