<?php

namespace CUPAdminBusiness\Form;

use CUPAdminBusiness\Entity\Business;
use Zend\Form\Form;
use Zend\Mvc\I18n\Translator;

class BusinessForm extends Form
{
    public function __construct(Translator $translator)
    {
        parent::__construct('business');
        $this->setAttribute('method', 'post');

        $this->add([
            'name'       => 'name',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'name',
                'maxlength' => 64,
                'class'    => 'form-control',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name'       => 'domains',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'domains',
                'class'    => 'form-control'
            ]
        ]);

        $this->add([
            'name'       => 'address',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'address',
                'maxlength' => 64,
                'class'    => 'form-control',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name'       => 'zipCode',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'zipCode',
                'maxlength' => 12,
                'class'    => 'form-control',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name'       => 'province',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'province',
                'maxlength' => 2,
                'class'    => 'form-control',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name'       => 'city',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'city',
                'maxlength' => 64,
                'class'    => 'form-control',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name'       => 'vatNumber',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'vatNumber',
                'maxLength' => 13,
                'class'    => 'form-control',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name'       => 'email',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'email',
                'class'    => 'form-control'
            ]
        ]);

        $this->add([
            'name'       => 'phone',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'number',
                'maxlength' => 13,
                'class'    => 'form-control'
            ]
        ]);

        $this->add([
            'name'       => 'fax',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'fax',
                'maxlength' => 13,
                'class'    => 'form-control'
            ]
        ]);

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
                    Business::TYPE_WIRE_TRANSFER => $translator->translate(Business::TYPE_WIRE_TRANSFER),
                    Business::TYPE_CREDIT_CARD => $translator->translate(Business::TYPE_CREDIT_CARD)
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
                Business::FREQUENCE_WEEKLY => $translator->translate(Business::FREQUENCE_WEEKLY),
                Business::FREQUENCE_FORTHNIGHTLY => $translator->translate(Business::FREQUENCE_FORTHNIGHTLY),
                Business::FREQUENCE_MONTHLY => $translator->translate(Business::FREQUENCE_MONTHLY)
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
