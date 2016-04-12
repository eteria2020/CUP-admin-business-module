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
                    'do-edit-data' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/edit-data/:code',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'do-edit-data',
                            ],
                        ],
                    ],
                    'do-edit-params' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/edit-params/:code',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'do-edit-params',
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
                                'action' => 'edit-data-tab',
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
                                'action' => 'edit-params-tab',
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
