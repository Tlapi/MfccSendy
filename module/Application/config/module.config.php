<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            	'may_terminate' => true,
            	'child_routes' => array(
            				'stats' => array(
            						'type'    => 'Literal',
            						'options' => array(
            								'route'    => 'stats',
            								'defaults' => array(
            										'__NAMESPACE__' => 'Application\Controller',
            										'controller'    => 'Index',
            										'action'        => 'stats',
            								),
            						),
            				),
            	),
            ),
        	// Brands
        	'brands' => array(
        			'type'    => 'Literal',
        			'options' => array(
        					'route'    => '/brands',
        					'defaults' => array(
        							'__NAMESPACE__' => 'Application\Controller',
        							'controller'    => 'Brands',
        							'action'        => 'index',
        					),
        			),
        			'may_terminate' => true,
        			'child_routes' => array(
        					'campaigns' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/:id/campaigns',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Brands',
        											'action'        => 'campaigns',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'lists' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/:id/lists',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Brands',
        											'action'        => 'lists',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'reports' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/:id/reports',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Brands',
        											'action'        => 'reports',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'show' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/show/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Brands',
        											'action'        => 'show',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'edit' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/edit/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Brands',
        											'action'        => 'edit',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'add' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/add',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Brands',
        											'action'        => 'add',
        									)
        							),
        					),
        					'delete' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/delete/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Brands',
        											'action'        => 'delete',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        			),
        	),
        	// Lists
        	'lists' => array(
        			'type'    => 'Literal',
        			'options' => array(
        					'route'    => '/lists'
        			),
        			'may_terminate' => false,
        			'child_routes' => array(
        					'show' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/show/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Lists',
        											'action'        => 'show',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        							'may_terminate' => true,
        							'child_routes' => array(
	        							'add-subscribers' => array(
	        									'type'    => 'Segment',
	        									'options' => array(
	        											'route'    => '/add-subscribers',
	        											'defaults' => array(
	        													'__NAMESPACE__' => 'Application\Controller',
	        													'controller'    => 'Lists',
	        													'action'        => 'addSubscribers',
	        											),
	        									),
	        							),
	        							'remove-subscribers' => array(
	        									'type'    => 'Segment',
	        									'options' => array(
	        											'route'    => '/remove-subscribers',
	        											'defaults' => array(
	        													'__NAMESPACE__' => 'Application\Controller',
	        													'controller'    => 'Lists',
	        													'action'        => 'removeSubscribers',
	        											),
	        									),
	        							),
									)
        					),
        					'edit' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/edit/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Lists',
        											'action'        => 'edit',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'add' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/add/:brand_id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Lists',
        											'action'        => 'add',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'split' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/split/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Lists',
        											'action'        => 'split',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'merge' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/merge/:brand_id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Lists',
        											'action'        => 'merge',
        									),
        									'constraints' => array(
        											'brand_id' => '[0-9]*',
        									),
        							),
        					),
        					'delete' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/delete/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Lists',
        											'action'        => 'delete',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'delete-user' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/delete/:connection_id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Lists',
        											'action'        => 'deleteUser',
        									),
        									'constraints' => array(
        											'connection_id' => '[0-9]*',
        									),
        							),
        					),
        					'resubscribe' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/resubscribe/:connection_id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Lists',
        											'action'        => 'resubscribe',
        									),
        									'constraints' => array(
        											'connection_id' => '[0-9]*'
        									),
        							),
        					),
        					'unsubscribe' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/unsubscribe/:connection_id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Lists',
        											'action'        => 'unsubscribe',
        									),
        									'constraints' => array(
        											'connection_id' => '[0-9]*'
        									),
        							),
        					),
        			),
        	),
        	// Campaigns
        	'campaigns' => array(
        			'type'    => 'Literal',
        			'options' => array(
        					'route'    => '/campaigns'
        			),
        			'may_terminate' => false,
        			'child_routes' => array(
        					'show' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/show/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Campaigns',
        											'action'        => 'show',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        							'may_terminate' => true,
        							'child_routes' => array(
        									'activity' => array(
        											'type'    => 'Literal',
        											'options' => array(
        													'route'    => '/activity',
        											),
        											'child_routes' => array(
	        											'sent' => array(
	        													'type'    => 'Literal',
	        													'options' => array(
	        															'route'    => '/sent',
	        															'defaults' => array(
	        																	'action' => 'activity',
	        																	'filter' => 'sent'
	        															),
	        													),
	        											),
	        											'opened' => array(
	        													'type'    => 'Literal',
	        													'options' => array(
	        															'route'    => '/opened',
	        															'defaults' => array(
	        																	'action' => 'activity',
	        																	'filter' => 'opened'
	        															),
	        													),
	        											),
	        											'clicked' => array(
	        													'type'    => 'Literal',
	        													'options' => array(
	        															'route'    => '/clicked',
	        															'defaults' => array(
	        																	'action' => 'activity',
	        																	'filter' => 'clicked'
	        															),
	        													),
	        											),
	        											'unsubscribed' => array(
	        													'type'    => 'Literal',
	        													'options' => array(
	        															'route'    => '/unsubscribed',
	        															'defaults' => array(
	        																	'action' => 'activity',
	        																	'filter' => 'unsubscribed'
	        															),
	        													),
	        											),
	        											'complained' => array(
	        													'type'    => 'Literal',
	        													'options' => array(
	        															'route'    => '/complained',
	        															'defaults' => array(
	        																	'action' => 'activity',
	        																	'filter' => 'complained'
	        															),
	        													),
	        											),
	        											'bounced' => array(
	        													'type'    => 'Literal',
	        													'options' => array(
	        															'route'    => '/bounced',
	        															'defaults' => array(
	        																	'action' => 'activity',
	        																	'filter' => 'bounced'
	        															),
	        													),
	        											),
        											)
        									),
        									'links' => array(
        											'type'    => 'Literal',
        											'options' => array(
        													'route'    => '/links',
        													'defaults' => array(
        															'__NAMESPACE__' => 'Application\Controller',
        															'controller'    => 'Campaigns',
        															'action'        => 'links',
        													),
        											),
        									),
        									'os' => array(
        											'type'    => 'Literal',
        											'options' => array(
        													'route'    => '/os',
        													'defaults' => array(
        															'__NAMESPACE__' => 'Application\Controller',
        															'controller'    => 'Campaigns',
        															'action'        => 'os',
        													),
        											),
        									),
        									'demographics' => array(
        											'type'    => 'Literal',
        											'options' => array(
        													'route'    => '/demographics',
        													'defaults' => array(
        															'__NAMESPACE__' => 'Application\Controller',
        															'controller'    => 'Campaigns',
        															'action'        => 'demographics',
        													),
        											),
        									),
        									'share' => array(
        											'type'    => 'Literal',
        											'options' => array(
        													'route'    => '/share',
        													'defaults' => array(
        															'__NAMESPACE__' => 'Application\Controller',
        															'controller'    => 'Campaigns',
        															'action'        => 'share',
        													),
        											),
        									),
        							)
        					),
        					'render' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/render/:id[/:clickmap]',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Campaigns',
        											'action'        => 'render',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'render-pdf' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/render-pdf/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Campaigns',
        											'action'        => 'renderPdf',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'duplicate' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/duplicate/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Campaigns',
        											'action'        => 'duplicate',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'edit' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/edit/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Campaigns',
        											'action'        => 'add',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'send-to' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/send-to/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Campaigns',
        											'action'        => 'sendTo',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'send-test' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/send-test/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Campaigns',
        											'action'        => 'sendTest',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'calculate-recipients' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/calculate-recipients',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Campaigns',
        											'action'        => 'calculateRecipients',
        									),
        							),
        					),
        					'add' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/add/:brand_id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Campaigns',
        											'action'        => 'add',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'delete' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/delete/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Campaigns',
        											'action'        => 'delete',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        			),
        	),
        	// Templates
        	'templates' => array(
	        	'type'    => 'Literal',
	        	'options' => array(
	        			'route'    => '/templates',
	        			'defaults' => array(
	        					'__NAMESPACE__' => 'Application\Controller',
	        					'controller'    => 'Templates',
	        					'action'        => 'index',
	        			),
	        	),
	        	'may_terminate' => true,
	        	'child_routes' => array(
		        	'show' => array(
		        			'type'    => 'Segment',
		        			'options' => array(
		        					'route'    => '/templates/:id',
		        					'defaults' => array(
		        							'__NAMESPACE__' => 'Application\Controller',
		        							'controller'    => 'Templates',
		        							'action'        => 'show',
		        					),
		        					'constraints' => array(
		        							'id' => '[0-9]*',
		        					),
		        			),
		        	),
	        	),
        	),
        	// Public
        	'public' => array(
	        	'type'    => 'Literal',
	        	'options' => array(
	        			'route'    => '/public',
	        			'defaults' => array(
	        					'__NAMESPACE__' => 'Application\Controller',
	        					'controller'    => 'Public',
	        					'action'        => 'index',
	        			),
	        	),
	        	'may_terminate' => true,
	        	'child_routes' => array(
		        	'unsubscribe' => array(
		        			'type'    => 'Segment',
		        			'options' => array(
		        					'route'    => '/unsubscribe/[:id][/:campaign_id]',
		        					'defaults' => array(
		        							'__NAMESPACE__' => 'Application\Controller',
		        							'controller'    => 'Public',
		        							'action'        => 'unsubscribe',
		        					),
		        					'constraints' => array(
		        							//'id' => '[0-9]*',
		        							//'campaign_id' => '[0-9]*',
		        					),
		        			),
		        	),
		        	'resubscribe' => array(
		        			'type'    => 'Segment',
		        			'options' => array(
		        					'route'    => '/resubscribe/[:id][/:campaign_id]',
		        					'defaults' => array(
		        							'__NAMESPACE__' => 'Application\Controller',
		        							'controller'    => 'Public',
		        							'action'        => 'resubscribe',
		        					),
		        					'constraints' => array(
		        							//'id' => '[0-9]*',
		        							//'campaign_id' => '[0-9]*',
		        					),
		        			),
		        	),
		        	'report' => array(
		        			'type'    => 'Segment',
		        			'options' => array(
		        					'route'    => '/report/[:id]',
		        					'defaults' => array(
		        							'__NAMESPACE__' => 'Application\Controller',
		        							'controller'    => 'Campaigns',
		        							'action'        => 'show',
		        							'publicReport' => true
		        					),
		        					'constraints' => array(
		        							'id' => '[0-9]*',
		        					),
		        			),
        					'may_terminate' => true,
        					'child_routes' => array(
        							'activity' => array(
        									'type'    => 'Literal',
        									'options' => array(
        											'route'    => '/activity',
        									),
        									'child_routes' => array(
        											'sent' => array(
        													'type'    => 'Literal',
        													'options' => array(
        															'route'    => '/sent',
        															'defaults' => array(
        																	'action' => 'activity',
        																	'filter' => 'sent',
        																	'publicReport' => true
        															),
        													),
        											),
        											'opened' => array(
        													'type'    => 'Literal',
        													'options' => array(
        															'route'    => '/opened',
        															'defaults' => array(
        																	'action' => 'activity',
        																	'filter' => 'opened',
        																	'publicReport' => true
        															),
        													),
        											),
        											'clicked' => array(
        													'type'    => 'Literal',
        													'options' => array(
        															'route'    => '/clicked',
        															'defaults' => array(
        																	'action' => 'activity',
        																	'filter' => 'clicked',
        																	'publicReport' => true
        															),
        													),
        											),
        											'unsubscribed' => array(
        													'type'    => 'Literal',
        													'options' => array(
        															'route'    => '/unsubscribed',
        															'defaults' => array(
        																	'action' => 'activity',
        																	'filter' => 'unsubscribed',
        																	'publicReport' => true
        															),
        													),
        											),
        											'complained' => array(
        													'type'    => 'Literal',
        													'options' => array(
        															'route'    => '/complained',
        															'defaults' => array(
        																	'action' => 'activity',
        																	'filter' => 'complained',
        																	'publicReport' => true
        															),
        													),
        											),
        											'bounced' => array(
        													'type'    => 'Literal',
        													'options' => array(
        															'route'    => '/bounced',
        															'defaults' => array(
        																	'action' => 'activity',
        																	'filter' => 'bounced',
        																	'publicReport' => true
        															),
        													),
        											),
        									)
        							),
        							'links' => array(
        									'type'    => 'Literal',
        									'options' => array(
        											'route'    => '/links',
        											'defaults' => array(
        													'__NAMESPACE__' => 'Application\Controller',
        													'controller'    => 'Campaigns',
        													'action'        => 'links',
        													'publicReport' => true
        											),
        									),
        							),
        							'os' => array(
        									'type'    => 'Literal',
        									'options' => array(
        											'route'    => '/os',
        											'defaults' => array(
        													'__NAMESPACE__' => 'Application\Controller',
        													'controller'    => 'Campaigns',
        													'action'        => 'os',
        													'publicReport' => true
        											),
        									),
        							),
        							'demographics' => array(
        									'type'    => 'Literal',
        									'options' => array(
        											'route'    => '/demographics',
        											'defaults' => array(
        													'__NAMESPACE__' => 'Application\Controller',
        													'controller'    => 'Campaigns',
        													'action'        => 'demographics',
        													'publicReport' => true
        											),
        									),
        							),
        							'login' => array(
        									'type'    => 'Literal',
        									'options' => array(
        											'route'    => '/login',
        											'defaults' => array(
        													'__NAMESPACE__' => 'Application\Controller',
        													'controller'    => 'Public',
        													'action'        => 'login',
        													'publicReport' => true
        											),
        									),
        							),
        							'logout' => array(
        									'type'    => 'Literal',
        									'options' => array(
        											'route'    => '/logout',
        											'defaults' => array(
        													'__NAMESPACE__' => 'Application\Controller',
        													'controller'    => 'Public',
        													'action'        => 'logout',
        													'publicReport' => true
        											),
        									),
        							),
        					),
		        			
		        	),
	        	),
        	),
        	// Settings
        	'settings' => array(
        			'type'    => 'Literal',
        			'options' => array(
        					'route'    => '/settings',
        					'defaults' => array(
        							'__NAMESPACE__' => 'Application\Controller',
        							'controller'    => 'Settings',
        							'action'        => 'index',
        					),
        			),
        			'may_terminate' => true,
        	),
        	// Subscribers
        	'subscribers' => array(
	        	'type'    => 'Literal',
	        	'options' => array(
	        			'route'    => '/subscribers',
	        			'defaults' => array(
	        					'__NAMESPACE__' => 'Application\Controller',
	        					'controller'    => 'Subscribers',
	        					'action'        => 'index',
	        			),
	        	),
	        	'child_routes' => array(
		        	'show' => array(
		        			'type'    => 'Segment',
		        			'options' => array(
		        					'route'    => '/show/:email',
		        					'defaults' => array(
		        							'__NAMESPACE__' => 'Application\Controller',
		        							'controller'    => 'Subscribers',
		        							'action'        => 'show',
		        					),
		        					'constraints' => array(
		        							//'id' => '[0-9]*',
		        							//'campaign_id' => '[0-9]*',
		        					),
		        			),
		        	),
		        )
        	),
        	// Users
        	'users' => array(
        			'type'    => 'Literal',
        			'options' => array(
        					'route'    => '/users',
        					'defaults' => array(
        							'__NAMESPACE__' => 'Application\Controller',
        							'controller'    => 'Users',
        							'action'        => 'index',
        					),
        			),
        			'may_terminate' => true,
        			'child_routes' => array(
        					'add' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/add',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Users',
        											'action'        => 'add',
        									),
        							),
        					),
        					'edit' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/edit/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Users',
        											'action'        => 'edit',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'delete' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/delete/:id',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Users',
        											'action'        => 'delete',
        									),
        									'constraints' => array(
        											'id' => '[0-9]*',
        									),
        							),
        					),
        					'add-role' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/add-role',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Users',
        											'action'        => 'addRole',
        									),
        							),
        					),
        					'edit-role' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/edit-role',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Users',
        											'action'        => 'editRole',
        									),
        							),
        					),
        					'delete-role' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/delete-role',
        									'defaults' => array(
        											'__NAMESPACE__' => 'Application\Controller',
        											'controller'    => 'Users',
        											'action'        => 'deleteRole',
        									),
        							),
        					),
        			),
        	),
        	// Mandrill webhooks
        	'hooks' => array(
        			'type'    => 'Literal',
        			'options' => array(
        					'route'    => '/hooks',
        					'defaults' => array(
        							'__NAMESPACE__' => 'Application\Controller',
        							'controller'    => 'Hooks',
        							'action'        => 'index',
        					),
        			),
        			'may_terminate' => true,
        			'child_routes' => array(
	        			'set' => array(
	        					'type'    => 'Segment',
	        					'options' => array(
	        							'route'    => '/set',
	        							'defaults' => array(
	        									'__NAMESPACE__' => 'Application\Controller',
	        									'controller'    => 'Hooks',
	        									'action'        => 'set',
	        							),
	        					),
	        			),
	        			'open-message' => array(
	        					'type'    => 'Segment',
	        					'options' => array(
	        							'route'    => '/open-message/:id',
	        							'defaults' => array(
	        									'__NAMESPACE__' => 'Application\Controller',
	        									'controller'    => 'Hooks',
	        									'action'        => 'openMessage',
	        							),
	        					),
	        			),
        			),
        	),
        	// Cron
        	'cron' => array(
        			'type'    => 'Literal',
        			'options' => array(
        					'route'    => '/cron',
        					'defaults' => array(
        							'__NAMESPACE__' => 'Application\Controller',
        							'controller'    => 'Cron',
        							'action'        => 'index',
        					),
        			),
        			'may_terminate' => true,
        	),
        	// REST api
        	'api' => array(
        			'type'    => 'Literal',
        			'options' => array(
        					'route'    => '/api',
        					'defaults' => array(
        							'__NAMESPACE__' => 'Application\Controller',
        							'controller'    => 'Api',
        					),
        			),
        			'may_terminate' => true,
        			'child_routes' => array(
        					'subscriber' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/subscriber/:list_id/:id/:key',
        									'constraints' => array(
        									),
        									'defaults' => array(
        											//'action'        => 'credit',
        											'controller'    => 'Api',
        									),
        							),
        					),
        			),
        	),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => array(
        		'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            //'Application\Controller\Brands' => 'Application\Controller\BrandsController',
            'Application\Controller\Lists' => 'Application\Controller\ListsController',
            'Application\Controller\Subscribers' => 'Application\Controller\SubscribersController',
            //'Application\Controller\Campaigns' => 'Application\Controller\CampaignsController',
            'Application\Controller\Templates' => 'Application\Controller\TemplatesController',
            'Application\Controller\Settings' => 'Application\Controller\SettingsController',
            'Application\Controller\Hooks' => 'Application\Controller\HooksController',
            'Application\Controller\Cron' => 'Application\Controller\CronController',
            'Application\Controller\Users' => 'Application\Controller\UsersController',
            'Application\Controller\Public' => 'Application\Controller\PublicController',
            'Application\Controller\Api' => 'Application\Controller\ApiController',
        ),
        'factories'    => array(
			'Application\Controller\Brands'    => 'Application\ControllerFactory\BrandsControllerFactory',
			'Application\Controller\Campaigns'    => 'Application\ControllerFactory\CampaignsControllerFactory',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
        		'ViewJsonStrategy',
        ),
    ),
    'doctrine' => array(
    		'driver' => array(
    				'app_driver' => array(
    						'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
    						'cache' => 'array',
    						'paths' => array(__DIR__ . '/../src/Application/Entity')
    				),
    				'orm_default' => array(
    						'drivers' => array(
    								'Application\Entity' => 'app_driver',
    						),
    				),
    		),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
