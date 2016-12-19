<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Url;

use Long\Config\Config;

class Url
{
	/**
	 * 获取网站url
	 * @param string $protocol
	 */
	public static function siteUrl($protocol = '')
	{
		if (is_cli()) return '';
		$baseUrl = rtrim(Config::get('base_url'), '/');
		$baseUrl = substr($baseUrl, strpos($baseUrl, '//') + 2);

		if (empty($baseUrl)) {
			$baseUrl = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_ADDR'];
		}

		if (!empty($protocol)) {
			$baseUrl = $protocol . '://' . $baseUrl;
		}
		$baseUrl .= '/';

		return $baseUrl;
	}
}