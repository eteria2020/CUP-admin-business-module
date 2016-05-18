<?php

namespace CUPAdminBusinessModule\Controller;

use CUPAdminBusinessModule\Form\ChargePenaltyOrExtraForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PenaltyController extends AbstractActionController
{
    /**
     * @var ChargePenaltyOrExtraForm
     */
    private $chargePenaltyOrExtraForm;

    /**
     * PenaltyController constructor.
     * @param ChargePenaltyOrExtraForm $chargePenaltyOrExtraForm
     */
    public function __construct(ChargePenaltyOrExtraForm $chargePenaltyOrExtraForm)
    {
        $this->chargePenaltyOrExtraForm = $chargePenaltyOrExtraForm;
    }

    public function chargeAction()
    {
        if ($this->getRequest()->isPost()) {
            //todo add penalty
        }
        return new ViewModel([
            'form' => $this->chargePenaltyOrExtraForm
        ]);
    }
}
