<?php

class UsersForm extends \Phalcon\Forms\Form
{

	public function setFields()
	{
		$this->add($this->firstnameField());
		$this->add($this->lastnameField());
		$this->add($this->emailField());
		$this->add($this->passwordField());
		$this->add($this->isadminField());
		$this->add($this->biographyField());
		$this->add($this->shortDescriptionField());
		$this->add($this->userStatusIdField());
		$this->add($this->lastDateLoginField());
	}


	private function firstnameField()
	{
		$element = new \Phalcon\Forms\Element\Text("firstname");
		$element->setLabel("firstname");
		$element->addValidator(new \Phalcon\Validation\Validator\StringLength([
		    "max" => 65
		]));
		return $element;
	}


	private function lastnameField()
	{
		$element = new \Phalcon\Forms\Element\Text("lastname");
		$element->setLabel("lastname");
		$element->addValidator(new \Phalcon\Validation\Validator\StringLength([
		    "max" => 65
		]));
		return $element;
	}


	private function emailField()
	{
		$element = new \Phalcon\Forms\Element\Text("email");
		$element->setLabel("email");
		$element->addValidator(new \Phalcon\Validation\Validator\StringLength([
		    "max" => 105
		]));
		return $element;
	}


	private function passwordField()
	{
		$element = new \Phalcon\Forms\Element\Password("password");
		$element->setLabel("password");
		return $element;
	}


	private function isadminField()
	{
		$element = new \Phalcon\Forms\Element\Check("isadmin");
		$element->setLabel("isadmin");
		return $element;
	}


	private function biographyField()
	{
		$element = new \Phalcon\Forms\Element\Textarea("biography");
		$element->setLabel("biography");
		return $element;
	}


	private function shortDescriptionField()
	{
		$element = new \Phalcon\Forms\Element\Textarea("shortDescription");
		$element->setLabel("shortDescription");
		return $element;
	}


	private function userStatusIdField()
	{
		$element = new \Phalcon\Forms\Element\Select("userStatusId");
		$element->setLabel("userStatusId");
		$element->setOptions([]);
		return $element;
	}


	private function lastDateLoginField()
	{
		$element = new \Phalcon\Forms\Element\Date("lastDateLogin");
		$element->setLabel("lastDateLogin");
		return $element;
	}

}
