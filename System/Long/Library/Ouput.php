<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Library;

/**
 * TODO 完善模块，错误处理等
 * Class Output
 * @package Long\Library
 */
class Output
{
	public static function raw($data)
	{
        echo (string)$data;

	}

	/**
	 * @param $data
	 */
	public static function json($data)
	{
		echo json_encode($data);
	}

	public static function html($data)
	{
		echo $data;
	}
}