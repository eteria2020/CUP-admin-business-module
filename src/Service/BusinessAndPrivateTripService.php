<?php

namespace CUPAdminBusinessModule\Service;

use BusinessCore\Service\Helper\SearchCriteria;
use CUPAdminBusinessModule\Service\Queries\BusinessAndPrivateTripQueries;
use SharengoCore\Entity\Trips;

class BusinessAndPrivateTripService {

    /**
     * @var BusinessAndPrivateTripQueries
     */
    private $businessAndPrivateTripQueries;

    public function __construct(
    BusinessAndPrivateTripQueries $businessAndPrivateTripQueries
    ) {
        $this->businessAndPrivateTripQueries = $businessAndPrivateTripQueries;
    }

    public function searchTrips(SearchCriteria $searchCriteria) {
        return $this->businessAndPrivateTripQueries->searchTrips($searchCriteria);
    }

    public function searchBusinessTripPaymentByTrip(Trips $trips) {
        return $this->businessAndPrivateTripQueries->searchBusinessTripPaymentByTrip($trips);
    }

    public function getTotalTrips() {
        return $this->businessAndPrivateTripQueries->countAll();
    }

    public function countFilteredTripWithoutPagination($searchCriteria) {
        return $this->businessAndPrivateTripQueries->searchTrips($searchCriteria, true);
    }

}
