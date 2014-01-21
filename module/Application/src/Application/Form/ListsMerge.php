<?php
namespace Application\Form;

//use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Form;

class ListsMerge extends Form
{

	public function prepareElements()
	{
		// add() can take either an Element/Fieldset instance,
		// or a specification, from which the appropriate object
		// will be built.
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Merge',
                'type'  => 'submit'
            ),
        ));

		// We could also define the input filter here, or
		// lazy-create it in the getInputFilter() method.
	}
}