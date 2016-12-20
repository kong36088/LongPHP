<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * Longphp
 * Author: William Jiang
 */

define('LONGPHP_VERSION', '1.0');

//引入常用方法
require SYS_PATH . DIRECTORY_SEPARATOR . 'Long/Functions.php';

//设置错误处理
set_error_handler('errorHandler');
set_exception_handler('exceptionHandler');


//加载配置文件
Long\Config\Config::initialize();


//初始化输入类
\Long\Input\Input::initialize();

//测试类
new \Controllers\TestController();

//路由器初始化
\Long\Long_Router::initialize();