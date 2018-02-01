<?php

class GuestController
{
	//返回json类型
	private $returnJson = array('type' => 'guest');

	/**
	 * 登录
	 */
	public function login()
	{
		$loginName = securelyInput('name');
		$loginPassword = securelyInput('password');
		$server = new GuestModule;
		if (preg_match('/^[0-9a-zA-Z]{6,32}$/', $loginPassword))
		{
			if (preg_match('/^[a-zA-Z][0-9a-zA-Z_]{3,59}$/', $loginName))
			{
				$result = $server -> login($loginName, $loginPassword);

				if ($result)
				{
					$this -> returnJson['statusCode'] = '000000';
					$this -> returnJson['userInfo'] = $result;
				}
				else
					$this -> returnJson['statusCode'] = '120004';
			}
			else
			{
				$this -> returnJson['statusCode'] = '120001';
				exitOutput(json_encode($this -> returnJson));
			}
		}
		else
		{
			//密码非法
			$this -> returnJson['statusCode'] = '120002';
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 用户名注册
	 */
	public function register()
	{
		if (!ALLOW_REGISTER)
		{
			//不允许新用户注册
			$this -> returnJson['statusCode'] = '130005';
		}
		else
		{
			$userName = securelyInput('userName');
			$loginPassword = securelyInput('userPassword');
			$nickNameLen = mb_strlen(quickInput('userNickName'), 'utf8');
			$userNickName = securelyInput('userNickName');

			//验证用户名,4~16位非纯数字，英文数字下划线组合，只能以英文开头
			if (!preg_match('/^[a-zA-Z][0-9a-zA-Z_]{3,59}$/', $userName))
			{
				//用户名非法
				$this -> returnJson['statusCode'] = '130001';
			}
			elseif (!preg_match('/^[0-9a-zA-Z]{32}$/', $loginPassword))
			{
				//密码非法
				$this -> returnJson['statusCode'] = '130002';
			}
			elseif (!($nickNameLen == 0 || $nickNameLen <= 16))
			{
				//用户名非法
				$this -> returnJson['statusCode'] = '130014';
			}
			else
			{
				$server = new GuestModule;
				$result = $server -> register($userName, $loginPassword, $userNickName);

				if ($result)
					//注册成功
					$this -> returnJson['statusCode'] = '000000';
				else
					//注册失败
					$this -> returnJson['statusCode'] = '130005';
			}
		}

		exitOutput($this -> returnJson);
	}

	/**
	 * 用户查重
	 */
	public function checkUserNameExist()
	{
		$userName = securelyInput('userName');
		$server = new GuestModule;
		if (preg_match('/^[a-zA-Z][0-9a-zA-Z_]{3,59}$/', $userName))
		{
			$result = $server -> checkUserNameExist($userName);
			if ($result)
				//用户名可注册
				$this -> returnJson['statusCode'] = '000000';
			else
				//用户名重复
				$this -> returnJson['statusCode'] = '130005';
		}
		else
			//userName格式非法
			$this -> returnJson['statusCode'] = '130001';

		exitOutput($this -> returnJson);
	}

	/**
	 * 检查登录状态
	 */
	public function checkLogin()
	{
		$server = new GuestModule;
		$result = $server -> checkLogin();

		if ($result)
		{
			//已登录
			$this -> returnJson['statusCode'] = '000000';
		}
		else
		{
			//未登录
			$this -> returnJson['statusCode'] = '120005';
		}
		exitOutput($this -> returnJson);
	}

}
?>