<?php

namespace CUPAdminBusinessModule\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\Repository\BusinessTripRepository;
use BusinessCore\Service\Helper\SearchCriteria;

use CUPAdminBusinessModule\Service\Repository\BusinessAndPrivateTripRepository;
use Doctrine\ORM\EntityManager;
use SharengoCore\Entity\Repository\TripsRepository;

class BusinessAndPrivateTripService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var BusinessTripRepository
     */
    private $businessTripRepository;
    /**
     * @var TripsRepository
     */
    private $privateTripRepository;
    /**
     * @var BusinessAndPrivateTripRepository
     */
    private $businessAndPrivateTripRepository;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessTripRepository $businessTripRepository
     * @param TripsRepository $privateTripRepository
     * @param BusinessAndPrivateTripRepository $businessAndPrivateTripRepository
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessTripRepository $businessTripRepository,
        TripsRepository $privateTripRepository,
        BusinessAndPrivateTripRepository $businessAndPrivateTripRepository
    ) {
        $this->entityManager = $entityManager;
        $this->businessTripRepository = $businessTripRepository;
        $this->privateTripRepository = $privateTripRepository;
        $this->businessAndPrivateTripRepository = $businessAndPrivateTripRepository;
    }

    public function searchTrips(SearchCriteria $searchCriteria)
    {
        return $this->businessAndPrivateTripRepository->searchTrips($searchCriteria);
    }

    public function getTotalTrips()
    {
        return $this->businessAndPrivateTripRepository->countAll();
    }

    public function countFilteredTripWithoutPagination($searchCriteria)
    {
        return $this->businessAndPrivateTripRepository->searchTrips($searchCriteria, true);
    }
}
