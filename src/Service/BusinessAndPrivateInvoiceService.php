<?php

namespace CUPAdminBusinessModule\Service;

use BusinessCore\Entity\Repository\BusinessInvoiceRepository;
use BusinessCore\Service\Helper\SearchCriteria;
use CUPAdminBusinessModule\Service\Queries\BusinessAndPrivateInvoiceQueries;
use SharengoCore\Entity\Repository\InvoicesRepository as PrivateInvoiceRepository;
use SharengoCore\Entity\Repository\TripsRepository;

class BusinessAndPrivateInvoiceService
{
    /**
     * @var BusinessAndPrivateInvoiceQueries
     */
    private $businessAndPrivateInvoiceQueries;
    /**
     * @var BusinessInvoiceRepository
     */
    private $businessInvoiceRepository;
    /**
     * @var PrivateInvoiceRepository
     */
    private $privateInvoiceRepository;

    public function __construct(
        BusinessAndPrivateInvoiceQueries $businessAndPrivateInvoiceQueries,
        BusinessInvoiceRepository $businessInvoiceRepository,
        PrivateInvoiceRepository $privateInvoiceRepository
    ) {
        $this->businessAndPrivateInvoiceQueries = $businessAndPrivateInvoiceQueries;
        $this->businessInvoiceRepository = $businessInvoiceRepository;
        $this->privateInvoiceRepository = $privateInvoiceRepository;
    }

    public function searchInvoices(SearchCriteria $searchCriteria)
    {
        return $this->businessAndPrivateInvoiceQueries->searchInvoices($searchCriteria);
    }

    public function getTotalInvoices()
    {
        return $this->businessAndPrivateInvoiceQueries->countAll();
    }

    public function countFilteredInvoiceWithoutPagination($searchCriteria)
    {
        return $this->businessAndPrivateInvoiceQueries->searchInvoices($searchCriteria, true);
    }

    public function findBusinessInvoiceById($id)
    {
        return $this->businessInvoiceRepository->findOneBy(['id' => $id]);
    }

    public function findPrivateInvoiceById($id)
    {
        return $this->privateInvoiceRepository->findOneBy(['id' => $id]);
    }
}
