<?php

class UserDao
{
    /**
     * 修改密码
     * @param $hashPassword string 新密码
     * @param $userID int 用户ID
     * @return bool
     */
    public function changePassword($hashPassword, $userID)
    {
        $db = getDatabase();

        $db->prepareExecute('UPDATE eo_user SET eo_user.userPassword =? WHERE eo_user.userID = ?;', array(
            $hashPassword,
            $userID
        ));

        if ($db->getAffectRow() < 1)
            return FALSE;
        else
            return TRUE;
    }

    /**
     * 修改昵称
     * @param $userID int 用户ID
     * @param $nickName string 昵称
     * @return bool
     */
    public function changeNickName(&$userID, &$nickName)
    {
        $db = getDatabase();
        $db->prepareExecute('UPDATE eo_user SET eo_user.userNickName =? WHERE eo_user.userID = ?;', array(
            $nickName,
            $userID
        ));

        if ($db->getAffectRow() < 1)
            return FALSE;
        else
            return TRUE;
    }

    /**
     * 检查用户是否存在
     * @param $userName 用户名
     * @return bool|array
     */
    public function checkUserExist(&$userName)
    {
        $db = getDatabase();
        $result = $db->prepareExecute('SELECT yiluo_user.id,yiluo_user.username FROM yiluo_user WHERE eo_user.username = ?;', array($userName));

        if (empty($result))
            return FALSE;
        else
            return $result;
    }

}

?>