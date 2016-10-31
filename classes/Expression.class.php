<?php
abstract class Expression {

	// Logical operators
	const OPERATOR_AND = 'AND ';
	const OPERATOR_OR = 'OR ';

	abstract function dump();

}
?>
