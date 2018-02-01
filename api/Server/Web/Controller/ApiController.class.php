<?php

/**
 * @name eolinker open source，eolinker开源版本
 * @link https://www.eolinker.com
 * @package eolinker
 * @author www.eolinker.com 广州银云信息科技有限公司 ©2015-2016
 *  * eolinker，业内领先的Api接口管理及测试平台，为您提供最专业便捷的在线接口管理、测试、维护以及各类性能测试方案，帮助您高效开发、安全协作。
 * 如在使用的过程中有任何问题，欢迎加入用户讨论群进行反馈，我们将会以最快的速度，最好的服务态度为您解决问题。
 * 用户讨论QQ群：284421832
 *
 * 注意！eolinker开源版本仅供用户下载试用、学习和交流，禁止“一切公开使用于商业用途”或者“以eolinker开源版本为基础而开发的二次版本”在互联网上流通。
 * 注意！一经发现，我们将立刻启用法律程序进行维权。
 * 再次感谢您的使用，希望我们能够共同维护国内的互联网开源文明和正常商业秩序。
 *
 */
class ApiController
{
    // return an json object
    // 返回json类型
    private $returnJson = array('type' => 'api');

    /**
     * Checkout login status
     * 验证登录状态
     */
    public function __construct()
    {
        // identity authentication
        // 身份验证
        $server = new GuestModule;
        if (!$server->checkLogin()) {
            $this->returnJson['statusCode'] = '120005';
            exitOutput($this->returnJson);
        }
    }

    /**
     * Add api
     * 添加api
     */
    public function addApi()
    {
        $groupID = securelyInput('groupID');
        $module = new GroupModule();
        $userType = $module->getUserType($groupID);
        if ($userType < 0 || $userType > 2) {
            $this->returnJson['statusCode'] = '120007';
            exitOutput($this->returnJson);
        }
        $apiName = securelyInput('apiName');
        $apiURI = securelyInput('apiURI');
        $apiProtocol = securelyInput('apiProtocol');
        $apiRequestType = securelyInput('apiRequestType');
        $apiSuccessMock = quickInput('apiSuccessMock');
        $apiFailureMock = quickInput('apiFailureMock');
        $apiStatus = securelyInput('apiStatus');
        $starred = securelyInput('starred');
        $apiNoteType = securelyInput('apiNoteType');
        $apiNoteRaw = securelyInput('apiNoteRaw');
        $apiNote = securelyInput('apiNote');
        $apiRequestParamType = securelyInput('apiRequestParamType');
        $apiRequestRaw = securelyInput('apiRequestRaw');
        $apiHeader = json_decode($_POST['apiHeader'], TRUE);
        $apiRequestParam = json_decode($_POST['apiRequestParam'], TRUE);
        $apiResultParam = json_decode($_POST['apiResultParam'], TRUE);

        $service = new ApiModule;
        $result = $service->addApi($apiName, $apiURI, $apiProtocol, $apiSuccessMock, $apiFailureMock, $apiRequestType, $apiStatus, $groupID, $apiHeader, $apiRequestParam, $apiResultParam, $starred, $apiNoteType, $apiNoteRaw, $apiNote, $apiRequestParamType, $apiRequestRaw);
        if ($result) {
            $this->returnJson['statusCode'] = '000000';
            $this->returnJson['apiID'] = $result['apiID'];
            $this->returnJson['groupID'] = $result['groupID'];
        } else {
            $this->returnJson['statusCode'] = '160000';
        }
        exitOutput($this->returnJson);
    }

