<?php

namespace CUPAdminBusiness\Controller;

use CUPAdminBusiness\Form\InputData\BusinessDataFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $businessService = $serviceLocator->getServiceLocator()->get('CUPAdminBusiness\Service\BusinessService');

        $languageService = $serviceLocator->getServiceLocator()->get('LanguageService');
        $translator = $languageService->getTranslator();

        $addBusinessDataFactory = new BusinessDataFactory($businessService);
        $businessForm = $serviceLocator->getServiceLocator()->get('businessForm');

        return new BusinessController($translator, $businessService, $businessForm, $addBusinessDataFactory);
    }
}
