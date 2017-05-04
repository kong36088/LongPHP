<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Library;

/**
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
        header("Content-type:application/json");
        echo json_encode($data);
	}

	public static function html($data)
	{
	    header("Content-type:text/html");
		echo $data;
	}

}