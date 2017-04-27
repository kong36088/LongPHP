<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Core;

use Long\Library\Logger\Log;

class LongModel
{
	protected $tableName = '';

	public function __construct()
	{
		Log::info('Init Model');

		$dbDriver = 'Long\\Database\\' . ucfirst(Config::get('db_driver')) . 'Driver';
		$dbDriverFile = SYS_PATH . DIRECTORY_SEPARATOR . 'Long/Database/'.ucfirst(Config::get('db_driver')) . 'Driver.php';

		if (!file_exists($dbDriverFile)) {
			throwError('Db driver type error', 500,true);
		}

		$this->db = new $dbDriver();
	}
}