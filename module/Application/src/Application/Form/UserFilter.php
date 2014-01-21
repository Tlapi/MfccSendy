<?php
namespace Application\Form;


use Zend\InputFilter\InputFilter;

class UserFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
				'name'       => 'email',
				'required'   => true,
				'filters'   => array(
						array('name' => 'StringTrim'),
				),
		));

	}
}