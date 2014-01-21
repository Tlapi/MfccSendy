<?php
return array(
    // All navigation-related configuration is collected in the 'navigation' key
    'navigation' => array(
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
        'default' => array(
            // And finally, here is where we define our page hierarchy
            'dashboard' => array(
                'label' => 'Dashboard',
                'route' => 'home',
            	'class' => 'dashboard'
            ),
        	'brands' => array(
        		'label' => 'Brands',
        		'route' => 'brands',
        		'class' => 'brands',
        			'pages' => array(
        					'show' => array(
        							'label' => 'Show brand',
        							'route' => 'brands/show/:id',
        					),
        			),
        	)
        ),
    ),
);