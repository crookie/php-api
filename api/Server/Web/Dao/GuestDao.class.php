<?php

class GuestDao
{
    /**
     * 用户名注册
     * @param $userName string 用户名
     * @param $hashPassword string 密码
     * @param $userNickName string 昵称
     * @return bool
     */
    public function register(&$userName, &$hashPassword, &$userNickName)
    {
        $db = getDatabase();

        //判断是否已存在用户
        $result = $db->prepareExecute('SELECT eo_user.userID FROM eo_user WHERE userName=?;', array($userName));

        //已存在则返回
        if (!empty($result))
            return FALSE;

        //若不存在则插入
        $result = $db->prepareExecute('INSERT INTO eo_user (eo_user.userName,eo_user.userPassword,eo_user.userNickName) VALUES (?,?,?);', array(
            $userName,
            $hashPassword,
            $userNickName
        ));

        //插入成功
        if ($db->getAffectRow() > 0)
            return $db->getLastInsertID();
        else
            return FALSE;
    }

    /**
     * 检查用户名是否存在
     * @param $userName string 用户名
     * @return bool
     */
    public function checkUserNameExist(&$userName)
    {
        $db = getDatabase();

        $result = $db->prepareExecute('SELECT * FROM yiluo_user WHERE yiluo_user.username = ?;', array($userName));

        if (empty($result))
            return TRUE;
        else
            return FALSE;
    }

    /**
     * 获取用户信息
     * @param $loginName string 登录用户名
     * @return bool
     */
    public function getLoginInfo(&$loginName)
    {
        $db = getDatabase();

        $result = $db->prepareExecute('SELECT * FROM yiluo_user WHERE yiluo_user.username = ?;', array($loginName));

        if (empty($result))
            return FALSE;
        else
            return $result;
    }

    /**
     * 获取用户信息
     * @param $userId string 用户名ID
     * @return bool
     */
    public function getUserInfo(&$userId)
    {
        $db = getDatabase();

        $result = $db->prepareExecute('SELECT * FROM yiluo_user WHERE yiluo_user.id = ?;', array($userId));

        if (empty($result))
            return FALSE;
        else
            return $result;
    }

}

?>