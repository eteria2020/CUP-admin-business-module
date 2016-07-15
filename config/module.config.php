<?php

namespace CUPAdminBusinessModule;

$translator = new \Zend\I18n\Translator\Translator;
return [
    'router' => [
        'routes' => [
            'business' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/business',
                    'defaults' => [
                        '__NAMESPACE__' => 'CUPAdminBusinessModule\Controller',
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
                    'time-packages' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/time-packages',
                            'defaults' => [
                                '__NAMESPACE__' => 'CUPAdminBusinessModule\Controller',
                                'controller' => 'TimePackages',
                                'action' => 'index',
                            ],
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
                        ],
                    ],
                    'penalty' => [
                        'type' => 'Literal',
                        'options' => [
                            'route'    => '/penality',
                            'defaults' => [
                                'controller' => 'Penalty',
                                'action' => 'charge',
                            ],
                        ],
                    ],
                    'stats' => [
                        'type' => 'Literal',
                        'options' => [
                            'route'    => '/stats',
                            'defaults' => [
                                'controller' => 'BusinessStatistics',
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'data' => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/data',
                                    'defaults' => [
                                        'action' => 'data',
                                    ],
                                ],
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
                            'do-edit-fare' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/edit-fare',
                                    'defaults' => [
                                        'action' => 'do-edit-fare',
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
                            'ajax-tab-fare' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/ajax-tab/fare',
                                    'defaults' => [
                                        'action' => 'fare-tab',
                                    ],
                                ],
                            ],
                            'ajax-tab-payments' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/ajax-tab/payments',
                                    'defaults' => [
                                        'controller' => 'BusinessPayments',
                                        'action' => 'payments-tab',
                                    ],
                                ],
                            ],
                            'ajax-tab-time-packages' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/ajax-tab/packages',
                                    'defaults' => [
                                        'action' => 'time-packages-tab',
                                    ],
                                ],
                            ],
                            'set-packages-as-buyable' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/buyable-packages',
                                    'defaults' => [
                                        'action' => 'set-packages-as-buyable',
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

                            'payments' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/payments',
                                    'defaults' => [
                                        'controller' => 'BusinessPayments',
                                    ],
                                ],
                                'may_terminate' => false,
                                'child_routes' => [
                                    'confirm' => [
                                        'type'    => 'Segment',
                                        'options' => [
                                            'route'    => '/confirm/:type/:id',
                                            'defaults' => [
                                                'action' => 'confirmPayment',
                                            ],
                                        ],
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
                    'typeahead-json' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/typeahead-json',
                            'defaults' => [
                                'action' => 'typeahead-json',
                            ],
                        ],
                    ],
                ],
            ],
            /**
             * Overwrite Application module routes to allow specific business filtering
             */
            'trips' => [
                'child_routes' => [
                    'datatable' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/datatable',
                            'defaults' => [
                                '__NAMESPACE__' => 'CUPAdminBusinessModule\Controller',
                                'controller' => 'BusinessTrip',
                                'action' => 'datatable',
                            ],
                        ],
                    ],
                ]
            ],
            'invoices-download' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/invoice',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Invoices',
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'private' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/private/:id',
                            'defaults' => [
                                'action' => 'private-invoice-pdf',
                            ],
                        ],
                    ],
                    'business' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/business/:id',
                            'defaults' => [
                                'action' => 'business-invoice-pdf',
                            ],
                        ],
                    ],
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            'CUPAdminBusinessModule\Controller\Business' => 'CUPAdminBusinessModule\Controller\BusinessControllerFactory',
            'CUPAdminBusinessModule\Controller\TimePackages' => 'CUPAdminBusinessModule\Controller\TimePackagesControllerFactory',
            'CUPAdminBusinessModule\Controller\BusinessPayments' => 'CUPAdminBusinessModule\Controller\BusinessPaymentsControllerFactory',
            'CUPAdminBusinessModule\Controller\BusinessStatistics' => 'CUPAdminBusinessModule\Controller\BusinessStatisticsControllerFactory',
            'CUPAdminBusinessModule\Controller\BusinessTrip' => 'CUPAdminBusinessModule\Controller\BusinessTripControllerFactory',
            'CUPAdminBusinessModule\Controller\Penalty' => 'CUPAdminBusinessModule\Controller\PenaltyControllerFactory',
            //Overwrite default invoicesController to have also business invoices in the view
            'Application\Controller\Invoices' => 'CUPAdminBusinessModule\Controller\InvoiceControllerFactory',
        ]
    ],
    'service_manager' => [
        'factories' => [
            'CUPAdminBusinessModule\Form\BusinessConfigParamsForm' => 'CUPAdminBusinessModule\Form\BusinessConfigParamsFormFactory',
            'CUPAdminBusinessModule\Form\ChargePenaltyOrExtraForm' => 'CUPAdminBusinessModule\Form\ChargePenaltyOrExtraFormFactory',
            'CUPAdminBusinessModule\Service\BusinessAndPrivateTripService' => 'CUPAdminBusinessModule\Service\BusinessAndPrivateTripServiceFactory',
            'CUPAdminBusinessModule\Service\BusinessAndPrivateInvoiceService' => 'CUPAdminBusinessModule\Service\BusinessAndPrivateInvoiceServiceFactory',
         ],
        'invokables' => [
            'CUPAdminBusinessModule\Form\BusinessDetailsForm' => 'CUPAdminBusinessModule\Form\BusinessDetailsForm',
            'CUPAdminBusinessModule\Form\TimePackageForm' => 'CUPAdminBusinessModule\Form\TimePackageForm',
            'CUPAdminBusinessModule\Form\BusinessFareForm' => 'CUPAdminBusinessModule\Form\BusinessFareForm',
        ]
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'collections' => [
                'js/trips.js' => [
                    'js/libs/jquery.autocomplete.min.js',
                    'js/business-trips.js',
                ],
                'css/trips.css' => [
                    'css/autocomplete.css',
                ],
            ],
            'paths' => [
                __DIR__.'/../public/assets-modules/cup-admin-business-module'
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
            'businessFormElement' => 'CUPAdminBusinessModule\View\Helper\BusinessFormElementHelper',
        ]
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                ['controller' => 'CUPAdminBusinessModule\Controller\Business', 'roles' => ['admin']],
                ['controller' => 'CUPAdminBusinessModule\Controller\TimePackages', 'roles' => ['admin']],
                ['controller' => 'CUPAdminBusinessModule\Controller\BusinessPayments', 'roles' => ['admin']],
                ['controller' => 'CUPAdminBusinessModule\Controller\BusinessTrip', 'roles' => ['admin']],
                ['controller' => 'CUPAdminBusinessModule\Controller\Penalty', 'roles' => ['admin']],
                ['controller' => 'CUPAdminBusinessModule\Controller\BusinessStatistics', 'roles' => ['admin']],
                ['controller' => 'CUPAdminBusinessModule\Controller\Invoice', 'roles' => ['admin']],
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
                    ],
                    [
                        'label' => $translator->translate('Statistiche'),
                        'route' => 'business/stats',
                        'isVisible' => true
                    ],
                    [
                        'label' => $translator->translate('Addebito penale/extra'),
                        'route' => 'business/penalty',
                        'isVisible' => true
                    ],
                    [
                        'label' => $translator->translate('Gestione pacchetti'),
                        'route' => 'business/time-packages',
                        'isVisible' => true
                    ],
                    [
                        'route' => 'business/time-packages/add',
                        'isVisible' => false
                    ]
                ],
            ],
        ]
    ],
    'datatable-filters' => [
        'trips-index' => [
            'b.name' => $translator->translate("Azienda")
        ]
    ],
];
