<?php
namespace Application\Form;

//use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Form;

class Lists extends Form
{

	public function prepareElements()
	{
		// add() can take either an Element/Fieldset instance,
		// or a specification, from which the appropriate object
		// will be built.
        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        
        $this->add(array(
        		'name' => 'double_optin',
        		'options' => array(
        				'label' => 'List type',
        				'value_options' => array(
        						'0' => 'Single Opt-in',
        						'1' => 'Double Opt-in',
        				),
        		),
        		'attributes' => array(
        				'value' => '0'
        		),
        		'type' => '\Zend\Form\Element\Radio'
        ));
        
        $this->add(array(
        		'name' => 'sub_success_page',
        		'options' => array(
        				'label' => 'Subscribe success page',
        		),
        		'attributes' => array(
        				'type' => 'text',
        				'placeholder' => 'http://'
        		),
        		'type' => '\Zend\Form\Element\Url',
        		'allowEmpty' => true,
        		'required' => false
        ));
        
        $this->add(array(
        		'name' => 'sub_confirmed_page',
        		'options' => array(
        				'label' => 'Subscription confirmed page',
        		),
        		'attributes' => array(
        				'type' => 'text',
        				'placeholder' => 'http://'
        		),
        		'type' => '\Zend\Form\Element\Url'
        ));
        
        $this->add(array(
        		'name' => 'unsub_from_all',
        		'options' => array(
        				'label' => 'Unsubscribe type',
        				'value_options' => array(
        						'0' => 'Only this list',
        						'1' => 'All lists',
        				),
        		),
        		'attributes' => array(
        				'value' => '0'
        		),
        		'type' => '\Zend\Form\Element\Radio'
        ));
        
        $this->add(array(
        		'name' => 'unsub_page',
        		'options' => array(
        				'label' => 'Unsubscribe confirmation page',
        		),
        		'attributes' => array(
        				'type' => 'text',
        				'placeholder' => 'http://'
        		),
        		'type' => '\Zend\Form\Element\Url'
        ));

        $this->add(array(
        		'name' => 'thanks_send',
        		'options' => array(
        				'label' => 'Send user a thank you email after they subscribe through the subscribe form or API?',
        				'value_options' => array(
        						'use_hidden_element' => true,
                     			'checked_value' => '1',
                     			'unchecked_value' => '0'
        				),
        		),
        		'type' => '\Zend\Form\Element\Checkbox'
        ));
        
        $this->add(array(
        		'name' => 'thanks_subject',
        		'options' => array(
        				'label' => 'Thank you email subject',
        		),
        		'attributes' => array(
        				'type' => 'text',
        				'placeholder' => 'Email subject'
        		),
        ));
        $this->add(array(
        		'name' => 'thanks_message',
        		'options' => array(
        				'label' => 'Thank you email message',
        		),
        		'attributes' => array(
        				'placeholder' => 'Email message'
        		),
        		'type' => '\Zend\Form\Element\Textarea'
        ));
        
        $this->add(array(
        		'name' => 'conf_subject',
        		'options' => array(
        				'label' => 'Confirmation email subject',
        		),
        		'attributes' => array(
        				'type' => 'text',
        				'placeholder' => 'Email subject'
        		),
        ));
        $this->add(array(
        		'name' => 'conf_message',
        		'options' => array(
        				'label' => 'Double Opt-In confirmation message',
        		),
        		'attributes' => array(
        				'placeholder' => 'Email message'
        		),
        		'type' => '\Zend\Form\Element\Textarea'
        ));

        $this->add(array(
        		'name' => 'unsub_send',
        		'options' => array(
        				'label' => 'Send user a confirmation email after they unsubscribe from a newsletter or through the API?',
        				'value_options' => array(
        						'use_hidden_element' => true,
                     			'checked_value' => '1',
                     			'unchecked_value' => '0'
        				),
        		),
        		'type' => '\Zend\Form\Element\Checkbox'
        ));

        $this->add(array(
        		'name' => 'unsub_subject',
        		'options' => array(
        				'label' => 'Goodbye email subject',
        		),
        		'attributes' => array(
        				'type' => 'text',
        				'placeholder' => 'Email subject'
        		),
        ));
        $this->add(array(
        		'name' => 'unsub_message',
        		'options' => array(
        				'label' => 'Goodbye email message',
        		),
        		'attributes' => array(
        				'placeholder' => 'Email message'
        		),
        		'type' => '\Zend\Form\Element\Textarea'
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Add',
                'type'  => 'submit'
            ),
        ));

		// We could also define the input filter here, or
		// lazy-create it in the getInputFilter() method.
	}
}