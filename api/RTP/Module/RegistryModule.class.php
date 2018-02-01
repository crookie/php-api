<?php

namespace RTP\Module;

Class RegistryModule
{
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

	protected function __construct()
	{
		if (is_null(self::$registry))
			self::$registry = array();
	}

	/**
	 * 获取全局变量
	 */
	public static function get($name)
	{
		if (isset(self::$registry[$name]))
			return self::$registry[$name];
		return NULL;
	}

	/**
	 * 设置全局变量
	 */
	public static function set($name, $value)
	{
		if (is_null(self::$registry))
			self::$registry = array();
		self::$registry[$name] = $value;
	}

	/**
	 * 删除全局变量
	 */
	public static function del($name)
	{
		unset(self::$registry[$name]);
	}

	/**
	 * 将数组输入全局变量
	 */
	public static function setArray($array, $overWrite = FALSE)
	{
		while ($kv = each($array))
		{
			//如果已经存在重复的键,则覆盖之前的值
			if ($overWrite && isset(self::$registry[$kv[0]]))
				self::$registry[$kv[0]] = $kv[1];
			//否则跳过相同的键
			else
			if (isset(self::$registry[$kv[0]]))
				continue;
			else
				self::$registry[$kv[0]] = $kv[1];
		}
	}

	/**
	 * 获取全局变量数组
	 */
	public static function getAll()
	{
		return self::$registry;
	}

	/**
	 * 清除所有全局变量
	 */
	public static function clean()
	{
		unset(self::$registry);
		self::$registry = NULL;
	}

}
?>