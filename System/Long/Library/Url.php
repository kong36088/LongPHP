<?php
/**
 * Longphp
 * Author: William Jiang <william@jwlchian.cn>
 */

namespace Long\Library;

use Long\Core\Config;

class Url
{
    /**
     * get the url of your site
     * @param string $protocol
     * @return bool|string
     */
	public static function siteUrl($protocol = '')
	{
		if (isCli()) return '';
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