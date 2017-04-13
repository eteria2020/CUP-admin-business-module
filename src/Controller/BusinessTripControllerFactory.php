<?php

namespace CUPAdminBusinessModule\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessTripControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sharedServiceLocator = $serviceLocator->getServiceLocator();

        $businessAndPrivateTripService = $sharedServiceLocator->get('CUPAdminBusinessModule\Service\BusinessAndPrivateTripService');
        $datatableService = $sharedServiceLocator->get('BusinessCore\Service\DatatableService');

        $urlHelper = $sharedServiceLocator->get('viewhelpermanager')->get('url');


        return new BusinessTripController(
            $businessAndPrivateTripService,
            $datatableService,
            $urlHelper
        );
    }
}
