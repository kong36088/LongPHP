<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Core;

use Long\Library\Output;
use Philo\Blade\Blade;

class LongController
{

    private static $_instance;
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

    /**
     * to store the loaded models
     * @var array
     */
    protected $_models = array();

    /**
     * Library path according to configuration
     * @var string
     */
    protected $_libraryPath;

    /**
     * Model path according to configuration
     * @var string
     */
    protected $_modelPath;

    public function __construct()
    {
        self::$_instance = &$this;
        $this->_libraryPath = Config::get('application_path') . '\\Library';
        $this->_modelPath = Config::get('application_path') . '\\Model';

        Log::writeLog('Init Controller ' . __CLASS__, 'INFO');
    }

    public static function &getInstance()
    {
        return self::$_instance;
    }

    /**
     * render templates using blade
     * @param string $bladeFile blade文件路径
     * @param array $params the data being assigned
     */
    protected function _render($bladeFile, $params = array())
    {
        $blade = new Blade(VIEW_PATH, CACHE_PATH);
        $html = $blade->view()->make($bladeFile, $params)->render();
        $this->_output($html, 'html');
    }

    /**
     * @param string|array $data
     * @param string $type output type
     */
    protected function _output($data, $type = 'RAW')
    {
        $type = strtoupper($type);

        switch ($type) {
            case 'JSON':
                Output::json($data);
                break;
            case 'HTML':
                Output::html($data);
                break;
            default:
                Output::raw($data);
        }
    }

    /**
     * To load model
     * @param string $className model class name
     * @param array $args
     * @param string $namespace the namespace of model
     * @return object
     */
    protected function &_model($className, $args = array(), $namespace = ''){
        if(empty($namespace)){
            $namespace = $this->_modelPath;
        }

        $className = ucfirst($className);
        
        $namespace = trim($namespace,'\\\/');
        $fullClassName = $namespace . '\\' . $className;

        if(isset($this->_models[$fullClassName])){
            return $this->_models[$fullClassName];
        }

        $ReflectionClass = new \ReflectionClass($fullClassName);
        $this->_models[$fullClassName] = $ReflectionClass->newInstance($args);
        return $this->_models[$fullClassName];
    }

    /**
     * 加载外部类放入类属性中
     * TODO abandoned
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