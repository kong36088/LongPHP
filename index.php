<?php
/**
 * LongPHP
 * Author: William Jiang
 */

/**
 * Application environment
 * options:
 *
 * develop
 * production
 * test
 */
define('ENV', 'develop');


/**
 * Here is the error reporting handle
 */
switch (ENV) {
	case 'develop':
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		break;

	case 'test':
	case 'production':
		ini_set('display_errors', 0);
		error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', true, 503);
		echo 'please check your application environment.';
		exit(1);
}

/**
 * 在这里设置你的应用的文件夹
 */
$applicationPath = 'Application'; //尽量不要修改，需要修改则需要同时修改composer.json中autoload中Application/Library路径

/**
 * 在这里设置系统文件夹
 */
$longphpPath = 'System'; //尽量不要修改，需要修改则需要同时修改composer.json中autoload中System路径

/**
 * 视图路径
 */
$viewPath = $applicationPath.DIRECTORY_SEPARATOR.'View';

$cachePath = 'cache';

defined('SELF') or define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

defined('SYS_PATH') or define('SYS_PATH', rtrim($longphpPath, '/\\'));

defined('APP_PATH') or define('APP_PATH', rtrim($applicationPath, '/\\'));

defined('VIEW_PATH') or define('VIEW_PATH', APP_PATH . DIRECTORY_SEPARATOR . 'View');

defined('BASE_PATH') or define('BASE_PATH', dirname(__FILE__));

defined('CACHE_PATH') or define('CACHE_PATH',$cachePath);

//利用composer进行自动加载管理
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

require_once SYS_PATH . DIRECTORY_SEPARATOR . 'Longphp.php';

