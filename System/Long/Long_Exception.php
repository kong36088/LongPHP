<?php
/**
 * Longphp
 * Author: William Jiang
 */
namespace Long;

use Long\Log\Log;

class Long_Exception
{

	public static $levels = array(
		E_ERROR => 'Error',
		E_WARNING => 'Warning',
		E_PARSE => 'Parsing Error',
		E_NOTICE => 'Notice',
		E_CORE_ERROR => 'Core Error',
		E_CORE_WARNING => 'Core Warning',
		E_COMPILE_ERROR => 'Compile Error',
		E_COMPILE_WARNING => 'Compile Warning',
		E_USER_ERROR => 'User Error',
		E_USER_WARNING => 'User Warning',
		E_USER_NOTICE => 'User Notice',
		E_STRICT => 'Runtime Notice'
	);

	public static function logError($severity, $errMsg, $errFile, $errLine)
	{
		$severity = isset(self::$levels[$severity]) ? self::$levels[$severity] : $severity;
		Log::writeLog('Severity: ' . $severity . ' --> ' . $errMsg . ' ' . $errFile . ' ' . $errLine, 'ERROR');
	}

	public static function show404()
	{
		setHeader(404);
		ob_start();
		echo 'page not found.';
		ob_end_flush();
		exit(1);
	}

	public static function showError($message, $status_code = 200, $template = 'error_general', $heading = '出错')
	{
		$templatesPath = VIEW_PATH . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR;

		if (is_cli()) {
			$message = "\t" . (is_array($message) ? implode("\n\t", $message) : $message);
			$template = 'cli' . DIRECTORY_SEPARATOR . $template;
		} else {
			setHeader($status_code);
			$message = '<p>' . (is_array($message) ? implode('</p><p>', $message) : $message) . '</p>';
			$template = 'html' . DIRECTORY_SEPARATOR . $template;
		}

		ob_start();
		include($templatesPath . $template . '.php');
		$buffer = ob_get_contents();
		ob_end_clean();

		echo $buffer;
	}

	public static function showException($exception)
	{
		$templates_path = VIEW_PATH . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR;

		$message = $exception->getMessage();
		if (empty($message)) {
			$message = '(null)';
		}

		if (is_cli()) {
			$templates_path .= 'cli' . DIRECTORY_SEPARATOR;
		} else {
			setHeader(500);
			$templates_path .= 'html' . DIRECTORY_SEPARATOR;
		}

		ob_start();
		include($templates_path . 'error_exception.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}
}