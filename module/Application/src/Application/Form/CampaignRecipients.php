<?php
namespace Application\Form;

//use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Form;

class CampaignRecipients extends Form
{

	public function prepareElements(\Application\Entity\Campaign $campaign)
	{
		// add() can take either an Element/Fieldset instance,
		// or a specification, from which the appropriate object
		// will be built.
		$options = array();
		foreach($campaign->brand->lists as $list){
			$options[$list->id] = $list->name;
		}

        $this->add(array(
            'name' => 'recipients',
            'options' => array(
                'label' => 'Recipients',
            	'value_options' => $options
            ),
            'attributes' => array(
                'multiple' => 'multiple',
            ),
        	'type' => 'Zend\Form\Element\Select',
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Send this campaign now!',
                'type'  => 'submit'
            ),
        ));

		// We could also define the input filter here, or
		// lazy-create it in the getInputFilter() method.
	}
}