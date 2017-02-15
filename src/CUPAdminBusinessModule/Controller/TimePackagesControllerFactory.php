<?php

namespace CUPAdminBusinessModule\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TimePackagesControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sharedServiceManager = $serviceLocator->getServiceLocator();
        $businessTimePackageService = $sharedServiceManager->get('BusinessCore\Service\BusinessTimePackageService');
        $timePackageForm = $sharedServiceManager->get('CUPAdminBusinessModule\Form\TimePackageForm');

        $translator = $sharedServiceManager->get('Translator');

        return new TimePackagesController($businessTimePackageService, $timePackageForm, $translator);
    }
}
