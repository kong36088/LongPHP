# LongPHP
A light PHP MVC framework

## Install

``` bash
composer install
```

## Requirements

`php >= 5.6`
`composer`

# Documentation


This is powered by Blade, some help here:https://laravel.com/docs/5.3/blade
``` php
$this->render('your/blade', array('your' => 'variables'));
```

Please make sure you have the right access root of the project.
``` bash
chmod -R 0755 /Path/to/Longphp
chmod -R 0644 /Path/to/Longphp/cache
chmod -R 0644 /Path/to/Longphp/Application/logs
```

After writing your own Library/Controller(etc), please make sure you have run this command:
``` bash
composer dump-autoload
```

## Description

LongPHP is powered by composer, which can help you extend your application by using namespace.

## Controller

DemoController
``` php
<?php

/**
 * Longphp
 * Author: William Jiang
 */

namespace Application\Controller;

use Long\Core\Config;
use Long\Core\LongException;
use Long\Library\Input;
use Long\Core\LongController;
use Long\Library\Logger\Log;
use Long\Library\Session\LongSession;
use Long\Library\Url;

class DemoController extends LongController
{

    //construct method
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

}
```
You can get access to index method like this:
http://yourhost/index.php/Demo/index

## Model

Config your database in `Application/config/database.php`

|config|options|description|
|:-----------:|:-----------:|:-----------:|
|`db_host`||Database host name|
|`db_user`||Database username|
|`db_password`||Database pass|
|`db_port`| 3306/(others)|Database port|
|`db_database`||Database database|
|`db_charset`|utf8/(others)|Database charset|
|`db_driver`| mysqli |Database driver, only mysql is supported now|

Demo Model:
``` php
<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Application\Model;


use Long\Core\LongModel;

class TestModel extends LongModel
{
	public function getById($id = 1)
	{
		return $this->db->query('SELECT * FROM test_table WHERE id = ?', array($id));
	}

	public function insertTestData()
	{
		return $this->db->query('INSERT INTO test_table(`number`,`doubled`,`strings`,`time`) VALUES(?,?,?,?)', [1, 1.111, 'string test', date('Y-m-d H:i:s')]);
	}

	public function updateTestData($id = 1,$update)
	{
		return $this->db->query('UPDATE test_table SET `strings` = ? WHERE `id` = ?', array($update,$id));
	}

	public function deleteTestData($id = 1)
	{
		return $this->db->query('DELETE FROM test_table WHERE id = ?', array($id));
	}

	public function transTestData(){
		$this->db->transStart();
		$this->updateTestData(1, 'This is Transaction');
		$this->db->commit();

		$this->db->transStart();
		$this->updateTestData(1, 'This is rollback');
		$this->db->rollback();

	}
}
```
## Router
When you don't figure out controller or method in url ,you can set your default controller and method at `Application/config/router.php` and use as default.

|config|description|
|:-----------:|:-----------:|
|`default_controller`|Default controller|
|`default_method`|Default method|

## Input

Getting input data.
``` php
use Long\Library\Input; //import Input

...

//getting input data
echo Input::get('key');
echo Input::post('key');
echo Input::put('key');
echo Input::delete('key');
```

## Session

### Introduction
LongPHP provides session support.
Support for session driver now:`file`,`database`.

### Configuration

THe configuration file is stored at `Application/config/config.php`

|config|options|description|
|:-----------:|:-----------:|:-----------:|
|`session_driver`|file/database|Where to store session|
|`session_path`| Framework/session |Session files location. Leave blank to use default|
|`session_cookie_name`| |The session token store in browser|
|`session_expiration`| 7200/(time you want) |The expiration time of session|


### Retrieving Data

You can use `LongSession::get($key)` to retrieve data.
`LongSession::all()`to retrieve all data you have put.

Make sure that you have used the namespace `Long\Library\Session`
``` php
namespace Application\Controller;

use Long\Library\Session;

class UserController extends LongController
{

    public function show()
    {
        $value = LongSession::get('key');

        //if you want to get all data
        
    }
}
```

### Putting Data

Using `set` method can help you to put data into session.
If you are willing to put a bunch of data, `batchSet` will be useful.
``` php
//put data
LongSession::set('key','value');

//batch assignment
LongSession::batchSet(['test1'=>'data1','test2'=>'data2']);
```

### Deleting Data

The `remove` method will remove a piece of data from the session. 
If you want to get the data before removing it, use `pull`.
If you would like to remove all data from the session, you may use the `flush` method:
``` php
//delete 'key'
LongSession::remove('key');

//get 'key' then delete it
LongSession::pull('key');

//Delete all data
LongSession::flush();
```

