<?php
abstract class SqlInstruction {

	protected $criteria;
	protected $entity;

	function setEntity($entity) {
		$this->entity = $entity;
	}

	function getEntity() {
		return $this->entity;
	}

	function setCriteria(Criteria $criteria = null) {
		$this->criteria = $criteria;
	}

	abstract function getInstruction();

}
?>
