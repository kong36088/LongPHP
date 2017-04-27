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

    protected static $_logFormat = '%s : %s  %s' . PHP_EOL;
    /**
     * 返回格式化的记录内容
     * @param $message
     * @param $level
     * @return string
     */
    public static function formatLogMessage($message, $level)
    {
        $level = strtoupper($level);
        $date = date(self::$_dateFormat);
        $logMsg = sprintf(self::$_logFormat,$level,$date,$message);
        return $logMsg;
    }


    /**
     * @return string
     */
    public static function getDateFormat()
    {
        return self::$_dateFormat;
    }

    /**
     * @param string $dateFormat
     */
    public static function setDateFormat($dateFormat)
    {
        self::$_dateFormat = $dateFormat;
    }

    /**
     * @return string
     */
    public static function getLogFormat()
    {
        return self::$_logFormat;
    }

    /**
     * @param string $logFormat
     */
    public static function setLogFormat($logFormat)
    {
        self::$_logFormat = $logFormat;
    }


}