<?php

namespace CUPAdminBusinessModule\Form;

use Zend\Form\Form;

class BusinessUserForm extends Form
{
    public function __construct()
    {
        parent::__construct('business-user');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => [
                'id' => 'name',
                'class' => 'form-control',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name'       => 'email',
            'type'       => 'Zend\Form\Element\Email',
            'attributes' => [
                'id'       => 'email',
                'class'    => 'form-control',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name'       => 'password1',
            'type'       => 'Zend\Form\Element\Password',
            'attributes' => [
                'id'       => 'password1',
                'class'    => 'form-control',
                'required' => 'required',
                'minlength' => 8
            ]
        ]);

        $this->add([
            'name'       => 'password2',
            'type'       => 'Zend\Form\Element\Password',
            'attributes' => [
                'id'       => 'password2',
                'class'    => 'form-control',
                'required' => 'required',
                'minlength' => 8
            ]
        ]);
    }
}
