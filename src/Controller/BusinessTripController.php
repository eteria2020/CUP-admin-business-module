<?php

namespace CUPAdminBusinessModule\Controller;

use BusinessCore\Service\DatatableService;
use CUPAdminBusinessModule\Service\BusinessAndPrivateTripService;
use SharengoCore\Entity\TripPayments;
use SharengoCore\Entity\Trips;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Helper\Url;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

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
     * @var Url
     */
    private $urlHelper;
    /**
     * @var Container
     */
    private $datatableFiltersSessionContainer;

    /**
     * BusinessTripController constructor.
     * @param BusinessAndPrivateTripService $businessAndPrivateTripService
     * @param DatatableService $datatableService
     * @param Url $urlHelper
     * @param Container $datatableFiltersSessionContainer
     */
    public function __construct(
        BusinessAndPrivateTripService $businessAndPrivateTripService,
        DatatableService $datatableService,
        Url $urlHelper,
        Container $datatableFiltersSessionContainer
    ) {
        $this->businessAndPrivateTripService = $businessAndPrivateTripService;
        $this->datatableService = $datatableService;
        $this->urlHelper = $urlHelper;
        $this->datatableFiltersSessionContainer = $datatableFiltersSessionContainer;
    }

    /**
     * This method return an array containing the DataTable filters,
     * from a Session Container.
     *
     * @return array
     */
    private function getDataTableSessionFilters()
    {
        return $this->datatableFiltersSessionContainer->offsetGet('Trips');
    }

    public function indexAction()
    {
        $sessionDatatableFilters = $this->getDataTableSessionFilters();

        return new ViewModel([
            'filters' => json_encode($sessionDatatableFilters),
        ]);
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

    private function getClickablePlate(Trips $trip)
    {
        $urlHelper = $this->urlHelper;
        return sprintf(
            '<a href="%s">%s</a>',
            $urlHelper(
                'cars/edit',
                ['plate' => $trip->getCar()->getPlate()]
            ),
            $trip->getCar()->getPlate()
        );
    }

    private function mapBusinessTripsToDatatable(array $trips)
    {
        return array_map(function (Trips $trip) {
            $translator = $this->TranslatorPlugin();

            $plate = $this->getClickablePlate($trip);

            $parentId = "";
            $parentStart = "";
            $parent = $trip->getParent();

            if ($parent !== null && $parent instanceof Trips && $parent->getId() != -1) {
                $parentId = "<br>(" . $parent->getId() . ")";
                $parentStart = "<br>(" . $parent->getTimestampBeginning()->format('d-m-Y H:i:s') . ")";
            }

            $tripCost = $this->calculateTripCost($trip);
            return [
                'e' => [
                    'id' => $trip->getId() . $parentId,
                    'kmBeginning' => $trip->getKmBeginning(),
                    'kmEnd' => $trip->getKmEnd(),
                    'timestampBeginning' => $trip->getTimestampBeginning()->format('d-m-Y H:i:s') . $parentStart,
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
                    'plate' => $plate,
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
