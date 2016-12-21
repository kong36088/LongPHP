<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long;

use Long\Config\Config;
use Long\Log\Log;

class Long_Model
{
	protected $tableName = '';

	public function __construct()
	{
		Log::writeLog('Init Model', 'info');

		$dbDriver = 'Long\\Database\\' . ucfirst(Config::get('db_driver')) . 'Driver';
		$this->db = new $dbDriver();
	}
}