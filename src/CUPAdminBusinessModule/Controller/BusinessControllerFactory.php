<?php

namespace CUPAdminBusinessModule\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sharedServiceLocator = $serviceLocator->getServiceLocator();
        $businessService = $sharedServiceLocator->get('BusinessCore\Service\BusinessService');
        $datatableService = $sharedServiceLocator->get('BusinessCore\Service\DatatableService');
        $businessTimePackageService = $sharedServiceLocator->get('BusinessCore\Service\BusinessTimePackageService');
        $businessDetailsForm = $sharedServiceLocator->get('CUPAdminBusinessModule\Form\BusinessDetailsForm');
        $businessConfigParamsForm = $sharedServiceLocator->get('CUPAdminBusinessModule\Form\BusinessConfigParamsForm');
        $businessFareForm = $sharedServiceLocator->get('CUPAdminBusinessModule\Form\BusinessFareForm');
        $fleetService = $sharedServiceLocator->get('BusinessCore\Service\BusinessFleetService');
        $translator = $sharedServiceLocator->get('translator');

        return new BusinessController(
            $translator,
            $datatableService,
            $businessService,
            $businessTimePackageService,
            $businessDetailsForm,
            $businessConfigParamsForm,
            $businessFareForm,
            $fleetService
        );
    }
}
