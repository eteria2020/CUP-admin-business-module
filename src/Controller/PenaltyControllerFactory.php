<?php

namespace CUPAdminBusinessModule\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class PenaltyControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return PenaltyController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sharedServiceManager = $serviceLocator->getServiceLocator();
        $chargePenaltyOrExtraForm = $sharedServiceManager->get('CUPAdminBusinessModule\Form\ChargePenaltyOrExtraForm');
        $businessService = $sharedServiceManager->get('BusinessCore\Service\BusinessService');
        $businessFleetService = $sharedServiceManager->get('BusinessCore\Service\BusinessFleetService');
        $businessPaymentService = $sharedServiceManager->get('BusinessCore\Service\BusinessPaymentService');
        $translator = $sharedServiceManager->get('translator');

        return new PenaltyController($chargePenaltyOrExtraForm, $businessPaymentService, $businessService, $businessFleetService, $translator);
    }
}
