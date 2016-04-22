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
                            'route' => '/:code',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9]{6}',
                            ],
                            'defaults' => [
                                'action' => 'edit',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'do-edit-details' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/edit-details',
                                    'defaults' => [
                                        'action' => 'do-edit-details',
                                    ],
                                ],
                            ],
                            'do-edit-params' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/edit-params',
                                    'defaults' => [
                                        'action' => 'do-edit-params',
                                    ],
                                ],
                            ],
                            'ajax-tab-edit' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/ajax-tab/edit',
                                    'defaults' => [
                                        'action' => 'edit-details-tab',
                                    ],
                                ],
                            ],
                            'ajax-tab-info' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/ajax-tab/info',
                                    'defaults' => [
                                        'action' => 'info-tab',
                                    ],
                                ],
                            ],
                            'ajax-tab-employees' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/ajax-tab/employees',
                                    'defaults' => [
                                        'action' => 'employees-tab',
                                    ],
                                ],
                            ],
                            'ajax-tab-params' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/ajax-tab/params',
                                    'defaults' => [
                                        'action' => 'edit-params-tab',
                                    ],
                                ],
                            ],
                            'approve-employee' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/approve-employee/:id',
                                    'defaults' => [
                                        'action' => 'approve-employee',
                                    ],
                                ],
                            ],
                            'remove-employee' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/remove-employee/:id',
                                    'defaults' => [
                                        'action' => 'remove-employee',
                                    ],
                                ],
                            ],
                            'block-employee' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/block-employee/:id',
                                    'defaults' => [
                                        'action' => 'block-employee',
                                    ],
                                ],
                            ],
                            'unblock-employee' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/unblock-employee/:id',
                                    'defaults' => [
                                        'action' => 'unblock-employee',
                                    ],
                                ],
                            ],
                        ]
                    ],
                    'datatable' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/datatable',
                            'defaults' => [
                                'action' => 'datatable',
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
            'CUPAdminBusiness\Form\BusinessConfigParamsForm' => 'CUPAdminBusiness\Form\BusinessConfigParamsFormFactory',
         ],
        'invokables' => [
            'CUPAdminBusiness\Form\BusinessDetailsForm' => 'CUPAdminBusiness\Form\BusinessDetailsForm',
        ]
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'map' => [
                'js/business.js' => __DIR__.'/../public/assets-modules/cup-admin-business/js/business.js',
                'js/business-edit.js' => __DIR__.'/../public/assets-modules/cup-admin-business/js/business-edit.js',
                'css/business-edit.css' => __DIR__.'/../public/assets-modules/cup-admin-business/css/business-edit.css'
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'businessFormElement' => 'CUPAdminBusiness\View\Helper\BusinessFormElementHelper',
        ]
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
