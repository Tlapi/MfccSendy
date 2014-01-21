<?php
namespace Application\Form;

//use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Form;

class ListSplit extends Form
{

	public function prepareElements()
	{
		// add() can take either an Element/Fieldset instance,
		// or a specification, from which the appropriate object
		// will be built.
        
		$this->add(array(
				'name' => 'chunk_size',
				'options' => array(
						'label' => 'Chunk size',
				),
				'attributes' => array(
						'type' => 'number',
				),
				'required' => true,
				
		));
		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Split',
                'type'  => 'submit'
            ),
        ));

		// We could also define the input filter here, or
		// lazy-create it in the getInputFilter() method.
	}
}