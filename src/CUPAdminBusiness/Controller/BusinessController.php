<?php

namespace CUPAdminBusiness\Controller;

use CUPAdminBusiness\Service\BusinessService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BusinessController extends AbstractActionController
{
    /**
     * @var BusinessService
     */
    private $businessService;

    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;
    }

    public function listAction()
    {
        return new ViewModel([
            'totalCustomers' => $this->businessService->getBusinesses()
        ]);
    }
}
