<?php
namespace CUPAdminBusiness\View\Helper;


use Zend\Form\Form;
use Zend\View\Helper\AbstractHelper;

class BusinessFormHelper extends AbstractHelper
{
    public function __invoke($label, $fieldName, Form $form, $fieldValue = null)
    {
        $field = $form->get($fieldName);
        if (!is_null($fieldValue)) {
            $field->setValue($fieldValue);
        }

        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label>' . $label . '</label>';
        $html .= $this->view->formElement($field);
        $html .= '</div>';

        return $html;
    }
}
