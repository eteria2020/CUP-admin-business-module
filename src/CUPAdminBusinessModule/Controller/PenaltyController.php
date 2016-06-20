<?php

namespace CUPAdminBusinessModule\Controller;

use BusinessCore\Exception\InvalidFormDataException;
use BusinessCore\Service\BusinessPaymentService;
use BusinessCore\Service\BusinessService;
use CUPAdminBusinessModule\Form\ChargePenaltyOrExtraForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;
use Zend\View\Model\ViewModel;

class PenaltyController extends AbstractActionController
{
    /**
     * @var ChargePenaltyOrExtraForm
     */
    private $chargePenaltyOrExtraForm;
    /**
     * @var BusinessPaymentService
     */
    private $businessPaymentService;
    /**
     * @var BusinessService
     */
    private $businessService;
    /**
     * @var Translator
     */
    private $translator;

    /**
     * PenaltyController constructor.
     * @param ChargePenaltyOrExtraForm $chargePenaltyOrExtraForm
     * @param BusinessPaymentService $businessPaymentService
     * @param BusinessService $businessService
     * @param Translator $translator
     */
    public function __construct(
        ChargePenaltyOrExtraForm $chargePenaltyOrExtraForm,
        BusinessPaymentService $businessPaymentService,
        BusinessService $businessService,
        Translator $translator
    ) {
        $this->chargePenaltyOrExtraForm = $chargePenaltyOrExtraForm;
        $this->businessPaymentService = $businessPaymentService;
        $this->businessService = $businessService;
        $this->translator = $translator;
    }

    public function chargeAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $data = $this->getRequest()->getPost();
                $business = $this->businessService->findByName($data['business']);
                $this->businessPaymentService->addPenaltyOrExtra($business, $data['amount'], $data['reason']);
                $this->flashMessenger()->addSuccessMessage($this->translator->translate('Addebito avvenuto con successo'));
            } catch (InvalidFormDataException $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }

            return $this->redirect()->toRoute(
                'business/penalty'
            );

        }
        return new ViewModel([
            'form' => $this->chargePenaltyOrExtraForm
        ]);
    }
}
