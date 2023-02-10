<?php

declare(strict_types=1);

namespace Application;

use Application\ActionMenu\View\Helper\ActionMenu;
use Application\View\Helper\Dialog;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'files' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/files[/:action]',
                    'defaults' => [
                        'controller' => Controller\FilesController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'box' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/box[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\BoxController::class,
                        'action'     => 'view',
                    ],
                ],
            ],
        ],
    ],
    'acl' => [
        'EVERYONE' => [
            'home' => ['index'],
        ],
    ],
    'controllers' => [
        'aliases' => [
            'files' => Controller\FilesController::class,
            'box' => Controller\BoxController::class,
        ],
        'factories' => [
            Controller\BoxController::class => Controller\Factory\BoxControllerFactory::class,
            Controller\IndexController::class => InvokableFactory::class,
            Controller\FilesController::class => Controller\Factory\FilesControllerFactory::class,
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'label' => 'Home',
                'route' => 'home',
                'order' => 0,
            ],
        ],
    ],
    'service_manager' => [
        'aliases' => [
            'files-model-adapter-config' => 'model-adapter-config',
        ],
        'factories' => [
            'access-token' => Service\Factory\AccessTokenFactory::class,
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'actionmenu' => ActionMenu::class,
            'dialog' => Dialog::class,
        ],
        'factories' => [
            ActionMenu::class => InvokableFactory::class,
            Dialog::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/custom-layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'navigation'              => __DIR__ . '/../view/partials/navigation.phtml',
            'flashmessenger'          => __DIR__ . '/../view/partials/flashmessenger.phtml',
            'dialog'                  => __DIR__ . '/../view/application/base-views/dialog.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
