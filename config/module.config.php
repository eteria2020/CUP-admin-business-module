<?php

namespace CUPAdminBusiness;

return [
    'router' => [
        'routes' => [
            'business' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/business',
                    'defaults' => [
                        '__NAMESPACE__' => 'CUPAdminBusiness\Controller',
                        'controller' => 'Business',
                        'action' => 'index',
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'add' => [
                        'type' => 'Literal',
                        'options' => [
                            'route'    => '/add',
                            'defaults' => [
                                'action' => 'add',
                            ],
                        ],
                    ],
                    'edit' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/edit/:code',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'edit',
                            ],
                        ],
                    ],
                    'ajax-tab-edit' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/ajax-tab/edit/:code',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'edit-tab',
                            ],
                        ],
                    ],
                    'ajax-tab-info' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/ajax-tab/info/:code',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'info-tab',
                            ],
                        ],
                    ],
                    'ajax-tab-params' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/ajax-tab/params/:code',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'params-tab',
                            ],
                        ],
                    ],
                    'datatable' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/datatable',
                            'defaults' => [
                                '__NAMESPACE__' => 'CUPAdminBusiness\Controller',
                                'controller'    => 'Business',
                                'action'        => 'datatable',
                            ],
                        ],
                    ],
                ],
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            'CUPAdminBusiness\Controller\Business' => 'CUPAdminBusiness\Controller\BusinessControllerFactory'
        ]
    ],
    'service_manager' => [
        'factories' => [
            'CUPAdminBusiness\Service\BusinessService' => 'CUPAdminBusiness\Service\BusinessServiceFactory',
            'BusinessForm' => 'CUPAdminBusiness\Form\BusinessFormFactory',
            'CUPAdminBusiness\Service\Datatable' => 'CUPAdminBusiness\Service\DatatableServiceFactory',
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity']
            ],
            'orm_default'             => [
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                ['controller' => 'CUPAdminBusiness\Controller\Business', 'roles' => ['admin', 'callcenter']],
            ],
        ],
    ],
];
