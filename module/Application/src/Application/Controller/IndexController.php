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

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	// TODO move to factory
    	$config  = $this->getServiceLocator()->get('config');
    	$mandrill = new \Mandrill($config['mandrill']['api_key']);
    	curl_setopt($mandrill->ch, CURLOPT_SSL_VERIFYPEER, false);
    	$mandrillInfo = $mandrill->users->info();

        return new ViewModel(array(
        	'mandrillInfo' => $mandrillInfo
        ));
    }
}
