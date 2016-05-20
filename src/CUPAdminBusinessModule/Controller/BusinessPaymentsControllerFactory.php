<?php

namespace CUPAdminBusinessModule\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessPaymentsControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $businessService = $serviceLocator->getServiceLocator()->get('BusinessCore\Service\BusinessService');
        $businessPaymentsService = $serviceLocator->getServiceLocator()->get('BusinessCore\Service\BusinessPaymentService');
        $datatableService = $serviceLocator->getServiceLocator()->get('BusinessCore\Service\DatatableService');
        return new BusinessPaymentsController($businessService, $datatableService, $businessPaymentsService);
    }
}
