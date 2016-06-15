<?php

namespace CUPAdminBusinessModule\Controller;

use Application\Controller\Plugin\TranslatorPlugin;
use BusinessCore\Entity\BusinessPayment;
use BusinessCore\Service\BusinessPaymentService;
use BusinessCore\Service\BusinessService;
use BusinessCore\Service\DatatableService;
use Doctrine\ORM\EntityNotFoundException;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * @method TranslatorPlugin translatorPlugin()
 */
class BusinessPaymentsController extends AbstractActionController
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
     * @var BusinessPaymentService
     */
    private $businessPaymentService;

    /**
     * BusinessPaymentsController constructor.
     * @param BusinessService $businessService
     * @param DatatableService $datatableService
     * @param BusinessPaymentService $businessPaymentService
     */
    public function __construct(
        BusinessService $businessService,
        DatatableService $datatableService,
        BusinessPaymentService $businessPaymentService
    ) {
        $this->datatableService = $datatableService;
        $this->businessService = $businessService;
        $this->businessPaymentService = $businessPaymentService;
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

    public function datatableAction()
    {
        $filters = $this->params()->fromPost();
        $business = $this->getBusiness();
        $searchCriteria = $this->datatableService->getSearchCriteria($filters);
        $businessPayments = $this->businessPaymentService->searchPaymentsByBusiness($business, $searchCriteria);
        $dataDataTable = $this->mapBusinessPaymentsToDatatable($businessPayments);
        $totalPayments = $this->businessPaymentService->getTotalPaymentsByBusiness($business);

        return new JsonModel([
            'draw'            => $this->params()->fromQuery('sEcho', 0),
            'recordsTotal'    => $totalPayments,
            'recordsFiltered' => count($dataDataTable),
            'data'            => $dataDataTable
        ]);
    }

    private function mapBusinessPaymentsToDatatable(array $businessPayments)
    {
        return array_map(function (BusinessPayment $businessPayment) {
            return [
                'bp' => [
                    'createdTs' => $businessPayment->getCreatedTs()->format('d-m-Y H:i:s'),
                    'type' => $this->formatPaymentType($businessPayment->getType()),
                    'amount' => $this->formatAmount($businessPayment->getAmount(), $businessPayment->getCurrency()),
                    'payedOnTs' => $this->formatStatus($businessPayment->getPayedOnTs()),
                ]
            ];
        }, $businessPayments);
    }

    private function formatPaymentType($paymentType)
    {
        switch ($paymentType) {
            case BusinessPayment::TIME_PACKAGE_TYPE:
                return $this->translatorPlugin()->translate("Pacchetto minuti");
                break;
            case BusinessPayment::TRIP_TYPE:
                return $this->translatorPlugin()->translate("Corsa");
                break;
            case BusinessPayment::EXTRA_TYPE:
                return $this->translatorPlugin()->translate("Extra");
                break;
            case BusinessPayment::PENALTY_TYPE:
                return $this->translatorPlugin()->translate("Penale");
                break;
            default:
                return $paymentType;
        }
    }

    private function formatAmount($amount, $currency)
    {
        $currencySymbol = $currency;
        switch ($currency) {
            case 'EUR':
                $currencySymbol = "€";
        }
        return sprintf("%s %s", number_format($amount / 100, 2, '.', ''), $currencySymbol);
    }

    private function formatStatus(\DateTime $payedOnTs = null)
    {
        if (is_null($payedOnTs)) {
            return $this->translatorPlugin()->translate("Non pagato");
        } else {
            return sprintf($this->translatorPlugin()->translate("Pagato in data %s"), $payedOnTs->format('d-m-Y'));
        }
    }
}
