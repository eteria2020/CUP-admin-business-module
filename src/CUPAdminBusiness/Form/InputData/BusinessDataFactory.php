<?php

namespace CUPAdminBusiness\Form\InputData;

use CUPAdminBusiness\Entity\Business;
use CUPAdminBusiness\Service\BusinessService;
use SharengoCore\Entity\Repository\TripsRepository;
use SharengoCore\Entity\Trips;
use SharengoCore\Exception\InvalidFormInputData;
use Zend\Form\Exception\InvalidElementException;

class BusinessDataFactory
{
    /**
     * @var BusinessService
     */
    private $businessService;

    /**
     * BusinessDataFactory constructor.
     * @param BusinessService $businessService
     */
    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;
    }

    /**
     * creates a new BusinessData object from an array
     *
     * @param array $data
     * @return AddBusinessData
     */
    public function fromArray(array $data)
    {
        $code = $this->businessService->getUniqueCode();

        return new BusinessData($code, $data);

    }
}
