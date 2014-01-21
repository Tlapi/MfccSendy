<?php
namespace Application\Form;

//use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Form;

class Settings extends Form
{

	public function prepareElements()
	{
		// add() can take either an Element/Fieldset instance,
		// or a specification, from which the appropriate object
		// will be built.
        $this->add(array(
            'name' => 'mandrill_api_key',
            'options' => array(
                'label' => 'Mandrill Api key',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        
        $this->add(array(
            'name' => 'sendgird_api_user',
            'options' => array(
                'label' => 'Sendgrid Api user',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'sendgird_api_key',
            'options' => array(
                'label' => 'Sendgrid Api key',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        
        $this->add(array(
            'name' => 'amazon_api_key',
            'options' => array(
                'label' => 'Amazon Api key',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        
        $this->add(array(
            'name' => 'pubnub_subscribe_key',
            'options' => array(
                'label' => 'PubNub subscribe key',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'pubnub_publish_key',
            'options' => array(
                'label' => 'PubNub publish key',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'pubnub_secret_key',
            'options' => array(
                'label' => 'PubNub secret key',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Save settings',
                'type'  => 'submit'
            ),
        ));

		// We could also define the input filter here, or
		// lazy-create it in the getInputFilter() method.
	}
}