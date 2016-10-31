<?php
final class SqlCustom extends SqlInstruction {

	private $sql;
	private $fields;

	function __construct($sql) {
		$this->sql = $sql;
		$this->fields = array();
	}

	function add($field) {
		$this->fields[] = $field;
	}

	function getInstruction() {
		$tmp = $this->sql;

		foreach ($this->fields as $field) {
			$tmp = self::replaceFirst('?', Filter::transform($field), $tmp);
		}

		return $tmp;
	}

	private static function replaceFirst($search, $replace, $subject) {
		$pos = strpos($subject, $search);
		return substr_replace($subject, $replace, $pos, strlen($search));
	}

}
?>
