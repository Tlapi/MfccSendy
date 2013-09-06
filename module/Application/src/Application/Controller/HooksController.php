<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class HooksController extends AbstractActionController
{
    public function indexAction()
    {
    	$pubnub = $this->getServiceLocator()->get('pubnubService');


    	$info = $pubnub->publish(array(
    			'channel' => 'mfcc_sender_event', ## REQUIRED Channel to Send
    			'message' => json_encode(array(
    				'somkey' => 'someval'
    			))
    	));

    	print_r($info);

    	die('hook');

        return new ViewModel(array(

        ));
    }

    public function setAction()
    {
    	// TODO move to factory
    	$webhooks = $this->getServiceLocator()->get('webhooks');

    	$webhooks->addWebhook();

		$this->redirect()->toRoute('home');
    }
}
