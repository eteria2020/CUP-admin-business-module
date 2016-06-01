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
        $stats = $this->businessService->getBusinessStatsData();

        $labels = [];
        $data = [];

        foreach ($stats as $row) {
            $labels[] = $row['name'];
            $data[] = $row['minutes'];
        }

        return new JsonModel([
            "labels" => $labels,
            "data" =>  $data
        ]);
    }
}
