<?php

namespace CUPAdminBusiness\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $businessService = $serviceLocator->getServiceLocator()->get('BusinessCore\Service\BusinessService');
        $datatableService = $serviceLocator->getServiceLocator()->get('BusinessCore\Service\DatatableService');

        $translator = $serviceLocator->getServiceLocator()->get('translator');

        $businessDetailsForm = $serviceLocator->getServiceLocator()->get('CUPAdminBusiness\Form\BusinessDetailsForm');
        $businessConfigParamsForm = $serviceLocator->getServiceLocator()->get('CUPAdminBusiness\Form\BusinessConfigParamsForm');

        return new BusinessController($translator, $datatableService, $businessService, $businessDetailsForm, $businessConfigParamsForm);
    }
}
