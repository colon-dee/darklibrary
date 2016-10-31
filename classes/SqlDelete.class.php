<?php
final class SqlDelete extends SqlInstruction {

	function getInstruction() {
		$sql = "DELETE FROM {$this->entity}";

		if ($this->criteria) {
			$sql .= ' WHERE ' . $this->criteria->dump();
		}

		return $sql;
	}

}
?>
