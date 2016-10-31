<?php
class CustomFilter extends Expression {

	private $filter;

	function __construct($filter) {
		$this->filter = $filter;
	}

	function dump() {
		return $this->filter;
	}

}
?>
