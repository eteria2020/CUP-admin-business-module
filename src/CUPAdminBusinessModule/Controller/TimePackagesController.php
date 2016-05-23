<?php

namespace CUPAdminBusinessModule\Controller;

use BusinessCore\Service\BusinessTimePackageService;
use CUPAdminBusinessModule\Form\TimePackageForm;

use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;

use Zend\View\Model\ViewModel;

class TimePackagesController extends AbstractActionController
{
    /**
     * @var BusinessTimePackageService
     */
    private $businessTimePackageService;
    /**
     * @var TimePackageForm
     */
    private $timePackageForm;
    /**
     * @var Translator
     */
    private $translator;

    /**
     * EmployeesController constructor.
     * @param BusinessTimePackageService $businessTimePackageService
     * @param TimePackageForm $timePackageForm
     * @param Translator $translator
     */
    public function __construct(
        BusinessTimePackageService $businessTimePackageService,
        TimePackageForm $timePackageForm,
        Translator $translator
    ) {
        $this->businessTimePackageService = $businessTimePackageService;
        $this->timePackageForm = $timePackageForm;
        $this->translator = $translator;
    }

    public function indexAction()
    {
        return new ViewModel([
            'packages' => $this->businessTimePackageService->findAll()
        ]);
    }

    public function addAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            $this->businessTimePackageService->addTimePackage($data['minutes'], $data['cost']);

            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Pacchetto creato con successo'));

            return $this->redirect()->toRoute('business/time-packages');

        }

        return new ViewModel([
            'form' => $this->timePackageForm
        ]);
    }
}
