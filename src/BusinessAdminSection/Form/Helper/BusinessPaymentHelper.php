<?php
namespace BusinessAdminSection\Form\Helper;

use BusinessCore\Entity\Business;
use Zend\Mvc\I18n\Translator;

class BusinessPaymentHelper
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getPrintableVersion($string)
    {
        switch ($string) {
            case Business::TYPE_WIRE_TRANSFER:
                return $this->translator->translate("Bonifico Bancario");
            case Business::TYPE_CREDIT_CARD:
                return $this->translator->translate("Carta di credito");
            case Business::FREQUENCE_WEEKLY:
                return $this->translator->translate("Settimanale");
            case Business::FREQUENCE_FORTNIGHTLY:
                return $this->translator->translate("Quindicinale");
            case Business::FREQUENCE_MONTHLY:
                return $this->translator->translate("Mensile");
            default:
                return $string;
        }
    }
}
