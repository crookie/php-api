<?php

class TempController
{
    // 返回json类型
    private $returnJson = array('type' => 'temp');

    /**
     * 检查登录状态
     */
    public function __construct()
    {
        // 身份验证
        $server = new GuestModule;
        
        // $obj['userName'] = 'admin';
        // $obj['userId'] = 1;
        // $jon = json_encode($obj);
        // $this->returnJson['statusCode'] = '120005';
        // // // $this->returnJson['code'] =  authcode($jon,'ecode','',3600);
        // $this->returnJson['dcode'] =  $server->checkLogin();
        // $this->returnJson['auth'] =  $_SERVER['HTTP_AUTHORIZATION'];
        // exitOutput($this->returnJson);
        if (!$server->checkLogin()) {
            $this->returnJson['statusCode'] = '120005';
            exitOutput($this->returnJson);
        }
    }

    /**
     * 退出登录
     */
    public function getList()
    {
        $lang = securelyInput('lang');
        $pageNo = securelyInput('pageNo');
        $pageSize = securelyInput('pageSize');

        $service = new TempModule();
        $result = $service->getList($lang, $pageNo, $pageSize);
        if ($result) {
            // 获取项目列表成功
            $this->returnJson['statusCode'] = '000000';
                        
            $result['header'] = $_SERVER['HTTP_TOKEN'];
            $result['cookie'] = $_COOKIE['PHPSESSID'];
            $this->returnJson['data'] = $result;
        } else {
            // 获取项目列表失败
            $this->returnJson['statusCode'] = '140005';
        }

        exitOutput($this->returnJson);
    }

}

?>