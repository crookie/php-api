<?php

class UserController
{
    // 返回json类型
    private $returnJson = array('type' => 'user');

    /**
     * 检查登录状态
     */
    public function __construct()
    {
        // 身份验证
        $server = new GuestModule;
        if (!$server->checkLogin()) {
            $this->returnJson['statusCode'] = '120005';
            exitOutput($this->returnJson);
        }
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        @session_start();
        @session_destroy();
        $this->returnJson['statusCode'] = '000000';
        exitOutput(json_encode($this->returnJson));
    }

    /**
     * 修改密码
     */
    public function changePassword()
    {
        $oldPassword = securelyInput('oldPassword');
        $newPassword = securelyInput('newPassword');

        if (!preg_match('/^[0-9a-zA-Z]{32}$/', $newPassword) || !preg_match('/^[0-9a-zA-Z]{32}$/', $oldPassword)) {
            //密码非法
            $this->returnJson['statusCode'] = '130002';
        } elseif ($oldPassword == $newPassword) {
            //密码相同
            $this->returnJson['statusCode'] = '000000';
        } else {
            $server = new UserModule;
            $result = $server->changePassword($oldPassword, $newPassword);

            if ($result) {
                $this->returnJson['statusCode'] = '000000';
            } else {
                $this->returnJson['statusCode'] = '130006';
            }
        }
        exitOutput($this->returnJson);
    }

    /**
     * 修改昵称
     */
    public function changeNickName()
    {
        $nickNameLength = mb_strlen(quickInput('nickName'), 'utf8');
        $nickName = securelyInput('nickName');

        if ($nickNameLength > 20) {
            //昵称格式非法
            $this->returnJson['statusCode'] = '130008';
        } else {
            $server = new UserModule;
            $result = $server->changeNickName($nickName);

            if ($result) {
                $this->returnJson['statusCode'] = '000000';
            } else {
                $this->returnJson['statusCode'] = '130009';
            }
        }
        exitOutput($this->returnJson);
    }

    /**
     * 确认用户名
     */
    public function confirmUserName()
    {
        $userName = securelyInput('userName');

        //验证用户名,4~16位非纯数字，英文数字下划线组合，只能以英文开头
        if (!preg_match('/^[a-zA-Z][0-9a-zA-Z_]{3,59}$/', $userName)) {
            //用户名非法
            $this->returnJson['statusCode'] = '130001';
        } else {
            $server = new UserModule;
            $result = $server->confirmUserName($userName);

            if ($result) {
                $this->returnJson['statusCode'] = '000000';
            } else {
                $this->returnJson['statusCode'] = '130010';
            }
        }
        exitOutput($this->returnJson);
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo()
    {
        $server = new UserModule;
        $result = $server->getUserInfo();
        // $result = getUserInfo();
        if ($result) {
            $this->returnJson['statusCode'] = '000000';
            $this->returnJson['userInfo'] = $result;
        } else {
            $this->returnJson['statusCode'] = '130013';
        }
        exitOutput($this->returnJson);
    }

}

?>