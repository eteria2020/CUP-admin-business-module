<?php

namespace CUPAdminBusinessModule\Form;

use Zend\Form\Form;

class TimePackageForm extends Form
{
    public function __construct()
    {
        parent::__construct('time-packages');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'minutes',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => [
                'id' => 'minutes',
                'min' => 0,
                'class' => 'form-control',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'cost',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => [
                'id' => 'cost',
                'min' => 0,
                'max' => 100000,
                'step' => 0.01,
                'class' => 'form-control',
                'required' => 'required'
            ]
        ]);
    }
}
