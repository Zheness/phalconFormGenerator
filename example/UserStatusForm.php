<?php

class UserStatusForm extends \Phalcon\Forms\Form
{

	public function setFields()
	{
		$this->add($this->nameField());
	}


	private function nameField()
	{
		$element = new \Phalcon\Forms\Element\Text("name");
		$element->setLabel("name");
		$element->addValidator(new \Phalcon\Validation\Validator\StringLength([
		    "max" => 45
		]));
		return $element;
	}

}
