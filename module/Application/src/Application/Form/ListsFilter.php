<?php
namespace Application\Form;


use Zend\InputFilter\InputFilter;

class ListsFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
				'name'       => 'name',
				'required'   => true,
				'filters'   => array(
						array('name' => 'StringTrim'),
				),
		));

	}
}