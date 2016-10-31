<?php
final class SqlUpdate extends SqlRowData {

	function getInstruction() {
		$set = array();

		foreach ($this->columnValues as $column => $value) {
			$set[] = "$column = $value";
		}

		$sql = "UPDATE {$this->entity} SET " . implode(', ', $set);

		if ($this->criteria) {
			$sql .= ' WHERE ' . $this->criteria->dump();
		}

		return $sql;
	}

}
?>
