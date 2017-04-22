<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Model;


use Long\Core\LongModel;

class TestModel extends LongModel
{
	public function getById($id = 1)
	{
		return $this->db->query('SELECT * FROM test_table WHERE id = ?', array($id));
	}

	public function insertTestData()
	{
		return $this->db->query('INSERT INTO test_table(`number`,`double`,`string`,`time`) VALUES(?,?,?,?)', [1, 1.111, 'string 测试', date('Y-m-d H:i:s')]);
	}

	public function updateTestData($id = 1,$update)
	{
		return $this->db->query('UPDATE test_table SET `string` = ? WHERE `id` = ?', array($update,$id));
	}

	public function deleteTestData($id = 1)
	{
		return $this->db->query('DELETE FROM test_table WHERE id = ?', array($id));
	}

	public function transTestData(){
		$this->db->transStart();
		$this->updateTestData(1, '这里是trans');
		$this->db->commit();

		$this->db->transStart();
		$this->updateTestData(1, '这里是rollback');
		$this->db->rollback();

	}
}