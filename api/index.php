<?php
header("Access-Control-Allow-Origin: *");

//定义框架路径
defined('PATH_FW') or define('PATH_FW', './RTP');

//引入框架
require PATH_FW . DIRECTORY_SEPARATOR . 'rtp.inc.php';
?>