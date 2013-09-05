<?php
namespace Application\Form;


use Zend\InputFilter\InputFilter;

class CampaignRecipientsFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
				'name'       => 'recipients',
				'required'   => true
		));

	}
}