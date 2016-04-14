<?php

namespace CUPAdminBusiness;

$translator = new \Zend\I18n\Translator\Translator;
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
                    'remove-employee' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/remove-employee/:code/:id',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'remove-employee',
                            ],
                        ],
                    ],
                    'block-employee' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/block-employee/:code/:id',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'block-employee',
                            ],
                        ],
                    ],
                    'unblock-employee' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/unblock-employee/:code/:id',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'unblock-employee',
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
                    'ajax-tab-employees' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/ajax-tab/employees/:code',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'employees-tab',
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
            'CUPAdminBusiness\Form\BusinessForm' => 'CUPAdminBusiness\Form\BusinessFormFactory',
         ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                ['controller' => 'CUPAdminBusiness\Controller\Business', 'roles' => ['admin']],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label'     => $translator->translate('Aziende'),
                'route'     => 'business',
                'icon'      => 'fa fa-briefcase',
                'resource'  => 'admin',
                'isRouteJs' => true,
                'pages'     => [
                    [
                        'label' => $translator->translate('Elenco'),
                        'route' => 'business',
                        'isVisible' => true
                    ]
                ],
            ],
        ]
    ]
];
