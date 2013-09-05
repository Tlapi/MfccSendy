<?php
namespace Application\Form;


use Zend\InputFilter\InputFilter;

class BrandFilter extends InputFilter
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
				'name'       => 'from_name',
				'required'   => true,
				'filters'   => array(
						array('name' => 'StringTrim'),
				),
		));

		$this->add(array(
				'name'       => 'from_email',
				'required'   => true,
				'validators' => array(
						array(
								'name'    => 'EmailAddress'
						),
				),
				'filters'   => array(
						array('name' => 'StringTrim'),
				),
		));

		$this->add(array(
				'name'       => 'reply_to',
				'required'   => true,
				'validators' => array(
						array(
								'name'    => 'EmailAddress'
						),
				),
				'filters'   => array(
						array('name' => 'StringTrim'),
				),
		));

	}
}