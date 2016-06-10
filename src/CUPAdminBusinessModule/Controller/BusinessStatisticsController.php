<?php

namespace CUPAdminBusinessModule\Controller;

use BusinessCore\Service\BusinessService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class BusinessStatisticsController extends AbstractActionController
{
    /**
     * @var Translator
     */
    private $translator;
    /**
     * @var BusinessService
     */
    private $businessService;

    /**
     * BusinessStatisticsController constructor.
     * @param Translator $translator
     * @param BusinessService $businessService
     */
    public function __construct(Translator $translator, BusinessService $businessService)
    {
        $this->translator = $translator;
        $this->businessService = $businessService;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function dataAction()
    {
        $filters = $this->params()->fromPost();
        $json = $this->businessService->getBusinessStatsData($filters);

        return new JsonModel($json);
    }
}
