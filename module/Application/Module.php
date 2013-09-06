<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getViewHelperConfig()
    {
    	return array(
    			'invokables' => array(
    					'userStatus'       => 'Application\View\Helper\UserStatus',
    					'campaignStatus'       => 'Application\View\Helper\CampaignStatus',
    					'printMandrillStats'       => 'Application\View\Helper\PrintMandrillStats'
    			)
    	);

    }

    public function getServiceConfig()
    {
    	return array(
    			'invokables' => array(
    			),
    			'factories' => array(
    					'mandrill' => function ($sm) {
    						$config  = $sm->get('config');
    						$mandrill = new \Mandrill($config['mandrill']['api_key']);
    						curl_setopt($mandrill->ch, CURLOPT_SSL_VERIFYPEER, false);
    						return $mandrill;
    					},
    					'webhooks' => function ($sm) {
    						$webhooks = new \Application\Service\Webhooks();
    						$webhooks->setMandrill($sm->get('mandrill'));
    						return $webhooks;
    					},
    					'campaignStats' => function ($sm) {
    						$campaignStats = new \Application\Service\CampaignStats();
    						$campaignStats->setMandrill($sm->get('mandrill'));
    						return $campaignStats;
    					},
    			),
    	);
    }

}
