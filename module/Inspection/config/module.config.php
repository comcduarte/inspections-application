<?php
namespace Inspection;

use Inspection\Controller\InspectionConfigController;
use Inspection\Controller\InspectionController;
use Inspection\Controller\PurposeController;
use Inspection\Controller\ResponseController;
use Inspection\Controller\Factory\InspectionConfigControllerFactory;
use Inspection\Controller\Factory\InspectionControllerFactory;
use Inspection\Controller\Factory\ListControllerFactory;
use Inspection\Form\InspectionForm;
use Inspection\Form\Factory\InspectionFormFactory;
use Inspection\Service\Factory\InspectionModelAdapterFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'inspections' => [
                'type' => Literal::class,
                'priority' => 1,
                'options' => [
                    'route' => '/inspections',
                    'defaults' => [
                        'action' => 'index',
                        'controller' => InspectionController::class,
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'config' => [
                        'type' => Segment::class,
                        'priority' => 100,
                        'options' => [
                            'route' => '/config[/:action]',
                            'defaults' => [
                                'action' => 'index',
                                'controller' => InspectionConfigController::class,
                            ],
                        ],
                    ],
                    'default' => [
                        'type' => Segment::class,
                        'priority' => -100,
                        'options' => [
                            'route' => '/[:action[/:uuid]]',
                            'defaults' => [
                                'action' => 'index',
                                'controller' => InspectionController::class,
                            ],
                        ],
                    ],
                    'lists' => [
                        'type' => Segment::class,
                        'priority' => 100,
                        'options' => [
                            'route' => '/lists/[:controller[/:action[/:uuid]]]',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'acl' => [
        'EVERYONE' => [
            'inspections/default' => [],
            'inspections' => [],
        ],
        'admin' => [
            'inspections/config' => [],
        ],
    ],
    'controllers' => [
        'aliases' => [
            'inspection' => InspectionController::class,
            'purpose' => PurposeController::class,
            'response' => ResponseController::class,
        ],
        'factories' => [
            InspectionController::class => InspectionControllerFactory::class,
            InspectionConfigController::class => InspectionConfigControllerFactory::class,
            PurposeController::class => ListControllerFactory::class,
            ResponseController::class => ListControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            InspectionForm::class => InspectionFormFactory::class,
        ],
    ],
    'navigation' => [
        'default' => [
            'inspections' => [
                'label' => 'Inspections',
                'route' => 'inspections/default',
                'class' => 'dropdown',
                'order' => 10,
                'resource' => 'inspections/default',
                'action' => 'menu',
                'privilege' => 'menu',
                'pages' => [
                    'add' => [
                        'label' => 'Add Inspection',
                        'route' => 'inspections/default',
                        'resource' => 'inspections/default',
                        'privilege' => 'create',
                        'action' => 'create',
                    ],
                    'list' => [
                        'label' => 'List Inspections',
                        'route' => 'inspections/default',
                        'resource' => 'inspections/default',
                        'privilege' => 'index',
                    ],
                ],
            ],
            'tblmgmt' => [
                'label' => 'Table Management',
                'route' => 'inspections/default',
                'class' => 'dropdown',
                'order' => 80,
                'resource' => 'inspections/tblmgmt',
                'action' => 'menu',
                'privilege' => 'menu',
                'pages' => [
                    'purposes' => [
                        'label' => 'Purpose Table',
                        'route' => 'inspections/default',
                        'resource' => 'inspections/tblmgmt',
                        'privilege' => 'create',
                        'action' => 'create',
                        'class' => 'dropdown-submenu',
                        'pages' => [
                            'add' => [
                                'label' => 'Add Purpose',
                                'route' => 'inspections/lists',
                                'resource' => 'inspections/tblmgmt',
                                'privilege' => 'create',
                                'action' => 'create',
                                'controller' => 'purpose',
                            ],
                            'list' => [
                                'label' => 'List Purposes',
                                'route' => 'inspections/lists',
                                'resource' => 'inspections/tblmgmt',
                                'privilege' => 'index',
                                'controller' => 'purpose',
                            ],
                        ],
                    ],
                    'responses' => [
                        'label' => 'Response Table',
                        'route' => 'inspections/default',
                        'resource' => 'inspections/tblmgmt',
                        'privilege' => 'create',
                        'action' => 'create',
                        'class' => 'dropdown-submenu',
                        'pages' => [
                            'add' => [
                                'label' => 'Add Response',
                                'route' => 'inspections/lists',
                                'resource' => 'inspections/tblmgmt',
                                'privilege' => 'create',
                                'action' => 'create',
                                'controller' => 'response',
                            ],
                            'list' => [
                                'label' => 'List Response',
                                'route' => 'inspections/lists',
                                'resource' => 'inspections/tblmgmt',
                                'privilege' => 'index',
                                'controller' => 'response',
                            ],
                        ],
                    ],
                ],
            ],
            'settings' => [
                'pages' => [
                    'inspections' => [
                        'label' => 'Inspections Settings',
                        'route' => 'inspections/config',
                        'action' => 'index',
                        'resource' => 'inspections/config',
                        'privilege' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'aliases' => [
            'inspection-model-adapter-config' => 'model-adapter-config',
            'list-model-adapter' => 'inspection-model-adapter',
        ],
        'factories' => [
            'inspection-model-adapter' => InspectionModelAdapterFactory::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'inspections/update' => __DIR__ . '/../view/inspection/update.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];