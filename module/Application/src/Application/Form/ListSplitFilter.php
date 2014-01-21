<?php
namespace Application\Form;


use Zend\InputFilter\InputFilter;

class ListSplitFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
				'name'       => 'chunk_size',
				'required'   => true
		));

	}
}