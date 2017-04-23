<?php

/**
 * Longphp
 * Author: William Jiang
 */

namespace Controllers;

use Application\Library\MyLibrary;
use Long\Core\Config;
use Long\Library\Input;
use Long\Core\Log;
use Long\Core\LongController;
use Long\Library\Logger;
use Long\Library\Url;

class TestController extends LongController
{

	public function __construct()
	{
        parent::__construct();

        MyLibrary::libraryOutput();
        //print_r($this->_loaded);exit;

        $TestController = new \ReflectionClass('Controllers\TestController');
        $o = self::getInstance();
        $methods = $TestController->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $k => $method){
            if($method->isPublic()&&!$method->isConstructor()) {
                $method->invokeArgs($o,[]);
            }
        }

	}

	public function initialize(){
	    $this->_output("initMethod<br>");
    }

	public function index()
	{
        echo Input::get('a');
        echo "index method called<br>";
	}

	public function testRender()
	{
		$this->_render('test', ['test' => 'test here']);
	}

	protected function testConfig()
	{
		print_r(Config::get());
	}

	public function testOutput()
	{
		$this->_output(['testdata1' => 'data1', 'testdata2' => 'data2'], 'json');
		$this->_output('raw data test<br>' . PHP_EOL, 'raw');
	}

	public function testUrl()
	{
		$this->_output(Url::siteUrl('https') . PHP_EOL, 'raw');
	}

	public function testLog()
	{
	    echo "Test write log<br>";
		Log::writeLog('Test Error', 'ERROR');
		Log::writeLog('Test DEBUG', 'DEBUG');
		Log::writeLog('Test INFO', 'INFO');
	}

	protected function testError()
	{
		//测试捕获异常
		new EmptyClass();
	}

    protected function testException()
	{
		//测试Exception
		throw new \Exception('Test  Exception');
	}

	public function testModel()
	{
		$model = $this->_model('testModel');
		print_r($model->getById(4));
		var_dump($model->insertTestData());
		var_dump($model->deleteTestData(2));
		var_dump($model->updateTestData(1,'测试'));
		var_dump($model->transTestData());
	}

	public function testLogger(){
	    $Logger = new Logger();
	    $Logger->info("abc");
    }
}