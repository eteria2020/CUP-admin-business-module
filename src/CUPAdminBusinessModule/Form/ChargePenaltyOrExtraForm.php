<?php

namespace CUPAdminBusinessModule\Form;

use BusinessCore\Entity\Base\BusinessPayment;
use BusinessCore\Entity\ExtraPayment;
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
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'business',
                'class'    => 'form-control',
                'required' => 'required',
                'placeholder' => $translator->translate("Inizia a digitare il nome dell'azienda per l'autocompletamento")
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
            'name'       => 'reason',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'reason',
                'maxlength' => 64,
                'class'    => 'form-control',
                'required' => 'required',
                'placeholder' => $translator->translate("Causale addebito")
            ]
        ]);
    }
}
