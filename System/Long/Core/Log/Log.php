<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Core;


class Log
{
	protected static $_filePermission = 0644;

	protected static $_dateFormat = 'Y-m-d H:i:s';
	/**
	 * 日志记录等级
	 *
	 * @var array
	 */
	protected static $_levels = array('ERROR' => 1, 'DEBUG' => 2, 'INFO' => 3, 'ALL' => 4);

	/**
	 * 写入日志
	 * @param string $message
	 * @param string $level ['ERROR','DEBUG','INFO','WARNING']
	 * @return bool
	 */
	public static function writeLog($message, $level)
	{
		$logLevel = (int) Config::get('log_level');
		$level = strtoupper($level);

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

		$date = date(self::$_dateFormat);
		$message = self::formatLogMessage($message, $level, $date);
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

	/**
	 * 返回格式化的记录内容
	 * @param $message
	 * @param $level
	 * @param $date
	 * @return string
	 */
	protected static function formatLogMessage($message, $level, $date)
	{
		$line = strtoupper($level) . ':' . $date . '  ' . $message . PHP_EOL;
		return $line;
	}
}