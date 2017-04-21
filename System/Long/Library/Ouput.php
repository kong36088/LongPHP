<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Library;


class Output
{
	public static function raw($data)
	{
		echo $data;
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