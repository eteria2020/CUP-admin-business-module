<?php

namespace CUPAdminBusinessModule\Controller;

use BusinessCore\Service\DatatableService;
use CUPAdminBusinessModule\Service\BusinessAndPrivateTripService;
use SharengoCore\Entity\TripPayments;
use SharengoCore\Entity\Trips;
use SharengoCore\Service\EventsService;
use SharengoCore\Service\TripCostComputerService;
use SharengoCore\Service\TripsService;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class BusinessTripController extends AbstractActionController
{
    /**
     * @var DatatableService
     */
    private $datatableService;
    /**
     * @var BusinessAndPrivateTripService
     */
    private $businessAndPrivateTripService;

    /**
     * BusinessTripController constructor.
     * @param DatatableService $datatableService
     * @param BusinessAndPrivateTripService $businessAndPrivateTripService
     */
    public function __construct(
        BusinessAndPrivateTripService $businessAndPrivateTripService,
        DatatableService $datatableService
    ) {
        $this->businessAndPrivateTripService = $businessAndPrivateTripService;
        $this->datatableService = $datatableService;
    }

    public function datatableAction()
    {
        $filters = $this->params()->fromPost();
        $searchCriteria = $this->datatableService->getSearchCriteria($filters);
        $trips = $this->businessAndPrivateTripService->searchTrips($searchCriteria);
        $tripsWithoutPagination = $this->businessAndPrivateTripService->countFilteredTripWithoutPagination($searchCriteria);
        $dataDataTable = $this->mapBusinessTripsToDatatable($trips);
        $totalTrips = $this->businessAndPrivateTripService->getTotalTrips();

        return new JsonModel([
            'draw'            => $this->params()->fromQuery('sEcho', 0),
            'recordsTotal'    => $totalTrips,
            'recordsFiltered' => $tripsWithoutPagination,
            'data'            => $dataDataTable
        ]);
    }

    private function mapBusinessTripsToDatatable(array $trips)
    {
        return array_map(function (Trips $trip) {
            $translator = $this->TranslatorPlugin();

            $tripCost = $this->calculateTripCost($trip);
            return [
                'e' => [
                    'id' => $trip->getId() ,
                    'kmBeginning' => $trip->getKmBeginning(),
                    'kmEnd' => $trip->getKmEnd(),
                    'timestampBeginning' => $trip->getTimestampBeginning()->format('d-m-Y H:i:s'),
                    'timestampEnd' => (is_null($trip->getTimestampEnd()) ? '' : $trip->getTimestampEnd()->format('d-m-Y H:i:s')),
                    'parkSeconds' => $trip->getParkSeconds() . ' sec',
                    'payable' => $trip->getPayable() ? $translator->translate('Si') : $translator->translate('No'),
                    'totalCost' => ['amount' => $tripCost, 'id' => $trip->getId()],
                    'idLink' => $trip->getId()
                ],
                'cu' => [
                    'id' => $trip->getCustomer()->getId(),
                    'email' => $trip->getCustomer()->getEmail(),
                    'surname' => $trip->getCustomer()->getSurname(),
                    'name' => $trip->getCustomer()->getName(),
                    'mobile' => $trip->getCustomer()->getMobile()
                ],
                'c' => [
                    'plate' => $trip->getCar()->getPlate(),
                    'label' => $trip->getCar()->getLabel(),
                    'parking' => $trip->getCar()->getParking() ? $translator->translate('Si') : $translator->translate('No'),
                    'keyStatus' => $trip->getCar()->getKeystatus()
                ],
                'cc' => [
                    'code' => is_object($trip->getCustomer()->getCard()) ? $trip->getCustomer()->getCard()->getCode() : ''

                ],
                'f' => [
                    'name' => $trip->getFleetName(),
                ],
                'duration' => $this->getDuration($trip->getTimestampBeginning(), $trip->getTimestampEnd()),
                'payed' => $trip->getPayable() ? ($trip->isPaymentCompleted() ? $translator->translate('Si') : $translator->translate('No')) : '-'
            ];
        }, $trips);
    }


    private function calculateTripCost(Trips $trip)
    {
        if ($trip->isEnded()) {
            if ($trip->getPayable() && $trip->isAccountable()) {
                $tripPayment = $trip->getTripPayment();
                if ($tripPayment instanceof TripPayments) {
                    return $tripPayment->getTotalCost();
                } else {
                    return 0;
                }
            } else {
                return 'FREE';
            }
        }
        return '';
    }

    public function getDuration($from, $to)
    {
        $translator = $this->TranslatorPlugin();
        if (empty($from) || empty($to)) {
            return $translator->translate('n.d.');
        }
        return $from->diff($to)->format('%dg %H:%I:%S');
    }
}
