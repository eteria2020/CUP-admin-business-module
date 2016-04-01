<?php

namespace CUPAdminBusiness\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class BusinessFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return BusinessForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new BusinessForm();
    }
}