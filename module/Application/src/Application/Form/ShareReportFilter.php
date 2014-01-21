<?php
namespace Application\Form;


use Zend\InputFilter\InputFilter;

class ShareReportFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
				'name'       => 'password',
				'required'   => true,
		));
		
		$this->add(array(
				'name'       => 'recipient',
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