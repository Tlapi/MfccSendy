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
    public function indexAction()
    {

    	// TODO move to factory
    	$mandrill = new \Mandrill('SlcPQn3V1Ooa8grE5j9Liw');

        return new ViewModel(array(
        	'mandrill' => $mandrill
        ));
    }
}
