<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Database;

use mysqli;
use Long\Long_Exception;

class MysqliDriver extends DBDriver
{
	protected $_driver = 'mysqli';

	public function __construct()
	{
		parent::__construct();

		$this->_con = new mysqli($this->_host, $this->_user, $this->_password, $this->_database, $this->_port);

		if ($this->_con->connect_errno) {
			Long_Exception::showError("Connect failed: " . $this->_con->connect_error, 500, 'error_db');
			exit(1);
		}

		if (!empty($this->_charset)) {
			$this->_con->query("set names 'utf8'");
		}

	}

	function query($sql, $params = array())
	{
		$stmt = $this->_con->prepare($sql);

		$types = '';
		if (is_array($params)) {
			foreach ($params as $v) {
				$type = strtolower(gettype($v));
				if ($type === 'integer') {
					$types .= 'i';
				} elseif ($type === 'double') {
					$types .= 'd';
				} else {
					$types .= 's';
				}
			}
		}
		$stmt->bind_param($types, $params);
		$stmt->execute();
		$result = $stmt->result_metadata();

		$resultArray = array();
		while ($field = $result->fetch_fields()) {
			foreach ($field as $v) {
				$resultArray[] = $v;
			}
		}


		$this->affectedRows = $stmt->affected_rows;
		$stmt->close();
		if (empty($resultArray)) {
			return $this->affectedRows;
		}
		return $resultArray;
	}


	public function transStart()
	{
		if ($this->_con->autocommit(false)) {
			$this->_con->begin_transaction();
			return true;
		} else {
			return false;
		}
	}

	public function commit()
	{
		if ($this->_con->commit()) {
			$this->_con->autocommit(true);
		}
	}

	public function rollback()
	{
		if ($this->_con->rollback()) {
			return true;
		} else {
			return false;
		}
	}

	public function __destruct()
	{
		$this->_con->close();
	}
}