<?php

class TempDao
{
    /**
     * 获取项目列表
     * @param $userID int 用户ID
     * @param $projectType int 项目类型[-1/0/1/2/3]=>[全部/Web/App/PC/其他]
     * @return bool|array
     */
    public function getList(&$lang = "cn", &$pageNo = 1, &$pageSize = 10)
    {
        $db = getDatabase();

        $result['list'] = $db->prepareExecuteAll("SELECT SQL_CALC_FOUND_ROWS * FROM met_news WHERE met_news.lang = ? ORDER BY met_news.addtime DESC LIMIT ?,?", array(
            $lang,
            ($pageNo-1) * $pageSize,
            $pageSize
        ));
        $count = $db->prepareExecute("SELECT COUNT(met_news.id) AS count FROM met_news WHERE met_news.lang = ?", array($lang));
        $result['pageNo'] = ceil($pageNo);
        $result['pageSize'] = ceil($pageSize);
        $result['count'] = $count['count'];
        $result['totalPage'] = ceil($result['count']/$pageSize);
        if (empty($result))
            return FALSE;
        else
            return $result;
    }

     /**
     * 获取消息列表
     * @param $userID int 用户ID
     * @param $page int 页码
     * @return bool|array
     */
    public function getMessageList(&$userID, &$page)
    {
        $db = getDatabase();
        $result['messageList'] = $db->prepareExecuteAll('SELECT eo_message.msgID,eo_message.msgType,eo_message.msg,eo_message.summary,eo_message.msgSendTime,eo_message.isRead FROM eo_message WHERE eo_message.toUserID = ? ORDER BY eo_message.msgSendTime DESC LIMIT ?,15;', array(
            $userID,
            ($page - 1) * 15
        ));

        $msgCount = $db->prepareExecute('SELECT COUNT(eo_message.msgID) AS msgCount FROM eo_message WHERE eo_message.toUserID = ?', array($userID));

        $result['msgCount'] = $msgCount['msgCount'];

        if (empty($result['messageList'][0]))
            return FALSE;
        else
            return $result;
    }
}

?>