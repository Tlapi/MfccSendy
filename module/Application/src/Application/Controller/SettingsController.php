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

class SettingsController extends AbstractActionController
{
	private $configPath = 'config/autoload/services.local.php';
	
    public function indexAction()
    {
    	// TODO move to factory
    	$mandrill = $this->getServiceLocator()->get('mandrill');
    	$mandrillInfo = $mandrill->users->info();
    	$this->layout()->setVariable('mandrillInfo', $mandrillInfo);
    	
    	// main menu
    	$this->layout()->setVariable('active', 'dashboard');
    	
    	// get config
    	$configs = $this->getServiceLocator()->get('config');
    	$config = $configs['services'];
    	
    	$form = new \Application\Form\Settings();
    	$form->prepareElements();
    	$form->setInputFilter(new \Application\Form\SettingsFilter());
    	
    	$request = $this->getRequest();
    	if ($request->isPost()){
    		$data = $request->getPost();
    	
    		$form->setData($data);
    		if ($form->isValid()) {
    			$servicesConfig = new \Zend\Config\Config(array('services' => $config), true);
    			
    			$servicesConfig->services->mandrill->api_key = $data['mandrill_api_key'];
    			$servicesConfig->services->sendgrid->api_user = $data['sendgird_api_user'];
    			$servicesConfig->services->sendgrid->api_key = $data['sendgird_api_key'];
    			$servicesConfig->services->amazon->api_key = $data['amazon_api_key'];
    			$servicesConfig->services->pubnub->subscribe_key = $data['pubnub_subscribe_key'];
    			$servicesConfig->services->pubnub->publish_key = $data['pubnub_publish_key'];
    			$servicesConfig->services->pubnub->secret_key = $data['pubnub_secret_key'];
    			
    			$writer = new \Zend\Config\Writer\PhpArray();
    			$writer->toFile($this->configPath, $servicesConfig);
    			
    			$this->flashMessenger()->addMessage('Changes has been saved!');
    			$this->redirect()->toRoute('settings');
    		}
    	} else {
    		$form->setData(array(
    				'mandrill_api_key' => $config['mandrill']['api_key'],
    				'sendgird_api_user' => $config['sendgrid']['api_user'],
    				'sendgird_api_key' => $config['sendgrid']['api_key'],
    				'amazon_api_key' => $config['amazon']['api_key'],
    				'pubnub_subscribe_key' => $config['pubnub']['subscribe_key'],
    				'pubnub_publish_key' => $config['pubnub']['publish_key'],
    				'pubnub_secret_key' => $config['pubnub']['secret_key'],
    		));
    	}
    	
        return new ViewModel(array(
        		'form' => $form
        ));
    }
}
