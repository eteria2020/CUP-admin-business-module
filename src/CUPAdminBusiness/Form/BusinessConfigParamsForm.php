<?php

namespace CUPAdminBusiness\Form;

use BusinessCore\Entity\Business;
use CUPAdminBusiness\Form\Helper\BusinessPaymentHelper;
use Zend\Form\Form;
use Zend\Mvc\I18n\Translator;

class BusinessConfigParamsForm extends Form
{
    public function __construct(Translator $translator, BusinessPaymentHelper $businessPaymentHelper)
    {
        parent::__construct('business');
        $this->setAttribute('method', 'post');

        $this->add([
            'name'       => 'paymentType',
            'type'       => 'Zend\Form\Element\Select',
            'attributes' => [
                'id'       => 'paymentType',
                'class'    => 'form-control'
            ],
            'options'    => [
                'value_options' => [
                    null => '---',
                    Business::TYPE_WIRE_TRANSFER => $businessPaymentHelper->getPrintableVersion(Business::TYPE_WIRE_TRANSFER),
                    Business::TYPE_CREDIT_CARD => $businessPaymentHelper->getPrintableVersion(Business::TYPE_CREDIT_CARD),
                ]
            ]
        ]);

        $this->add([
            'name'       => 'paymentFrequence',
            'type'       => 'Zend\Form\Element\Select',
            'attributes' => [
                'id'       => 'paymentFrequence',
                'class'    => 'form-control'
            ],
            'options'    => [
            'value_options' => [
                null => '---',
                Business::FREQUENCE_WEEKLY => $businessPaymentHelper->getPrintableVersion(Business::FREQUENCE_WEEKLY),
                Business::FREQUENCE_FORTNIGHTLY => $businessPaymentHelper->getPrintableVersion(Business::FREQUENCE_FORTNIGHTLY),
                Business::FREQUENCE_MONTHLY => $businessPaymentHelper->getPrintableVersion(Business::FREQUENCE_MONTHLY)
            ]
        ]
        ]);

        $this->add([
            'name'       => 'businessMailControl',
            'type'       => 'Zend\Form\Element\Select',
            'attributes' => [
                'id'       => 'businessMailControl',
                'class'    => 'form-control'
            ],
            'options'    => [
                'value_options' => [
                    0 => $translator->translate("No"),
                    1 => $translator->translate("Si")
                    ]
                ]
        ]);

        $this->add([
            'name'       => 'submit',
            'attributes' => [
                'type'  => 'submit',
                'value' => 'Submit'
            ]
        ]);
    }
}
