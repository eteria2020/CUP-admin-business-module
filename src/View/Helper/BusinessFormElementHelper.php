<?php
namespace CUPAdminBusinessModule\View\Helper;

use Zend\View\Helper\AbstractHelper;

class BusinessFormElementHelper extends AbstractHelper
{
    public function __invoke($label, $field, $disabled = false)
    {
        if ($disabled) {
            $field->setAttributes(['disabled' => 'disabled']);
        }
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label>' . $label . '</label>';
        $html .= $this->view->formElement($field);
        $html .= '</div>';

        return $html;
    }
}
