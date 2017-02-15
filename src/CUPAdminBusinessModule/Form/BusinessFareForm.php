<?php

namespace CUPAdminBusinessModule\Form;

use Zend\Form\Form;

class BusinessFareForm extends Form
{
    public function __construct()
    {
        parent::__construct('business');
        $this->setAttribute('method', 'post');

        $this->add([
            'name'       => 'motion',
            'type'       => 'Zend\Form\Element\Number',
            'attributes' => [
                'id'       => 'motion',
                'min' => '0',
                'max'    => '100',
                'step'    => '1',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name'       => 'park',
            'type'       => 'Zend\Form\Element\Number',
            'attributes' => [
                'id'       => 'park',
                'min' => '0',
                'max'    => '100',
                'step'    => '1',
                'required' => 'required'
            ]
        ]);
    }
}
