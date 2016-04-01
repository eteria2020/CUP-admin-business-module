<?php

namespace CUPAdminBusiness\Form\InputData;

use CUPAdminBusiness\Entity\Business;
use CUPAdminBusiness\Service\BusinessService;
use SharengoCore\Entity\Repository\TripsRepository;
use SharengoCore\Entity\Trips;
use SharengoCore\Exception\InvalidFormInputData;

class BusinessDataFactory
{
    private $translator;
    /**
     * @var BusinessService
     */
    private $businessService;

    /**
     * BusinessDataFactory constructor.
     * @param $translator
     * @param BusinessService $businessService
     */
    public function __construct($translator, BusinessService $businessService)
    {
        $this->translator = $translator;
        $this->businessService = $businessService;
    }

    /**
     * creates a new AddBusinessData object from an array
     *
     * @param array $data
     * @return AddBusinessData
     */
    public function fromArray(array $data)
    {
        $code = $this->businessService->getUniqueCode();
        $domains = explode(" ", $data['domains']);
        //todo validation

        return new BusinessData(
            $code,
            $data['name'],
            $domains,
            $data['address'],
            $data['zip-code'],
            $data['province'],
            $data['city'],
            $data['vat-number'],
            $data['email'],
            $data['phone'],
            $data['fax'],
            date_create(),
            $data['payment-type'],
            $data['payment-frequence'],
            $data['business-mail-control']
        );
    }
}
