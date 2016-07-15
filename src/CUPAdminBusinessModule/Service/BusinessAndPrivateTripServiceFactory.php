<?php

namespace CUPAdminBusinessModule\Service;

use CUPAdminBusinessModule\Service\Queries\BusinessAndPrivateTripQueries;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessAndPrivateTripServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        $businessAndPrivateRepository = new BusinessAndPrivateTripQueries($entityManager);

        return new BusinessAndPrivateTripService(
            $businessAndPrivateRepository
        );
    }
}
