<?php
class Criteria extends Expression {

	private $expressions;
	private $operators;
	private $properties;

	function __construct() {
		$this->expressions = array();
		$this->operators = array();
		$this->properties = array();
	}

	function add(Expression $expression, $operator = self::OPERATOR_AND) {
		if (empty($this->expressions)) {
			$operator = NULL;
		}

		$this->expressions[] = $expression;
		$this->operators[] = $operator;
	}

	function dump() {
		$result = '';

		foreach ($this->expressions as $i => $expression) {
			$operator = $this->operators[$i];
			$result .= $operator . $expression->dump() . ' ';
		}

		$result = trim($result);
		return "($result)";
	}

	function setProperty($property, $value = null) {
		$this->properties[$property] = $value;
	}

	function getProperty($property) {
		if (isset($this->properties[$property])) {
			return $this->properties[$property];
		}
		return null;
	}

}
?>
