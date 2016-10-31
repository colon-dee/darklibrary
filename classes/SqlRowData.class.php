<?php
abstract class SqlRowData extends SqlInstruction {

	protected $columnValues;

	function setRowData($column, $value) {
		if (is_string($value)) {
			$value = htmlspecialchars($value);
			$value = addslashes($value);
			$this->columnValues[$column] = "'$value'";
		} else if (is_bool($value)) {
			$this->columnValues[$column] = $value ? 'TRUE' : 'FALSE';
		} else if (!is_numeric($value) && empty($value)) {
			$this->columnValues[$column] = 'NULL';
		} else {
			$this->columnValues[$column] = $value;
		}
	}

}
?>
