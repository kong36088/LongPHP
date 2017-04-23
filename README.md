# LongPHP
A light PHP MVC framework

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
# Install

``` bash
composer install
```

# Requirements

`php >= 5.6`
`composer`

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



