<?php

class UserStatusForm {
	private function _Name() {
		$element = new \Phalcon\Forms\Element\Text("Name");
		$element->setLabel("Name");
		$element->addValidator(new \Phalcon\Validation\Validator\StringLength([
			"max" => 45
		]));
		return $element;
	}
	public function setFields() {
		$this->add($this->_Name());
	}
}

