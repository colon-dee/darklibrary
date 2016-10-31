<?php
final class SqlSelect extends SqlInstruction {

	private $columns;

	function __construct() {
		$this->columns = array();
	}

	function addColumn($column) {
		$this->columns[] = $column;
	}

	function getInstruction() {
		$columns = implode(', ', $this->columns);

		$sql = "SELECT $columns FROM {$this->entity}";

		if ($this->criteria) {
			$expression = $this->criteria->dump();

			if ($expression) {
				$sql .= ' WHERE ' . $expression;
			}

			$having = $this->criteria->getProperty('having');
			$order = $this->criteria->getProperty('order');
			$limit = $this->criteria->getProperty('limit');
			$offset = $this->criteria->getProperty('offset');

			if ($having) {
				$sql .= ' HAVING ' . $having;
			}

			if ($order) {
				$sql .= ' ORDER BY ' . $order;
			}

			if ($limit) {
				$sql .= ' LIMIT ' . $limit;
			}

			if ($offset) {
				$sql .= ' OFFSET ' . $offset;
			}
		}

		return $sql;
	}

}
?>
