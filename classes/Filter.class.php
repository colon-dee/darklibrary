<?php
class Filter extends Expression {

	private $field;
	private $operator;
	private $value;

	/**
	 * Creates a filter from the parameters values
	 *
	 * For example, the following code should be used to create a filter that
	 * returns the records with a value greater than 5:
	 *
	 * $filter = new Filter('value', '>', 5);
	 *
	 * @param string $field field name
	 * @param string $operator comparison operator (<, >, =, LIKE, <=, >=)
	 * @param mixed $value field value
	 */
	function __construct($field, $operator, $value) {
		$this->field = $field;
		$this->operator = $operator;
		$this->value = $this->transform($value);
	}

	static function transform($value) {
		if (is_array($value)) {
			$tmp = array();

			foreach ($value as $x) {
				$tmp[] = self::transform($x);
			}

			$result = '(' . implode(', ', $tmp) . ')';
		} else if (is_string($value)) {
			$value = htmlspecialchars($value);
			$value = addslashes($value);
			$result = "'$value'";
		} else if (is_null($value)) {
			$result = 'NULL';
		} else if (is_bool($value)) {
			$result = $value ? 'TRUE' : 'FALSE';
		} else {
			$result = $value;
		}

		return $result;
	}

	function dump() {
		return "{$this->field} {$this->operator} {$this->value}";
	}

}
?>
