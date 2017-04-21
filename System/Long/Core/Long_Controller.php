<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Core;

use Long\Library\Output;
use Philo\Blade\Blade;

class Long_Controller
{
	/**
	 * 用于保存已加载的外部类
	 * @var array
	 */
	protected $_class = array();

	/**
	 * loaded class
	 * @var array
	 */
	protected $_loaded = array();

	public function __construct()
	{
		Log::writeLog('Init Controller '.__CLASS__, 'INFO');
	}

	/**
	 * 渲染模版文件
	 * @param string $bladeFile blade文件路径
	 * @param array $params the data being assigned
	 */
	public function render($bladeFile, $params = array())
	{
		$blade = new Blade(VIEW_PATH, CACHE_PATH);
		$html = $blade->view()->make($bladeFile, $params)->render();
		$this->output($html, 'html');
	}

	/**
	 * @param string|array $data
	 * @param string $type output type
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
     * @param string $class the class name with namespace
     * @param array $params
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