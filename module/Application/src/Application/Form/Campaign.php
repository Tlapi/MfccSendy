<?php
namespace Application\Form;

//use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Form;

class Campaign extends Form
{

	public function prepareElements()
	{
		// add() can take either an Element/Fieldset instance,
		// or a specification, from which the appropriate object
		// will be built.

        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'subject',
            'options' => array(
                'label' => 'Subject',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'from_name',
            'options' => array(
                'label' => 'From name',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'from_email',
            'options' => array(
                'label' => 'From e-mail',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'reply_to',
            'options' => array(
                'label' => 'Reply to e-mail',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $this->add(array(
        		'name' => 'plain_text',
        		'options' => array(
        				'label' => 'Plain text',
        		),
        		'attributes' => array(
        				'type' => 'textarea',
        		),
        ));

        $this->add(array(
        		'name' => 'html_text',
        		'options' => array(
        				'label' => 'HMTL code',
        		),
        		'attributes' => array(
        				'type' => 'textarea',
        		),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Save & next',
                'type'  => 'submit'
            ),
        ));

		// We could also define the input filter here, or
		// lazy-create it in the getInputFilter() method.
	}
}