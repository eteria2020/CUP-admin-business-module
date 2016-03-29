<?php

namespace CUPAdminBusiness\Service;

use Zend\Mvc\I18n\Translator;

class BusinessService
{
    /**
     * @var string website base url
     */
    private $url;

    /**
     * @var Translator
     */
    private $translator;


    /**
     * BusinessService constructor.
     * @param Translator $translator
     * @param $url
     */
    public function __construct(
        Translator $translator,
        $url
    ) {
        $this->translator = $translator;
        $this->url = $url;
    }

    public function getBusinesses()
    {
        return [];
    }
}