    /**
     * Edit api
     * 编辑api
     */
    public function editApi()
    {
        $apiID = securelyInput('apiID');
        $module = new ApiModule();
        $userType = $module->getUserType($apiID);
        if ($userType < 0 || $userType > 2) {
            $this->returnJson['statusCode'] = '120007';
            exitOutput($this->returnJson);
        }
        $apiName = securelyInput('apiName');
        $apiURI = securelyInput('apiURI');
        $apiProtocol = securelyInput('apiProtocol');
        $apiRequestType = securelyInput('apiRequestType');
        $apiSuccessMock = quickInput('apiSuccessMock');
        $apiFailureMock = quickInput('apiFailureMock');
        $apiStatus = securelyInput('apiStatus');
        $starred = securelyInput('starred');
        $apiNoteType = securelyInput('apiNoteType');
        $apiNoteRaw = securelyInput('apiNoteRaw');
        $apiNote = securelyInput('apiNote');
        $apiRequestParamType = securelyInput('apiRequestParamType');
        $apiRequestRaw = securelyInput('apiRequestRaw');
        $groupID = securelyInput('groupID');
        $apiHeader = json_decode($_POST['apiHeader'], TRUE);
        $apiRequestParam = json_decode($_POST['apiRequestParam'], TRUE);
        $apiResultParam = json_decode($_POST['apiResultParam'], TRUE);

        $service = new ApiModule;
        $result = $service->editApi($apiID, $apiName, $apiURI, $apiProtocol, $apiSuccessMock, $apiFailureMock, $apiRequestType, $apiStatus, $groupID, $apiHeader, $apiRequestParam, $apiResultParam, $starred, $apiNoteType, $apiNoteRaw, $apiNote, $apiRequestParamType, $apiRequestRaw);
        if ($result) {
            $this->returnJson['statusCode'] = '000000';
            $this->returnJson['apiID'] = $result['apiID'];
            $this->returnJson['groupID'] = $result['groupID'];
        } else {
            $this->returnJson['statusCode'] = '160000';
        }
        exitOutput($this->returnJson);
    }

//	/**
//	 * 删除api,将其移入回收站
//	 */
//	public function removeApi() {
//		$apiID = securelyInput('apiID');
//		//判断apiID格式是否合法
//		if (preg_match('/^[0-9]{1,11}$/', $apiID)) {
//			//apiID格式合法
//			$service = new ApiModule;
//			$result = $service -> removeApi($apiID);
//			//判断删除api是否成功
//			if ($result) {
//				//删除api成功
//				$this -> returnJson['statusCode'] = '000000';
//			} else {
//				//删除api失败
//				$this -> returnJson['statusCode'] = '160008';
//			}
//		} else {
//			//apiID格式不合法
//			$this -> returnJson['statusCode'] = '160001';
//		}
//		exitOutput($this -> returnJson);
//	}

    /**
     * Delete apis in batches and move them into recycling station
     * 批量删除api,将其移入回收站
     */
    public function removeApi()
    {
        //接口ID
        $ids = securelyInput('apiID');
        $projectID = securelyInput('projectID');
        $module = new ProjectModule();
        $userType = $module->getUserType($projectID);
        if ($userType < 0 || $userType > 2) {
            $this->returnJson['statusCode'] = '120007';
            exitOutput($this->returnJson);
        }
        $arr = json_decode($ids);
        $arr = preg_grep('/^[0-9]{1,11}$/', $arr);//去掉数组中不是数字的ID
        //判断接口ID是否为空
        if (empty($arr)) {
            $this->returnJson['statusCode'] = '160001';
        } elseif (!preg_match('/^[0-9]{1,11}$/', $projectID)) {
            $this->returnJson['statusCode'] = '160002';
        } else {
            $api_ids = implode(',', $arr);
            $api_module = new ApiModule;
            $result = $api_module->removeApis($projectID, $api_ids);
            //验证结果是否成功
            if ($result) {
                //删除api成功
                $this->returnJson['statusCode'] = '000000';
            } else {
                //删除api失败
                $this->returnJson['statusCode'] = '160000';
            }
        }
        exitOutput($this->returnJson);
    }

    /**
     * Recover api
     * 恢复api
     */
    public function recoverApi()
    {
        //接口ID
        $ids = securelyInput('apiID');
        $groupID = securelyInput('groupID');
        $module = new GroupModule();
        $userType = $module->getUserType($groupID);
        if ($userType < 0 || $userType > 2) {
            $this->returnJson['statusCode'] = '120007';
            exitOutput($this->returnJson);
        }
        $arr = json_decode($ids);
        $arr = preg_grep('/^[0-9]{1,11}$/', $arr);//去掉数组中不是数字的ID
        //判断接口ID是否为空
        if (empty($arr)) {
            $this->returnJson['statusCode'] = '160001';
        } elseif (!preg_match('/^[0-9]{1,11}$/', $groupID)) {
            $this->returnJson['statusCode'] = '160002';
        } else {
            $api_ids = implode(',', $arr);
            $api_module = new ApiModule;
            $result = $api_module->recoverApis($groupID, $api_ids);
            //验证结果是否成功
            if ($result) {
                //恢复api成功
                $this->returnJson['statusCode'] = '000000';
            } else {
                //恢复api失败
                $this->returnJson['statusCode'] = '160000';
            }
        }
        exitOutput($this->returnJson);
    }

