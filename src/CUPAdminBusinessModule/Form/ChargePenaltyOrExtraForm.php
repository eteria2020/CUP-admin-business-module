<?php

namespace CUPAdminBusinessModule\Form;

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
                'min' => 0,
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
                    'extra' => $translator->translate("Extra"), //TODO set values as in Payments::something
                    'penalty' => $translator->translate("Penale")
                ]
            ]
        ]);
    }
}
