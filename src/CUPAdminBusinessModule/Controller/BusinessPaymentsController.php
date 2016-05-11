<?php

namespace CUPAdminBusinessModule\Controller;

use BusinessCore\Service\BusinessService;
use BusinessCore\Service\DatatableService;
use Doctrine\ORM\EntityNotFoundException;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

class BusinessPaymentsController
{
    /**
     * @var DatatableService
     */
    private $datatableService;
    /**
     * @var BusinessService
     */
    private $businessService;

    /**
     * BusinessPaymentsController constructor.
     * @param BusinessService $businessService
     * @param DatatableService $datatableService
     */
    public function __construct(
        BusinessService $businessService,
        DatatableService $datatableService
    ) {
        $this->datatableService = $datatableService;
        $this->businessService = $businessService;
    }

    public function paymentsTabAction()
    {
        $business = $this->getBusiness();

        $view = new ViewModel([
            'business' => $business
        ]);
        $view->setTerminal(true);

        return $view;
    }

    protected function getBusiness()
    {
        $code = $this->params()->fromRoute('code', 0);
        $business = $this->businessService->getBusinessByCode($code);

        if (is_null($business)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            throw new EntityNotFoundException();
        }
        return $business;
    }
}
