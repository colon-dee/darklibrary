<?php
final class Repository {

	private $class;

	function __construct($class) {
		$this->class = $class;
	}

	function load(Criteria $criteria = null, $registroUnico = false) {
		$sql = new SqlSelect();
		$sql->addColumn('*');
		$sql->setEntity(constant($this->class . '::TABLE'));
		$sql->setCriteria($criteria);

		return $this->loadSQL($sql, $registroUnico);
	}

	function loadSQL(SqlInstruction $sql, $registroUnico = false) {
		$className = $this->class;
		$con = $className::getConnection();
		$result = $con->query($sql->getInstruction());

		if (!$result) {
			return array();
		}

		$results = array();

		while ($row = $result->fetchObject($this->class)) {
			$results[] = $row;
		}

		if ($registroUnico) {
			return isset($results[0]) ? $results[0] : null;
		} else {
			return $results;
		}
	}

	function delete(Criteria $criteria) {
		$sql = new SqlDelete();
		$sql->setEntity(constant($this->class . '::TABLE'));
		$sql->setCriteria($criteria);

		$className = $this->class;
		$con = $className::getConnection();
		$result = $con->exec($sql->getInstruction());

		return $result;
	}

	function count(Criteria $criteria = null) {
		$sql = new SqlSelect();
		$sql->addColumn('COUNT(*)');
		$sql->setEntity(constant($this->class . '::TABLE'));
		$sql->setCriteria($criteria);

		$className = $this->class;
		$con = $className::getConnection();
		$result = $con->query($sql->getInstruction());

		if ($result) {
			$row = $result->fetch();
			return $row[0];
		}

		return 0;
	}

}
?>
