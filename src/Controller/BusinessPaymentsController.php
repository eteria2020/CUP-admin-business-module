<?php

namespace CUPAdminBusinessModule\Controller;

use Application\Controller\Plugin\TranslatorPlugin;
use BusinessCore\Entity\Base\BusinessPayment;
use BusinessCore\Entity\BusinessTripPayment;
use BusinessCore\Entity\ExtraPayment;
use BusinessCore\Entity\SubscriptionPayment;
use BusinessCore\Entity\TimePackagePayment;
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
        $totalPayments = $this->businessPaymentService->getTotalPaymentsByBusiness($business);
        $searchCriteria = $this->datatableService->getSearchCriteria($filters);
        $businessPaymentsNumber = $this->businessPaymentService->countFilteredPaymentsByBusiness($business, $searchCriteria);
        $businessPayments = $this->businessPaymentService->searchPaymentsByBusiness($business, $searchCriteria);
        $dataDataTable = $this->mapBusinessPaymentsToDatatable($businessPayments);

        return new JsonModel([
            'draw'            => $this->params()->fromQuery('sEcho', 0),
            'recordsTotal'    => $totalPayments,
            'recordsFiltered' => $businessPaymentsNumber,
            'data'            => $dataDataTable
        ]);
    }

    public function confirmPaymentAction()
    {
        $business = $this->getBusiness();
        $type = $this->params()->fromRoute('type', 0);
        $id = $this->params()->fromRoute('id', 0);

        $className = null;
        switch ($type) {
            case BusinessPayment::TYPE_TRIP:
                $className = BusinessTripPayment::CLASS_NAME;
                break;
            case BusinessPayment::TYPE_PACKAGE:
                $className = TimePackagePayment::CLASS_NAME;
                break;
            case BusinessPayment::TYPE_EXTRA:
                $className = ExtraPayment::CLASS_NAME;
                break;
            case BusinessPayment::TYPE_SUBSCRIPTION:
                $className = SubscriptionPayment::CLASS_NAME;
                break;
            default:
                throw new \Exception;
        }

        $this->businessPaymentService->flagPaymentAsConfirmedPayedByWire($className, $id);
        return $this->redirect()->toRoute(
            'business/edit',
            ['code' => $business->getCode()],
            ['query' => ['tab' => 'payments']]
        );
    }

    private function mapBusinessPaymentsToDatatable(array $businessPayments)
    {
        return array_map(function ($businessPayment) {

            $payedOn = empty($businessPayment['payed_on_ts']) ? '-' : date_create($businessPayment['payed_on_ts'])->format('d-m-Y H:i:s');
            $flaggedAsPayedOn = empty($businessPayment['expected_payed_ts']) ? '-' : date_create($businessPayment['expected_payed_ts'])->format('d-m-Y H:i:s');
            return [
                'created_ts' => date_create($businessPayment['created_ts'])->format('d-m-Y H:i:s'),
                'type' => $this->formatPaymentType($businessPayment['type']),
                'amount' => $this->formatAmount($businessPayment['amount'], $businessPayment['currency']),
                'payed_on_ts' => $payedOn,
                'expected_payed_ts' => $flaggedAsPayedOn,
                'status' => $this->formatStatus($businessPayment['status']),
                'details' => $this->formatAdditionalDetails($businessPayment)
            ];
        }, $businessPayments);
    }


    private function formatPaymentType($paymentType)
    {
        switch ($paymentType) {
            case BusinessPayment::TYPE_PACKAGE:
                return $this->translatorPlugin()->translate("Pacchetto minuti");
            case BusinessPayment::TYPE_EXTRA:
                return $this->translatorPlugin()->translate("Extra / Penale");
            case BusinessPayment::TYPE_TRIP:
                return $this->translatorPlugin()->translate("Corsa");
            case BusinessPayment::TYPE_SUBSCRIPTION:
                return $this->translatorPlugin()->translate("Sottoscrizione");
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
                break;
        }
        return sprintf("%s %s", number_format($amount / 100, 2, '.', ''), $currencySymbol);
    }

    private function formatStatus($status)
    {
        switch ($status) {
            case BusinessPayment::STATUS_CONFIRMED_PAYED:
                return $this->translatorPlugin()->translate("Pagato");
            case BusinessPayment::STATUS_EXPECTED_PAYED:
                return $this->translatorPlugin()->translate("Pagato, in attesa di conferma");
            case BusinessPayment::STATUS_INVOICED:
                return $this->translatorPlugin()->translate("Pagato e fatturato");
            case BusinessPayment::STATUS_PENDING:
                return $this->translatorPlugin()->translate("Non pagato");
        }
        return $status;
    }

    private function formatAdditionalDetails($businessPayment)
    {
        $business = $this->getBusiness();
        $status = $businessPayment['status'];
        if ($status == BusinessPayment::STATUS_EXPECTED_PAYED) {
            $type = $businessPayment['type'];
            $paymentId = $businessPayment['payment_id'];
            $url = $this->url()->fromRoute(
                'business/edit/payments/confirm',
                [
                    'code' => $business->getCode(),
                    'type' => $type,
                    'id' => $paymentId
                ]
            );
            $text = $this->translatorPlugin()->translate("Conferma come pagata");
            return sprintf("<a href=%s>%s</a>", $url, $text);
        }

        return '-';
    }
}
