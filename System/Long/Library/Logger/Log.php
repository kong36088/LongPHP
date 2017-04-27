<?php
/**
 * LongPHP
 * Author: William Jiang <william@jwlchian.cn>
 * Date: 2017/4/21
 * Time: 下午5:47
 */

namespace Long\Library\Logger;


use Long\Core\Config;
use Psr\Log\LogLevel;

class Log extends AbstractLogger
{
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';

    static protected $_levels = array(
        LogLevel::EMERGENCY => 1,
        LogLevel::ALERT => 1,
        LogLevel::CRITICAL => 1,
        LogLevel::ERROR => 1,
        LogLevel::WARNING => 2,
        LogLevel::NOTICE => 2,
        LogLevel::INFO => 3,
        LogLevel::DEBUG =>4
    );

    protected static $_dateFormat = 'Y-m-d H:i:s';

    protected static $_filePermission = 0766;
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return int|bool
     */
    public static function log($level, $message, array $context = array())
    {
        $logLevel = (int) Config::get('log_level');

        //to judge log level
        if (!isset(self::$_levels[$level]) || self::$_levels[$level] > $logLevel) {
            return false;
        }
        $filePath = Config::get('log_path') . 'log-' . date('Y-m-d') . '.log';

        if (!file_exists($filePath)) {
            $newFile = true;
        }

        if (!$fp = @fopen($filePath, 'ab')) {
            return false;
        }

        flock($fp, LOCK_EX);

        $message = LogFormatter::formatLogMessage($message, $level);
        for ($written = 0; $written < strlen($message); $written += $fwrite) {
            $fwrite = fwrite($fp, substr($message, $written));
            if ($fwrite === false) {
                break;
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        if (isset($newFile) && $newFile === true) {
            chmod($filePath, self::$_filePermission);
        }
        return is_int($fwrite);
    }
}