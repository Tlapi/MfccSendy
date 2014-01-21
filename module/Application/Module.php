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
use Zend\Mvc\Router\RouteMatch;

class Module
{

	protected $whitelist = array('public/report/login', 'zfcuser/login', 'hooks', 'cron', 'public/unsubscribe', 'public/resubscribe', 'api', 'api/subscriber');
	
	protected $publicreportlist = array('public/report', 'public/report/logout', 'public/report/os', 'public/report/demographics', 'public/report/links', 
			'public/report/activity/sent', 'public/report/activity/opened', 'public/report/activity/clicked', 'public/report/activity/unsubscribed',
			'public/report/activity/complained', 'public/report/activity/bounced', 'campaigns/render', 'subscribers/show'
	);

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        // Check authetication
        $sm = $e->getApplication()->getServiceManager();
        $list = $this->whitelist;
        $prlist = $this->publicreportlist;
        $auth = $sm->get('zfcuser_auth_service');

        $eventManager->attach(MvcEvent::EVENT_ROUTE, function($e) use ($prlist, $list, $auth) {
        	//die('route');
        	$match = $e->getRouteMatch();

        	// No route match, this is a 404
        	if (!$match instanceof RouteMatch) {
        		return;
        	}

        	// Route is whitelisted
        	$name = $match->getMatchedRouteName();
        	if (in_array($name, $prlist)) {
        		return;
        	}
        	if (in_array($name, $list)) {
        		if($name=='zfcuser/login')
        			$e->getViewModel()->setTemplate('layout/login');
        		else
        			$e->getViewModel()->setTemplate('layout/public');
        		return;
        	}

        	// User is authenticated
        	if ($auth->hasIdentity()) {
        		return;
        	}

        	// Redirect to the user login page, as an example
        	$router   = $e->getRouter();
        	$url      = $router->assemble(array(), array(
        			'name' => 'zfcuser/login'
        	));

        	$response = $e->getResponse();
        	$response->getHeaders()->addHeaderLine('Location', $url);
        	$response->setStatusCode(302);

        	return $response;
        }, -100);

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
    					'printMandrillStats'       => 'Application\View\Helper\PrintMandrillStats',
    					'formatStatNumber'       => 'Application\View\Helper\PrintStatNumber',
    					'printReputationNumber'       => 'Application\View\Helper\PrintReputationNumber',
    			)
    	);

    }

    public function getServiceConfig()
    {
    	return array(
    			'invokables' => array(
    					'listsService' => 'Application\Service\Lists',
    					'Application\Authentication\Adapter\Host' => 'Application\Authentication\Adapter\Host',
    			),
    			'factories' => array(
    					'mandrill' => function ($sm) {
    						$config  = $sm->get('config');
    						$mandrill = new \Mandrill($config['services']['mandrill']['api_key']);
    						curl_setopt($mandrill->ch, CURLOPT_SSL_VERIFYPEER, false);
    						return $mandrill;
    					},
    					'sendgrid' => function ($sm) {
    						$config  = $sm->get('config');
    						$sendgrid = new \SendGridPHP\Web($config['services']['sendgrid']['api_user'], $config['services']['sendgrid']['api_key']);
    						return $sendgrid;
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
    					'campaignSender' => function ($sm) {
    						$campaignSender = new \Application\Service\CampaignSender();
    						$campaignSender->setMandrill($sm->get('mandrill'));
    						return $campaignSender;
    					},
    					'campaignLog' => function ($sm) {
    						$campaignLog = new \Application\Service\CampaignLog();
    						$campaignLog->setLogRepository($sm->get('Doctrine\ORM\EntityManager')->getRepository('Application\Entity\CampaignLog'));
    						return $campaignLog;
    					},
    					'pubnubService' => function ($sm) {
    						$config  = $sm->get('config');
    						$pubnub = new \Pubnub\Pubnub(
							    $config['services']['pubnub']['publish_key'],  ## PUBLISH_KEY
							    $config['services']['pubnub']['subscribe_key'],  ## SUBSCRIBE_KEY
							    $config['services']['pubnub']['secret_key'],      ## SECRET_KEY
							    false    ## SSL_ON?
							);
    						return $pubnub;
    					},
    			),
    	);
    }

}
