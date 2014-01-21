<?php
namespace Application\Form;


use Zend\InputFilter\InputFilter;

class ListsMergeFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
				'name'       => 'merge',
				'required'   => true
		));

	}
}