<?php

namespace CUPAdminBusinessModule\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class BusinessTripControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sharedServiceLocator = $serviceLocator->getServiceLocator();

        $businessAndPrivateTripService = $sharedServiceLocator->get('CUPAdminBusinessModule\Service\BusinessAndPrivateTripService');
        $datatableService = $sharedServiceLocator->get('BusinessCore\Service\DatatableService');

        $urlHelper = $sharedServiceLocator->get('viewhelpermanager')->get('url');

        $datatablesSessionNamespace = $sharedServiceLocator->get('Configuration')['session']['datatablesNamespace'];
        // Creating DataTable Filters Session Container
        $datatableFiltersSessionContainer = new Container($datatablesSessionNamespace);


        return new BusinessTripController(
            $businessAndPrivateTripService,
            $datatableService,
            $urlHelper,
            $datatableFiltersSessionContainer
        );
    }
}
