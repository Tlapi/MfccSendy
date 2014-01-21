<?php
namespace Application\Form;

//use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Form;

class User extends Form
{

	public function prepareElements()
	{
		// add() can take either an Element/Fieldset instance,
		// or a specification, from which the appropriate object
		// will be built.

        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'E-mail',
            ),
            'attributes' => array(
                'type' => 'text',
            	'autocomplete' => 'off',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
                'type' => 'text',
                'autocomplete' => 'off',
            ),
        ));

        $this->add(array(
            'name' => 'role',
            'options' => array(
                'label' => 'Role',

            ),
        	'type' => 'Select',
        	'attributes' => array(
        		'options' => array(
        			'test' => 'Hi, Im a test!',
        			'Foo' => 'Bar',
        		),
        	),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Save new user',
                'type'  => 'submit'
            ),
        ));

		// We could also define the input filter here, or
		// lazy-create it in the getInputFilter() method.
	}
}