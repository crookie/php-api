<?php

class UserModule
{
    public function __construct()
    {
        @session_start();
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo()
    {
        // $dao = new MessageDao;
        // $server = new GuestModule;

        // $auth =  $_SERVER['HTTP_TOKEN'];
        // $user = json_decode(authcode($auth,'DECODE'));
        // $userInfo['userID'] = $user->userId;
        // $userInfo['userName'] = $user['userName'];
        $guestDao = new GuestDao;
        $userId = getUserInfo('userId');
        $user = $guestDao->getUserInfo($userId);
        $userInfo['id'] = $user['id'];
        $userInfo['username'] = $user['username'];
        $userInfo['email'] = $user['email'];
        $userInfo['tel'] = $user['tel'];
        $userInfo['groupid'] = $user['groupid'];
        $userInfo['head'] = $user['head'];
        $userInfo['login_time'] = $user['login_time'];
        $userInfo['register_time'] = $user['register_time'];
        return $userInfo;
    }

    /**
     * 修改密码
     * @param $oldPassword string 旧密码
     * @param $newPassword string 新密码
     * @return bool
     */
    public function changePassword(&$oldPassword, &$newPassword)
    {
        $guestDao = new GuestDao;
        $userDao = new UserDao;
        $userInfo = $guestDao->getLoginInfo($_SESSION['userName']);

        if (md5($oldPassword) == $userInfo['userPassword']) {
            return $userDao->changePassword(md5($newPassword), $_SESSION['userID']);
        } else
            return FALSE;
    }

    /**
     * 修改昵称
     * @param $nickName string 昵称
     * @return bool
     */
    public function changeNickName(&$nickName)
    {
        $dao = new UserDao;
        if ($dao->changeNickName($_SESSION['userID'], $nickName)) {
            $_SESSION['userNickName'] = $nickName;
            return TRUE;
        } else
            return FALSE;
    }

    /**
     * 检查用户是否存在
     * @param $userName string 用户名
     * @return bool|array
     */
    public function checkUserExist(&$userName)
    {
        $dao = new UserDao;
        return $dao->checkUserExist($userName);
    }

}

?>