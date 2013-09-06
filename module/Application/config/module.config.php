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
            'Application\Controller\Brands' => 'Application\Controller\BrandsController',
            'Application\Controller\Lists' => 'Application\Controller\ListsController',
            'Application\Controller\Campaigns' => 'Application\Controller\CampaignsController',
            'Application\Controller\Settings' => 'Application\Controller\SettingsController',
            'Application\Controller\Hooks' => 'Application\Controller\HooksController',
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