    /**
     * Remove apis in batches from recycling station
     * 批量彻底删除api
     */
    public function deleteApi()
    {
        //接口ID
        $ids = securelyInput('apiID');
        $projectID = securelyInput('projectID');
        $module = new ProjectModule();
        $userType = $module->getUserType($projectID);
        if ($userType < 0 || $userType > 2) {
            $this->returnJson['statusCode'] = '120007';
            exitOutput($this->returnJson);
        }
        $arr = json_decode($ids);
        $arr = preg_grep('/^[0-9]{1,11}$/', $arr);//去掉数组中不是数字的ID
        //判断接口ID是否为空
        if (empty($arr)) {
            $this->returnJson['statusCode'] = '160001';
        } elseif (!preg_match('/^[0-9]{1,11}$/', $projectID)) {
            $this->returnJson['statusCode'] = '160002';
        } else {
            $api_ids = implode(',', $arr);
            $api_module = new ApiModule;
            $result = $api_module->deleteApis($projectID, $api_ids);
            //验证结果是否成功
            if ($result) {
                //删除api成功
                $this->returnJson['statusCode'] = '000000';
            } else {
                //删除api失败
                $this->returnJson['statusCode'] = '160000';
            }
        }
        exitOutput($this->returnJson);
    }

    /**
     * Clean up the recycling station
     * 清空回收站
     */
    public function cleanRecyclingStation()
    {
        $projectID = securelyInput('projectID');
        $module = new ProjectModule();
        $userType = $module->getUserType($projectID);
        if ($userType < 0 || $userType > 2) {
            $this->returnJson['statusCode'] = '120007';
            exitOutput($this->returnJson);
        }
        if (!preg_match('/^[0-9]{1,11}$/', $projectID)) {
            $this->returnJson['statusCode'] = '160002';
        } else {
            $service = new ApiModule;
            $result = $service->cleanRecyclingStation($projectID);
            if ($result) {
                $this->returnJson['statusCode'] = '000000';
            } else {
                $this->returnJson['statusCode'] = '160011';
            }
        }
        exitOutput($this->returnJson);
    }

    /**
     * Get api list from recycling station
     * 获取回收站api列表
     */
    public function getRecyclingStationApiList()
    {
        $projectID = securelyInput('projectID');
        $orderBy = securelyInput('orderBy', 0);
        $asc = securelyInput('asc', 0);
        if (preg_match('/^[0-9]{1,11}$/', $projectID)) {
            $service = new ApiModule;

            //判断排序方式
            switch ($orderBy) {
                //名称排序
                case 0 : {
                    $result = $service->getRecyclingStationApiListOrderByName($projectID, $asc);
                    break;
                }
                //时间排序
                case 1 : {
                    $result = $service->getRecyclingStationApiListOrderByRemoveTime($projectID, $asc);
                    break;

                }
                //星标排序
                case 2 : {
                    $result = $service->getRecyclingStationApiListOrderByStarred($projectID, $asc);
                    break;
                }
                //创建时间排序
                case 3 : {
                    $result = $service->getRecyclingStationApiListOrderByCreateTime($projectID, $asc);
                }
            }

            if ($result) {
                $this->returnJson['statusCode'] = '000000';
                $this->returnJson['apiList'] = $result;
            } else {
                $this->returnJson['statusCode'] = '160007';
            }
        } else {
            $this->returnJson['statusCode'] = '160002';
        }
        exitOutput($this->returnJson);
    }

    /**
     * Get api list by group
     * 获取api列表
     */
    public function getApiList()
    {
        $groupID = securelyInput('groupID');
        $orderBy = securelyInput('orderBy', 0);
        $asc = securelyInput('asc', 0);
        if (preg_match('/^[0-9]{1,11}$/', $groupID)) {
            $service = new ApiModule;

            //判断排序方式
            switch ($orderBy) {
                //名称排序
                case 0 : {
                    $result = $service->getApiListOrderByName($groupID, $asc);
                    break;
                }
                //时间排序
                case 1 : {
                    $result = $service->getApiListOrderByTime($groupID, $asc);
                    break;
                }
                //星标排序
                case 2 : {
                    $asc = 1;
                    $result = $service->getApiListOrderByStarred($groupID, $asc);
                    break;
                }
                //创建时间排序
                case 3 : {
                    $result = $service->getApiListOrderByCreateTime($groupID, $asc);
                    break;
                }
            }

            if ($result) {
                $this->returnJson['statusCode'] = '000000';
                $this->returnJson['apiList'] = $result;
            } else {
                $this->returnJson['statusCode'] = '160000';
            }
        } else {
            $this->returnJson['statusCode'] = '160002';
        }
        exitOutput($this->returnJson);
    }

