<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * Longphp
 * Author: William Jiang
 */

define('LONGPHP_VERSION', '1.0');

//引入常用方法
require SYS_PATH . DIRECTORY_SEPARATOR . 'Long/Functions.php';

set_error_handler('errorHandler');
set_exception_handler('exceptionHandler');


//加载配置文件
Long\Config\Config::loadConfig('config');


new \Controllers\TestController();

var_dump($_SERVER['REQUEST_URI']);