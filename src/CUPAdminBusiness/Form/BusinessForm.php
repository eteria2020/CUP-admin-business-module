<?php

namespace CUPAdminBusiness\Form;

use CUPAdminBusiness\Entity\Business;
use Zend\Form\Form;

class BusinessForm extends Form
{
    public function __construct()
    {
        parent::__construct('business');
        $this->setAttribute('method', 'post');

        $this->add([
            'name'       => 'name',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'name',
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
                'class'    => 'form-control',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name'       => 'zip-code',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'zip-code',
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
                'class'    => 'form-control',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name'       => 'vat-number',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'vat-number',
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
            'name'       => 'payment-type',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'payment-type',
                'class'    => 'form-control'
            ]
        ]);

        $this->add([
            'name'       => 'payment-frequence',
            'type'       => 'Zend\Form\Element\Text',
            'attributes' => [
                'id'       => 'payment-frequence',
                'class'    => 'form-control'
            ]
        ]);

        $this->add([
            'name'       => 'business-mail-control',
            'type'       => 'Zend\Form\Element\Checkbox',
            'attributes' => [
                'id'       => 'business-mail-control',
                'class'    => 'form-control'
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
