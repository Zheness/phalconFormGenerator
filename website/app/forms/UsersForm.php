<?php

class UsersForm {
	private function _Firstname() {
		$element = new \Phalcon\Forms\Element\Text("Firstname");
		$element->setLabel("Firstname");
		$element->addValidator(new \Phalcon\Validation\Validator\StringLength([
			"max" => 65
		]));
		return $element;
	}
	private function _Lastname() {
		$element = new \Phalcon\Forms\Element\Text("Lastname");
		$element->setLabel("Lastname");
		$element->addValidator(new \Phalcon\Validation\Validator\StringLength([
			"max" => 65
		]));
		return $element;
	}
	private function _Email() {
		$element = new \Phalcon\Forms\Element\Text("Email");
		$element->setLabel("Email");
		$element->addValidator(new \Phalcon\Validation\Validator\StringLength([
			"max" => 105
		]));
		return $element;
	}
	private function _Password() {
		$element = new \Phalcon\Forms\Element\Password("Password");
		$element->setLabel("Password");
		return $element;
	}
	private function _Isadmin() {
		$element = new \Phalcon\Forms\Element\Check("Isadmin");
		$element->setLabel("Isadmin");
		return $element;
	}
	private function _Biography() {
		$element = new \Phalcon\Forms\Element\Textarea("Biography");
		$element->setLabel("Biography");
		return $element;
	}
	private function _ShortDescription() {
		$element = new \Phalcon\Forms\Element\Textarea("ShortDescription");
		$element->setLabel("ShortDescription");
		return $element;
	}
	private function _UserStatusId() {
		$element = new \Phalcon\Forms\Element\Select("UserStatusId");
		$element->setLabel("UserStatusId");
		$element->setOptions([]);
		return $element;
	}
	private function _LastDateLogin() {
		$element = new \Phalcon\Forms\Element\Date("LastDateLogin");
		$element->setLabel("LastDateLogin");
		return $element;
	}
	public function setFields() {
		$this->add($this->_Firstname());
		$this->add($this->_Lastname());
		$this->add($this->_Email());
		$this->add($this->_Password());
		$this->add($this->_Isadmin());
		$this->add($this->_Biography());
		$this->add($this->_ShortDescription());
		$this->add($this->_UserStatusId());
		$this->add($this->_LastDateLogin());
	}
}

