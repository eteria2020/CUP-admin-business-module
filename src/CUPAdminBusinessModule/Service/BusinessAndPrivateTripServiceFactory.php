<?php

namespace CUPAdminBusinessModule\Service;

use CUPAdminBusinessModule\Service\Repository\BusinessAndPrivateTripRepository;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessAndPrivateTripServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $businessTripRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessTrip');
        $tripRepository = $entityManager->getRepository('SharengoCore\Entity\Trips');

        $businessAndPrivateRepository = new BusinessAndPrivateTripRepository($entityManager);

        return new BusinessAndPrivateTripService(
            $entityManager,
            $businessTripRepository,
            $tripRepository,
            $businessAndPrivateRepository
        );
    }
}
