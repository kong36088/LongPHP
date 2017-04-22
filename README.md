# LongPHP
A PHP MVC framework

# Documentation

This is powered by Blade, some help here:https://laravel.com/docs/5.3/blade
``` php
$this->render('your/blade', array('your' => 'variables'));
```

# Install

``` bash
composer install
```

# Requirements

`php >= 5.6`

# Ningx/Apache

## nginx

``` bash
server 
{
    listen       80;
    server_name  www.example.com;
    index index.shtml index.html index.htm index.php;
    root  /path/to/PHPWebIM/client;
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
    access_log  /Library/WebServer/nginx/logs/im.swoole.com  access;
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
    access_log  /Library/WebServer/nginx/logs/im.swoole.com  access;
}
```


## apache
``` apacheconfig
<VirtualHost *:80>
    DocumentRoot "/path/to/Longphp"
    ServerName im.swoole.com
    AddType application/x-httpd-php .php
    <Directory />
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
        DirectoryIndex index.php
    </Directory>
</VirtualHost>
```



