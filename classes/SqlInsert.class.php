<?php
final class SqlInsert extends SqlRowData {

	function setCriteria(Criteria $criteria = null) {
		throw new Exception('Cannot call setCriteria from' . __CLASS__);
	}

	function getInstruction() {
		$columns = implode(', ', array_keys($this->columnValues));
		$values = implode(', ', array_values($this->columnValues));

		return "INSERT INTO {$this->entity} ($columns) VALUES ($values)";
	}

}
?>
