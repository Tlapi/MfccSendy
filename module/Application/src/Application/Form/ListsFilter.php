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
		
		$this->add(array(
				'name'       => 'sub_success_page',
				'required'   => false
		));
		$this->add(array(
				'name'       => 'sub_confirmed_page',
				'required'   => false
		));
		$this->add(array(
				'name'       => 'unsub_page',
				'required'   => false
		));

	}
}