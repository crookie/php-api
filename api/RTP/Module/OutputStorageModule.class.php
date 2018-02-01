<?php

namespace RTP\Module;

Class OutputStorageModule
{
	/**
	 * 注册表变量
	 */
	private static $registry = NULL;
	private static $instance;

	/**
	 * 获取实例
	 */
	public static function getInstance()
	{
		//如果已经含有一个实例则直接返回实例
		if (!is_null(self::$instance))
		{
			return self::$instance;
		}
		else
		{
			//如果没有实例则新建
			return self::getNewInstance();
		}
	}

	/**
	 * 获取一个新的实例
	 */
	public static function getNewInstance()
	{
		self::$instance = null;
		self::$instance = new self;
		return self::$instance;
	}

	/**
	 * 构造函数
	 */
	protected function __construct()
	{
		if (is_null(self::$registry))
			self::$registry = array();
	}

	/**
	 * 获取储存的数据
	 */
	public static function get($offset)
	{
		if (isset(self::$registry[$offset]))
			return self::$registry[$offset];
		return NULL;
	}

	/**
	 * 设置缓存数据值
	 */
	public static function set($value)
	{
		if (is_null(self::$registry))
			self::$registry = array();

		self::$registry[] = $value;
	}

	/**
	 * 获取所有缓存值
	 */
	public static function getAll()
	{
		return self::$registry;
	}

	/**
	 * 检查缓存值是否已经存在
	 */
	public static function isExist($value)
	{
		if (is_null(self::$registry))
		{
			return FALSE;
		}
		if (in_array($value, self::$registry))
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 清除缓存值
	 */
	public static function clean()
	{
		self::$registry = NULL;
	}

}
?>