    /**
     * Get api detail
     * 获取api详情
     */
    public function getApi()
    {
        $apiID = securelyInput('apiID');
        if (preg_match('/^[0-9]{1,11}$/', $apiID)) {
            $service = new ApiModule;
            $result = $service->getApi($apiID);
            if ($result) {
                $this->returnJson['statusCode'] = '000000';
                $this->returnJson['apiInfo'] = $result;
            } else {
                $this->returnJson['statusCode'] = '160000';
            }
        } else {
            $this->returnJson['statusCode'] = '160001';
        }
        exitOutput($this->returnJson);
    }

    /**
     * Get all api list by project
     * 获取所有分组的api
     */
    public function getAllApiList()
    {
        $projectID = securelyInput('projectID');
        $orderBy = securelyInput('orderBy', 0);
        $asc = securelyInput('asc', 0);
        if (preg_match('/^[0-9]{1,11}$/', $projectID)) {
            $service = new ApiModule;

            switch ($orderBy) {
                //名称排序
                case 0 : {
                    $result = $service->getAllApiListOrderByName($projectID, $asc);
                    break;
                }
                //时间排序
                case 1 : {
                    $result = $service->getAllApiListOrderByTime($projectID, $asc);
                    break;
                }
                //星标排序
                case 2 : {
                    $asc = 1;
                    $result = $service->getAllApiListOrderByStarred($projectID, $asc);
                    break;
                }
                //创建时间
                case 3 : {
                    $result = $service->getAllApiListOrderByCreateTime($projectID, $asc);
                }
            }

            if ($result) {
                $this->returnJson['statusCode'] = '000000';
                $this->returnJson['apiList'] = $result;
            } else {
                $this->returnJson['statusCode'] = '160000';
            }
        } else {
            $this->returnJson['statusCode'] = '160003';
        }
        exitOutput($this->returnJson);
    }

    /**
     * search api
     * 搜索api
     */
    public function searchApi()
    {
        $tipsLen = mb_strlen(quickInput('tips'), 'utf8');
        $tips = securelyInput('tips');
        $projectID = securelyInput('projectID');
        if (!preg_match('/^[0-9]{1,11}$/', $projectID)) {
            $this->returnJson['statusCode'] = '160003';
        }
        if ($tipsLen > 255 || $tipsLen == 0) {
            $this->returnJson['statusCode'] = '160004';
        } else {
            $service = new ApiModule;
            $result = $service->searchApi($tips, $projectID);
            if ($result) {
                $this->returnJson['statusCode'] = '000000';
                $this->returnJson['apiList'] = $result;
            } else {
                $this->returnJson['statusCode'] = '160000';
            }
        }
        exitOutput($this->returnJson);
    }

    /**
     * add star to an api
     * 添加星标
     */
    public function addStar()
    {
        $apiID = securelyInput('apiID');
        if (preg_match('/^[0-9]{1,11}$/', $apiID)) {
            $service = new ApiModule;
            $result = $service->addStar($apiID);
            if ($result) {
                $this->returnJson['statusCode'] = '000000';
            } else {
                $this->returnJson['statusCode'] = '160000';
            }
        } else {
            $this->returnJson['statusCode'] = '160001';
        }
        exitOutput($this->returnJson);
    }

    /**
     * remove star from an api
     * 添加星标
     */
    public function removeStar()
    {
        $apiID = securelyInput('apiID');
        if (preg_match('/^[0-9]{1,11}$/', $apiID)) {
            $service = new ApiModule;
            $result = $service->removeStar($apiID);
            if ($result) {
                $this->returnJson['statusCode'] = '000000';
            } else {
                $this->returnJson['statusCode'] = '160000';
            }
        } else {
            $this->returnJson['statusCode'] = '160001';
        }
        exitOutput($this->returnJson);
    }

}

?>