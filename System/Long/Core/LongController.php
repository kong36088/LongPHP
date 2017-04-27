<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Core;

use Long\Library\Output;
use Philo\Blade\Blade;
use Long\Library\Logger\Log;


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

        Log::info('Init Controller ' . __CLASS__);
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
}