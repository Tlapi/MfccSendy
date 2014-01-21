<?php
namespace Application\Form;


use Zend\InputFilter\InputFilter;

class SettingsFilter extends InputFilter
{
	public function __construct()
	{
		/*
		$this->add(array(
				'name'       => 'mandrill_api_key',
				'validators' => array(
					new \Zend\Validator\Callback(function($value){
					    // some validation
					    return true;
					})
                ),
		));*/

	}
}