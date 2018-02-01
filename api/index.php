<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, authorization, Token");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

//定义框架路径
defined('PATH_FW') or define('PATH_FW', './RTP');

//引入框架
require PATH_FW . DIRECTORY_SEPARATOR . 'rtp.inc.php';
?>