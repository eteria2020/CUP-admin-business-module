<?php

namespace BusinessAdminSection\Form;

use Zend\Form\Form;

class BusinessDetailsForm extends Form
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
            'name'       => 'submit',
            'attributes' => [
                'type'  => 'submit',
                'value' => 'Submit'
            ]
        ]);
    }
}
