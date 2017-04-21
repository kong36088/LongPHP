<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * Longphp
 * Author: William Jiang
 */

/**
 * 系统部署域名
 */
$config['base_url'] = 'vm/';

/**
 * 日志等级
 *
 * 0 全部都不记录
 * 1 只记录error级别
 * 2 记录error和debug级别
 * 3 记录error、debug、info级别
 * 4 记录所有级别
 */
$config['log_level'] = 4;
/**
 * 日志路径目录
 */
$config['log_path'] = APP_PATH . DIRECTORY_SEPARATOR . 'logs/';

$config['blade_ext'] = '.blade.php';
