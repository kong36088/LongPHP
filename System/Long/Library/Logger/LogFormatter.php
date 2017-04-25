<?php
/**
 * LongPHP
 * Author: William Jiang <william@jwlchian.cn>
 * Date: 2017/4/24
 * Time: 下午4:19
 */

namespace Long\Library\Logger;


class LogFormatter
{
    protected static $_dateFormat = 'Y-m-d H:i:s';

    /**
     * TODO 自定义日志格式
     * 返回格式化的记录内容
     * @param $message
     * @param $level
     * @param $date
     * @return string
     */
    public static function formatLogMessage($message, $level, $date)
    {
        $line = strtoupper($level) . ':' . $date . '  ' . $message . PHP_EOL;
        return $line;
    }

    /**
     * @param string $dateFormat
     */
    public static function setDateFormat($dateFormat)
    {
        self::$_dateFormat = $dateFormat;
    }
}