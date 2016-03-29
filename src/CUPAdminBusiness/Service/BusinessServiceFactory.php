<?php

namespace CUPAdminBusiness\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $url = $serviceLocator->get('Configuration')['website']['uri'];

        $languageService = $serviceLocator->get('LanguageService');
        $translator = $languageService->getTranslator();

        return new BusinessService(
            $translator,
            $url
        );
    }
}
