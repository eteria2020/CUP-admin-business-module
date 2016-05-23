<?php

namespace CUPAdminBusinessModule\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $businessService = $serviceLocator->getServiceLocator()->get('BusinessCore\Service\BusinessService');
        $datatableService = $serviceLocator->getServiceLocator()->get('BusinessCore\Service\DatatableService');
        $businessTimePackageService = $serviceLocator->getServiceLocator()->get('BusinessCore\Service\BusinessTimePackageService');

        $translator = $serviceLocator->getServiceLocator()->get('translator');

        $businessDetailsForm = $serviceLocator->getServiceLocator()->get('CUPAdminBusinessModule\Form\BusinessDetailsForm');
        $businessConfigParamsForm = $serviceLocator->getServiceLocator()->get('CUPAdminBusinessModule\Form\BusinessConfigParamsForm');

        return new BusinessController($translator, $datatableService, $businessService, $businessTimePackageService, $businessDetailsForm, $businessConfigParamsForm);
    }
}
