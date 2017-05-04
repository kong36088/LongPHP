<?php
/**
 * LongPHP
 * Author: William Jiang <william@jwlchian.cn>
 * Date: 2017/5/3
 * Time: 下午5:28
 */

namespace Long\Library\Session;


interface LongSessionInterface
{
    public static function initialize();

    public static function get($key);

    public static function set($key, $value);

    public static function all();

    public static function remove($key);

    public static function destroy();

}