<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long;

use Long\Log\Log;
use Long\Output\Output;
use Philo\Blade\Blade;

class Long_Controller
{
	/**
	 * 用于保存已加载的外部类
	 * @var array
	 */
	protected $_class = array();

	/**
	 * 保存已加载过的命名空间
	 * @var array
	 */
	protected $_loaded = array();

	public function __construct()
	{
		Log::writeLog('Init Controller', 'INFO');
	}

	/**
	 * 渲染模版文件
	 * @param string $bladeFile blade文件路径
	 * @param array $params 需要assign的数据
	 */
	public function render($bladeFile, $params = array())
	{
		$blade = new Blade(VIEW_PATH, CACHE_PATH);
		$html = $blade->view()->make($bladeFile, $params)->render();
		$this->output($html, 'html');
	}

	/**
	 * @param string|array $data
	 * @param string $type 输出类型
	 */
	public function output($data, $type = 'RAW')
	{
		$type = strtoupper($type);
		//$this->_load('Output');

		if ($type === 'JSON') {
			Output::json($data);
		}
		if ($type === 'HTML') {
			Output::html($data);
		} else {
			Output::raw($data);
		}
	}

	/**
	 * 加载外部类放入类属性中
	 * @param string $class 类的命名空间
	 * @return bool
	 */
	protected function _load($class, $params = array())
	{
		if (isset($this->_loaded[$class])) {
			return true;
		}

		$newClass = new $class(...$params);
		$className = '_' . substr($class, strpos($class, '\\'));
		if (isset($newClass)) {
			$this->_loaded[$class] = $class;
			$this->$className = $newClass;
			return true;
		} else {
			return false;
		}

	}
}