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

### Retrieving Data

You can use `LongSession::get($key)` to retrieve data.

Make sure that you have used the namespace `Long\Library\Session`
``` php
namespace Application\Controller;

use Long\Library\Session;

class UserController extends LongController
{

    public function show()
    {
        $value = LongSession::get('key');

        //
    }
}
```

### Deleting Data

The forget method will remove a piece of data from the session. If you would like to remove all data from the session, you may use the `flush` method:
``` php
//delete 'key'
LongSession::remove('key');

//Delete all data
LongSession::flush();
```

### Regenerating The Session ID
Regenerating the session ID is often done in order to prevent malicious users from exploiting a session fixation attack on your application.

LongPHP automatically regenerates the session ID ; however, if you need to manually regenerate the session ID, you may use the  regenerate method.

``` php
LongPHP::regenerate();
```

## Log

``` php
use Long\Library\Logger\Log; //to import logger

....

Logger::warning("some warning message"); // static calling
```
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

# Ningx/Apache

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
If your project is not in the root directory

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



