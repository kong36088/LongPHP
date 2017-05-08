<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Database;

class MysqliDriver extends DBDriver
{
	protected $_driver = 'mysqli';

	public function __construct()
	{
		parent::__construct();

		$this->_con = new \mysqli($this->_host, $this->_user, $this->_password, $this->_database, $this->_port);

		if (!$this->_con || $this->_con->connect_errno) {
			throwError("Connect failed: " . $this->_con->connect_error, 500, true, 'error_db');
			exit(1);
		}

		if (!empty($this->_charset)) {
			$this->_con->query("set names '" . $this->_charset . "'");
		}

	}

	function query($sql, $params = array())
	{
		$types = '';
		$binds = array();
		if (is_array($params)) {
			foreach ($params as $k => &$v) {
				$binds[] = &$params[$k];
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

		$stmt = $this->_con->prepare($sql);

		if (!$stmt) {
			throwError('SQL error:' . $this->_con->error, 500, true, 'error_db');
		}

		//多参数动态绑定
		call_user_func_array(array($stmt, 'bind_param'), array_merge(array($types), $binds));
		$ex = $stmt->execute();
		//$metadata = $stmt->result_metadata();
		$metadata = $stmt->get_result();


		$this->fieldCount = $stmt->field_count;
		$this->numRows = $stmt->num_rows;
		$this->affectedRows = $stmt->affected_rows;

		if (is_object($metadata)) {
			$result = array();
			while ($field = $metadata->fetch_array()) {
				$result[] = $field;
			}
			$metadata->close();
		} else {
			$result = $this->affectedRows;
		}

		$stmt->close();

		return $result;
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
			$this->_con->autocommit(true);
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