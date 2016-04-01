<?php

namespace CUPAdminBusiness\Service;

use CUPAdminBusiness\Entity\Business;
use CUPAdminBusiness\Entity\Repository\BusinessRepository;
use CUPAdminBusiness\Form\InputData\BusinessData;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class BusinessService
{
    /**
     * @var string website base url
     */
    private $url;

    /**
     * @var Translator
     */
    private $translator;
    /**
     * @var DatatableService
     */
    private $datatableService;
    /**
     * @var BusinessRepository
     */
    private $businessRepository;
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessRepository $businessRepository
     * @param DatatableService $datatableService
     * @param Translator $translator
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessRepository $businessRepository,
        DatatableService $datatableService,
        Translator $translator
    ) {
        $this->translator = $translator;
        $this->businessRepository = $businessRepository;
        $this->entityManager = $entityManager;
        $this->datatableService = $datatableService;
    }

    public function getTotalBusinesses()
    {
        return count($this->businessRepository->findAll());
    }

    public function getDataDataTable(array $filters = [], $count = false)
    {
        $businesses = $this->datatableService->getData('Business', $filters, $count);

        if ($count) {
            return $businesses;
        }

        return array_map(function (Business $business) {
            return [
                'e' => [
                    'name' => $business->getName(),
                    'code' => $business->getCode(),
                    'vat' => $business->getVatNumber(),
                    'domains' => $business->getDomains(),
                    'city' => $business->getCity(),
                    'phone' => $business->getPhone(),
                    'insertedAt' => $business->getInsertedTs()->format('d-m-Y H:i:s'),
                ],
                'button' => $business->getCode()
            ];
        }, $businesses);
    }

    public function addBusiness(BusinessData $businessData)
    {
        $business = new Business(
            $businessData->getCode(),
            $businessData->getName(),
            $businessData->getDomains(),
            $businessData->getAddress(),
            $businessData->getZipCode(),
            $businessData->getProvince(),
            $businessData->getCity(),
            $businessData->getVatNumber(),
            $businessData->getEmail(),
            $businessData->getPhone(),
            $businessData->getFax(),
            $businessData->getInsertedAt()
        );


        $this->entityManager->persist($business);
        $this->entityManager->flush();
        return $business;
    }

    public function updateBusiness(Business $business, $data)
    {
        $business->setName($data['name']);
        $business->setAddress($data['address']);
        $business->setZipCode($data['zip-code']);
        $business->setProvince($data['province']);
        $business->setCity($data['city']);
        $business->setVatNumber($data['vat-number']);
        $business->setEmail($data['email']);
        $business->setPhone($data['phone']);
        $business->setFax($data['fax']);
        $business->setUpdatedTs(date_create());

        $this->entityManager->persist($business);
        $this->entityManager->flush();
        return $business;
    }

    public function getBusinessByCode($code)
    {
        return $this->businessRepository->getBusinessByCode($code);
    }

    public function getUniqueCode()
    {
        $code = substr(md5(uniqid(rand(), true)), 0, 6);
        while ($this->businessRepository->getBusinessByCode($code) != null) {
            $code = substr(md5(uniqid(rand(), true)), 0, 6);
        }
        return $code;
    }
}