### Regenerating The Session ID
Regenerating the session ID is often done in order to prevent malicious users from exploiting a session fixation attack on your application.

LongPHP automatically regenerates the session ID ; however, if you need to manually regenerate the session ID, you may use the  regenerate method.

``` php
LongPHP::regenerate();
```

## Cookie

 Cookie opertations:

``` php
use Long\Library\Cookie

...
//retrive cookie
echo Cookie::get('test_cookie');

//set cookie
Cookie::set('test_cookie','cookie value',100);

//remove cookie
Cookie::remove('test_cookie');
```

## Log

The levels of logger:
``` php
const EMERGENCY = 'emergency';
const ALERT     = 'alert';
const CRITICAL  = 'critical';
const ERROR     = 'error';
const WARNING   = 'warning';
const NOTICE    = 'notice';
const INFO      = 'info';
const DEBUG     = 'debug';
```

You can call logging methods according to the `log level`

|config|options|description|
|:-----------:|:-----------:|:-----------:|
|`log_level`|0/1/2/3/4|Log level |
|`log_path`|  |Where to store log files.|

0 Don't do any log 
1 Log only error 
2 Log error、warning、notice
3 error、warning、notice、info 
4 Log all

``` php
use Long\Library\Logger\Log; //to import logger

....

Logger::debug("some debug message"); // static calling
Logger::info("some info message"); // static calling
Logger::warning("some warning message"); // static calling
Logger::notice("some notice message"); // static calling
Logger::error("some error message"); // static calling
Logger::critical("some critical message"); // static calling
Logger::alert("some alert message"); // static calling
Logger::emergency("some emergency message"); // static calling
```



# Upload

Usage

Assume a file is uploaded with this HTML form:
``` html
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="foo" value=""/>
    <input type="submit" value="Upload File"/>
</form>
```

When the HTML form is submitted, the server-side PHP code can validate and upload the file like this:

``` php
<?php
$storage = new \Upload\Storage\FileSystem('/path/to/directory');
$file = new \Upload\File('foo', $storage);

// Optionally you can rename the file on upload
$new_filename = uniqid();
$file->setName($new_filename);

// Validate file upload
// MimeType List => http://www.iana.org/assignments/media-types/media-types.xhtml
$file->addValidations(array(
    // Ensure file is of type "image/png"
    new \Upload\Validation\Mimetype('image/png'),

    //You can also add multi mimetype validation
    //new \Upload\Validation\Mimetype(array('image/png', 'image/gif'))

    // Ensure file is no larger than 5M (use "B", "K", M", or "G")
    new \Upload\Validation\Size('5M')
));

// Access data about the file that has been uploaded
$data = array(
    'name'       => $file->getNameWithExtension(),
    'extension'  => $file->getExtension(),
    'mime'       => $file->getMimetype(),
    'size'       => $file->getSize(),
    'md5'        => $file->getMd5(),
    'dimensions' => $file->getDimensions()
);

// Try to upload file
try {
    // Success!
    $file->upload();
} catch (\Exception $e) {
    // Fail!
    $errors = $file->getErrors();
}
```

# Servers
If you want to make your uri shorter, the `url rewrite` will help you hide `index.php` in uri.

## nginx

``` bash
server 
{
    listen       80;
    server_name  www.example.com;
    index index.shtml index.html index.htm index.php;
    root  /path/to/root/Longphp;
     location / {
        try_files $uri $uri/ =404;
        if (!-e $request_filename)
        {
            rewrite (.*) /index.php;
        }
    }
    location ~ .*\.(php|php5)?$
    {
        fastcgi_pass  127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi.conf;
    }
    access_log  /var/log/Longphp/access.log  access;
}
```
If your project is not the root directory

For example, your project is located in `/path/to/root/Longphp`

``` bash
server 
{
    listen       80;
    server_name  www.example.com;
    index index.shtml index.html index.htm index.php;
    root  /path/to/root;
     location /Longphp {
        try_files $uri $uri/ =404;
        if (!-e $request_filename)
        {
            rewrite (.*) /Longphp/index.php;
        }
    }
    location ~ .*\.(php|php5)?$
    {
        fastcgi_pass  127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi.conf;
    }
    access_log  /var/log/Longphp/access.log  access;
}
```

## apache

If you are looking forward to rewriting url, please make sure that you have enabled apache `rewrite module`.
We have already written `.htaccess` file under the framework directory for you.

Here is the configuration of apache.
``` apacheconfig
<VirtualHost *:80>
    DocumentRoot "/path/to/Longphp"
    ServerName www.example.com
    AddType application/x-httpd-php .php
    <Directory />
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
        DirectoryIndex index.php
    </Directory>
</VirtualHost>
```



