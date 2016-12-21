<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Database;


use Long\Config\Config;

abstract class DBDriver
{
	protected $_host = '';

	protected $_user = '';

	protected $_password = '';

	protected $_port = 3306;

	protected $_database = '';

	protected $_charset = 'utf-8';

	protected $_con;

	/**
	 * 保存orm生成的需要查询的字段结果
	 * @var array
	 */
	protected $_orm_select = array();

	/**
	 * 数据查询影响的行数
	 * @var int
	 */
	public $affectedRows = 0;

	public $queryResult;

	public $fieldCount = 0;

	public $numRows = 0;

	public function __construct()
	{
		$this->_host = Config::get('db_host');
		$this->_user = Config::get('db_user');
		$this->_password = Config::get('db_password');
		$this->_port = Config::get('db_port');
		$this->_database = Config::get('db_database');
		$this->_charset = Config::get('db_charset');
	}

	/**
	 * 禁止克隆
	 */
	private function __clone()
	{
	}

	/**
	 * TODO 增加ORM模块
	 * @param string $select
	 * @return $this
	 */
	public function select($select = '*')
	{
		if (is_string($select)) {
			$select = explode(',', $select);
		}

		foreach ($select as $v) {
			$val = trim($v);

			if ($val !== '') {
				$this->_orm_select[] = $val;
			}
		}

		return $this;
	}

	abstract function query($sql, $params = array());

	abstract public function transStart();

	abstract public function commit();

	/**
	 * 关闭数据库连接
	 */
	abstract public function __destruct();
}