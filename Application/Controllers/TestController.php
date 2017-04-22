<?php

/**
 * Longphp
 * Author: William Jiang
 */

namespace Controllers;

use Long\Library\Input;
use Long\Core\Log;
use Long\Core\LongController;
use Long\Library\Logger;
use Long\Library\Url;
use Long\Core\Config;

class TestController extends LongController
{

	public function __construct()
	{
        parent::__construct();
        $TestController = new \ReflectionClass('Controllers\TestController');
        $o = $TestController->newInstanceWithoutConstructor();
        $methods = $TestController->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $k => $method){
            if($method->isPublic()&&!$method->isConstructor()) {
                $method->invokeArgs($o,[]);
            }
        }
		/*
		$this->testConfig();
		$this->testRender();
		$this->testUrl();
		//$this->testError();
		//$this->testException();
		$this->testModel();
        */
	}

	public function index()
	{
        echo Input::get('a');
	}

	public function testRender()
	{
		//$this->render('test', ['test' => 'test here']);
	}

	protected function testConfig()
	{
		//print_r(Config::get());
	}

	public function testOutput()
	{
		$this->output(['testdata1' => 'data1', 'testdata2' => 'data2'], 'json');
		$this->output('raw data test' . PHP_EOL, 'raw');
	}

	public function testUrl()
	{
		$this->output(Url::siteUrl('https') . PHP_EOL, 'raw');
	}

	public function testLog()
	{
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
		$model = M('test');
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