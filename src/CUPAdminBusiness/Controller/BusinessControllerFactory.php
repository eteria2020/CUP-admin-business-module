<?php

namespace CUPAdminBusiness\Controller;

use BusinessCore\Form\InputData\BusinessDataFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $businessService = $serviceLocator->getServiceLocator()->get('BusinessCore\Service\BusinessService');

        $translator = $serviceLocator->getServiceLocator()->get('translator');

        $businessForm = $serviceLocator->getServiceLocator()->get('CUPAdminBusiness\Form\BusinessForm');

        return new BusinessController($translator, $businessService, $businessForm);
    }
}
