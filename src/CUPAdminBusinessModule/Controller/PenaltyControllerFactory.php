<?php

namespace CUPAdminBusinessModule\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PenaltyControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $chargePenaltyOrExtraForm = $serviceLocator->getServiceLocator()->get('CUPAdminBusinessModule\Form\ChargePenaltyOrExtraForm');
        return new PenaltyController($chargePenaltyOrExtraForm);
    }
}
