<?php

namespace CUPAdminBusinessModule\Form;

use BusinessCore\Entity\BusinessPayment;
use Zend\Form\Form;
use Zend\Mvc\I18n\Translator;

class ChargePenaltyOrExtraForm extends Form
{
    public function __construct(Translator $translator)
    {
        parent::__construct('penalty');
        $this->setAttribute('method', 'post');

        $this->add([
            'name'       => 'business',
            'type'       => 'Zend\Form\Element\Select',
            'attributes' => [
                'id'       => 'business',
                'class'    => 'form-control',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name'       => 'amount',
            'type'       => 'Zend\Form\Element\Number',
            'attributes' => [
                'id'       => 'amount',
                'min' => 0.00,
                'step' => 0.01,
                'class'    => 'form-control',
                'required' => 'required',
                'placeholder' => $translator->translate("Importo addebito in €")
            ]
        ]);

        $this->add([
            'name'       => 'type',
            'type'       => 'Zend\Form\Element\Select',
            'attributes' => [
                'id'       => 'type',
                'class'    => 'form-control'
            ],
            'options'    => [
                'value_options' => [
                    BusinessPayment::EXTRA_TYPE => $translator->translate("Extra"),
                    BusinessPayment::PENALTY_TYPE => $translator->translate("Penale")
                ]
            ]
        ]);
    }
}
