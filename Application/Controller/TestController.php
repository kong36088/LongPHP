<?php

/**
 * Longphp
 * Author: William Jiang
 */

namespace Application\Controller;

use Long\Core\Config;
use Long\Core\LongException;
use Long\Library\Cookie;
use Long\Library\Input;
use Long\Core\LongController;
use Long\Library\Logger\Log;
use Long\Library\Output;
use Long\Library\Session\LongSession;
use Long\Library\Url;

class TestController extends LongController
{

	public function __construct()
	{
        parent::__construct();

        /*
        MyLibrary::libraryOutput();
        //print_r($this->_loaded);exit;

        $TestController = new \ReflectionClass('Application\Controller\TestController');
        $o = self::getInstance();
        $methods = $TestController->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $k => $method){
            if($method->isPublic()&&!$method->isConstructor()) {
                $method->invokeArgs($o,[]);
            }
        }
        */

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
		//$this->_output('raw data test<br>' . PHP_EOL, 'raw');
	}

	public function testUrl()
	{
		$this->_output(Url::siteUrl('https') . PHP_EOL, 'raw');
	}

	protected function testError()
	{
		//测试捕获异常
		new EmptyClass();
	}

    protected function testException()
	{
		//测试Exception
		throw new LongException('Test  Exception');
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
	    Log::warning("test warning");
    }

    public function testSession(){
	    LongSession::set('test_data',123);
	    $data = LongSession::get('test_data');
	    var_dump($data);
	    //LongSession::destroy();
        LongSession::batchSet(['test1'=>'data1','test2'=>'data2']);
        var_dump(LongSession::all());
        echo '<br/>';
        var_dump(LongSession::pull('test1'));
        LongSession::remove('test2');
    }

    public function testCookie(){
        //Cookie::set('test_cookie','cookie value',100);
        Cookie::remove('test_cookie');
        echo Cookie::get('test_cookie');
    }

}