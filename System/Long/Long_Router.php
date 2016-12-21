<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long;


use Long\Config\Config;
use Long\Log\Log;

class Long_Router
{
	public static function initialize()
	{
		if (is_cli()) {
			self::_commandLine();
		}
		Log::writeLog('Router init, request URI ' . $_SERVER['REQUEST_URI'], 'INFO');


		$appRequest = self::_router();
		//分发路由
		self::handler($appRequest);
	}

	protected static function _commandLine()
	{
		$_SERVER['REQUEST_URI'] = "";
		foreach ($_SERVER['argv'] as $k => $v) {
			if ($k == 0) continue;
			$_SERVER['REQUEST_URI'] .= "/" . $v;
		}
	}

	/**
	 * 处理路由
	 * @return array
	 */
	protected static function _router()
	{
		$uri = $_SERVER['REQUEST_URI'];
		$filePath = SELF;
		$documentPath = $_SERVER['DOCUMENT_ROOT'];

		$appPath = str_replace($documentPath, '', $filePath);
		$urlPath = $uri;

		$appPathArr = explode(DIRECTORY_SEPARATOR, $appPath);

		//获取出真实的请求控制器方法等
		foreach ($appPathArr as $k => $v) {
			if ($v) {
				$urlPath = preg_replace('/^\/' . $v . '\/?/', '/', $urlPath, 1);
			}
		}
		$urlPath = preg_replace('/^\//', '', $urlPath, 1); //ltrim($urlPath,'/')

		$appPathArr = explode('/', $urlPath);

		//去除参数
		if (!empty($appPathArr[1])) {
			$appPathArr[1] = preg_replace('/(\?.*)$/', '', $appPathArr[1]);
		}
		$appRequest = array(
			'controller' => empty($appPathArr[0]) ? Config::get('default_controller') : $appPathArr[0],
			'method' => empty($appPathArr[1]) ? Config::get('default_method') : $appPathArr[1],
		);

		return $appRequest;
	}


	public static function handler(Array $appRequest)
	{
		if (empty($appRequest['controller']) || empty($appRequest['method'])) {
			Long_Exception::show404();
			exit(1);
		}
		$controllerName = ucfirst($appRequest['controller']) . 'Controller';
		$controllerFile = ucfirst($appRequest['controller']) . 'Controller.php';
		$filePath = APP_PATH . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $controllerFile;

		$callMethod = $appRequest['method'];

		//判断文件是否存在
		if (!file_exists($filePath)) {
			Long_Exception::show404();
			exit(1);
		}
		$controller = 'Controllers\\' . $controllerName;
		$C = new $controller();

		if (!method_exists($C, $callMethod) || !is_callable(array($C, $callMethod))) {
			Long_Exception::show404();
			exit(1);
		}
		$C->$callMethod();
	}

}