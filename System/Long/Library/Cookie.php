<?php
/**
 * LongPHP
 * Author: William Jiang <william@jwlchian.cn>
 * Date: 2017/5/4
 * Time: 下午4:07
 */

namespace Long\Library;


use Long\Core\Config;

class Cookie
{
    public static function get($name)
    {
        if (is_array($name)) {
            $result = array();
            foreach ($name as $n) {
                $result[] = self::get($name);
            }
            return $result;
        }
        $prefix = '';
        if (Config::get('cookie_prefix') !== '') {
            $prefix = Config::get('cookie_prefix');
        }
        $name = $prefix . $name;
        return isset($_COOKIE[$name])?$_COOKIE[$name]:null;

    }

    public static function set($name, $value = '', $expire = 0, $domain = '', $path = '/', $prefix = '', $secure = NULL, $httponly = NULL)
    {
        if (is_array($name)) {
            // always leave 'name' in last place, as the loop will break otherwise, due to $$item
            foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'httponly', 'name') as $item) {
                if (isset($name[$item])) {
                    $$item = $name[$item];
                }
            }
        }

        if ($prefix === '' && Config::get('cookie_prefix') !== '') {
            $prefix = Config::get('cookie_prefix');
        }

        if ($domain == '' && Config::get('cookie_domain') != '') {
            $domain = Config::get('cookie_domain');
        }

        if ($path === '/' && Config::get('cookie_path') !== '/') {
            $path = Config::get('cookie_path');
        }

        $secure = ($secure === NULL && Config::get('cookie_secure') !== NULL)
            ? (bool)Config::get('cookie_secure')
            : (bool)$secure;

        $httponly = ($httponly === NULL && Config::get('cookie_httponly') !== NULL)
            ? (bool)Config::get('cookie_httponly')
            : (bool)$httponly;

        if (!is_numeric($expire) OR $expire < 0) {
            $expire = 1;
        } else {
            $expire = ($expire > 0) ? time() + $expire : 0;
        }

        setcookie($prefix . $name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public static function remove($name, $domain = '', $path = '/', $prefix = '')
    {
        self::set($name, '', '', $domain, $path, $prefix);
    }
}