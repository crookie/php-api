<?php

namespace RTP\Module;

class AutomaticallyModule
{
	private static $path;
	private static $groupName;
	private static $controllerName;
	private static $moduleName;
	private static $operationName;
	private static $daoName;
	private static $modelName;

	public static function start()
	{
		//注册自动载入方法
		spl_autoload_register('self::autoloadUserController');
		spl_autoload_register('self::autoloadUserModule');
		spl_autoload_register('self::autoloadUserDao');
		spl_autoload_register('self::autoloadRTPModule');

		/*
		 * 检查项目配置文件是否已经存在，如果存在则该项目可能已经部署完成，否则重新开始配置项目
		 */
		if (file_exists(PATH_FW . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'eo_config.php'))
		{
			quickRequire(PATH_FW . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'eo_config.php');
		}

		/*
		 * 检查版本配置文件是否存在
		 */
		if (file_exists(PATH_FW . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'version.php'))
		{
			quickRequire(PATH_FW . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'version.php');
		}

		self::$groupName = $_GET['g'];
		self::$controllerName = $_GET['c'];
		self::$moduleName = $_GET['c'];
		self::$operationName = $_GET['o'];

		//检查pathinfo完整性
		if (!isset(self::$groupName))
			throw new ExceptionModule(11002, 'error in lack of groupName');
		else
		if (!isset(self::$controllerName))
			throw new ExceptionModule(11003, 'error in lack of controllerName');
		else
		if (!isset(self::$operationName))
			throw new ExceptionModule(11004, 'error in lack of operationName');
		//实例化控制器对象
		$class = new \ReflectionClass(self::$controllerName . DIR_CONTROLLER);

		//系统魔术方法
		$php_magic_methods = array(
			'__construct',
			'__destruct',
			'__call',
			'__callstatic',
			'__get',
			'__set',
			'__isset',
			'__unset',
			'__sleep',
			'__wakeup',
			'__tostring',
			'__invoke',
			'__set_state',
			'__clone',
			'__debugInfo'
		);
		//操作无法访问
		// throw new ExceptionModule(11005, self::$operationName . $_GET['p']);
		//如果拥有相应的操作方法,并且这些方法并不是php的魔术方法
		if (!in_array(strtolower(self::$operationName), $php_magic_methods) && $class -> hasMethod(self::$operationName))
		{
			//获取方法
			$method = $class -> getMethod(self::$operationName);
			//判断是否是公用方法
			if ($method -> isPublic())
			{
				//判断是否是静态方法，静态与非静态的执行操作有所不同
				if ($method -> isStatic())
				{
					$method -> invoke(NULL);
				}
				else
				{
					$method -> invoke($class -> newInstance());
				}
			}
			else
			{
				//操作无法访问
				throw new ExceptionModule(11005, 'operation isn\'t a public function');
			}
			

		}
		else
		{
			//操作无法访问
			throw new ExceptionModule(11006, 'undefined operation or illegal operation name');
		}
	}

	/**
	 * 自动载入用户自定义控制器
	 */
	public static function autoloadUserController($className)
	{
		$path = realpath(PATH_APP . DIRECTORY_SEPARATOR . self::$groupName . DIRECTORY_SEPARATOR . DIR_CONTROLLER . DIRECTORY_SEPARATOR . self::$controllerName . DIR_CONTROLLER . '.class.php');
		quickRequire($path);
		//当控制器完成路由之后，取消自动载入控制器的路由，加快模块的加载速度
		spl_autoload_unregister('self::autoloadUserController');
	}

	/**
	 * 自动载入用户自定义模块
	 */
	public static function autoloadUserModule($className)
	{
		$path = realpath(PATH_APP . DIRECTORY_SEPARATOR . self::$groupName . DIRECTORY_SEPARATOR . DIR_MODULE . DIRECTORY_SEPARATOR . $className . '.class.php');
		quickRequire($path);
	}

	/**
	 * 自动载入用户自定义Dao
	 */
	public static function autoloadUserDao($className)
	{
		$path = realpath(PATH_APP . DIRECTORY_SEPARATOR . self::$groupName . DIRECTORY_SEPARATOR . DIR_DAO . DIRECTORY_SEPARATOR . $className . '.class.php');
		quickRequire($path);
	}

	/**
	 * 自动载入框架模块
	 */
	public static function autoloadRTPModule($className)
	{
		$path = realpath(PATH_FW . PATH_MODULE) . DIRECTORY_SEPARATOR . str_replace('RTP\Module\\', '', $className) . '.class.php';
		quickRequire($path);
	}

}
?>