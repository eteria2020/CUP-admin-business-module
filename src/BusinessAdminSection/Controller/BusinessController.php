<?php

namespace BusinessAdminSection\Controller;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessEmployee;
use BusinessCore\Exception\InvalidBusinessFormException;
use BusinessCore\Form\InputData\BusinessDataFactory;
use BusinessCore\Service\BusinessService;
use BusinessCore\Service\DatatableService;
use BusinessAdminSection\Form\BusinessConfigParamsForm;
use BusinessAdminSection\Form\BusinessDetailsForm;
use Doctrine\ORM\EntityNotFoundException;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class BusinessController extends AbstractActionController
{
    /**
     * @var BusinessService
     */
    private $businessService;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var DatatableService
     */
    private $datatableService;
    /**
     * @var BusinessDetailsForm
     */
    private $businessDetailsForm;
    /**
     * @var BusinessConfigParamsForm
     */
    private $businessConfigParamsForm;

    /**
     * BusinessController constructor.
     * @param Translator $translator
     * @param DatatableService $datatableService
     * @param BusinessService $businessService
     * @param BusinessDetailsForm $businessDetailsForm
     * @param BusinessConfigParamsForm $businessConfigParamsForm
     * @internal param BusinessForm $businessForm
     */
    public function __construct(
        Translator $translator,
        DatatableService $datatableService,
        BusinessService $businessService,
        BusinessDetailsForm $businessDetailsForm,
        BusinessConfigParamsForm $businessConfigParamsForm
    ) {
        $this->businessService = $businessService;
        $this->translator = $translator;
        $this->datatableService = $datatableService;
        $this->businessDetailsForm = $businessDetailsForm;
        $this->businessConfigParamsForm = $businessConfigParamsForm;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function addAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();

            try {
                $inputData = BusinessDataFactory::businessDetailsfromArray($data);
                $inputParams = BusinessDataFactory::businessConfigParamsfromArray($data);

                $this->businessService->addBusiness($inputData, $inputParams);

                $this->flashMessenger()->addSuccessMessage($this->translator->translate('Azienda aggiunta con successo'));

                return $this->redirect()->toRoute('business');
            } catch (InvalidBusinessFormException $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
                return $this->redirect()->toRoute('business/add');
            }
        }
        return new ViewModel([
            'detailsForm' => $this->businessDetailsForm,
            'paramsForm' => $this->businessConfigParamsForm
        ]);
    }

    public function editAction()
    {
        $code = $this->params()->fromRoute('code', 0);
        $business = $this->businessService->getBusinessByCode($code);
        $tab = $this->params()->fromQuery('tab', 'info');

        return new ViewModel([
            'business' => $business,
            'formDetails' => $this->businessDetailsForm,
            'formParams' => $this->businessConfigParamsForm,
            'tab' => $tab
        ]);
    }

    public function doEditDetailsAction()
    {
        $business = $this->getBusiness();
        $data = $this->getRequest()->getPost()->toArray();
        try {
            $inputData = BusinessDataFactory::businessDetailsfromArray($data);
            $this->businessService->updateBusinessDetails($business, $inputData);
            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Azienda modificata con successo'));
        } catch (InvalidBusinessFormException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }
        return $this->redirect()->toRoute(
            'business/edit',
            ['code' => $business->getCode()],
            ['query' => ['tab' => 'edit']]
        );
    }

    public function doEditParamsAction()
    {
        $business = $this->getBusiness();
        $data = $this->getRequest()->getPost()->toArray();
        try {
            $inputData = BusinessDataFactory::businessConfigParamsfromArray($data);
            $this->businessService->updateBusinessConfigParams($business, $inputData);
            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Parametri aziendali modificati con successo'));
        } catch (InvalidBusinessFormException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }
        return $this->redirect()->toRoute(
            'business/edit',
            ['code' => $business->getCode()],
            ['query' => ['tab' => 'params']]
        );
    }

    public function infoTabAction()
    {
        /** @var Business $business */
        $business = $this->getBusiness();

        $view = new ViewModel([
            'business' => $business,
            'form' => $form = $this->businessDetailsForm
        ]);
        $view->setTerminal(true);

        return $view;
    }

    public function editDetailsTabAction()
    {
        /** @var Business $business */
        $business = $this->getBusiness();

        $view = new ViewModel([
            'business' => $business,
            'form' => $form = $this->businessDetailsForm
        ]);
        $view->setTerminal(true);

        return $view;
    }

    public function editParamsTabAction()
    {
        /** @var Business $business */
        $business = $this->getBusiness();

        $view = new ViewModel([
            'business' => $business,
            'form' => $this->businessConfigParamsForm
        ]);
        $view->setTerminal(true);

        return $view;
    }

    public function employeesTabAction()
    {
        $business = $this->getBusiness();

        $view = new ViewModel([
            'business' => $business
        ]);
        $view->setTerminal(true);

        return $view;
    }

    public function approveEmployeeAction()
    {
        $businessCode = $this->params()->fromRoute('code', 0);
        $employeeId = $this->params()->fromRoute('id', 0);

        $this->businessService->setEmployeeStatus($businessCode, $employeeId, BusinessEmployee::STATUS_APPROVED);
        $this->flashMessenger()->addSuccessMessage($this->translator->translate('Dipendente approvato'));

        return $this->redirect()->toRoute(
            'business/edit',
            ['code' => $businessCode],
            ['query' => ['tab' => 'employees']]
        );
    }

    public function removeEmployeeAction()
    {
        $businessCode = $this->params()->fromRoute('code', 0);
        $employeeId = $this->params()->fromRoute('id', 0);

        $this->businessService->removeEmployee($businessCode, $employeeId);
        $this->flashMessenger()->addSuccessMessage($this->translator->translate('Dipendente eliminato con successo'));

        return $this->redirect()->toRoute(
            'business/edit',
            ['code' => $businessCode],
            ['query' => ['tab' => 'employees']]
        );
    }

    public function blockEmployeeAction()
    {
        $businessCode = $this->params()->fromRoute('code', 0);
        $employeeId = $this->params()->fromRoute('id', 0);

        $this->businessService->setEmployeeStatus($businessCode, $employeeId, BusinessEmployee::STATUS_BLOCKED);
        $this->flashMessenger()->addSuccessMessage($this->translator->translate('Dipendente bloccato con successo'));

        return $this->redirect()->toRoute(
            'business/edit',
            ['code' => $businessCode],
            ['query' => ['tab' => 'employees']]
        );
    }

    public function unblockEmployeeAction()
    {
        $businessCode = $this->params()->fromRoute('code', 0);
        $employeeId = $this->params()->fromRoute('id', 0);

        $this->businessService->setEmployeeStatus($businessCode, $employeeId, BusinessEmployee::STATUS_APPROVED);
        $this->flashMessenger()->addSuccessMessage($this->translator->translate('Dipendente sbloccato con successo'));

        return $this->redirect()->toRoute(
            'business/edit',
            ['code' => $businessCode],
            ['query' => ['tab' => 'employees']]
        );
    }

    public function datatableAction()
    {
        $filters = $this->params()->fromPost();
        $searchCriteria = $this->datatableService->getSearchCriteria($filters);
        $businesses = $this->businessService->searchBusinesses($searchCriteria);
        $dataDataTable = $this->mapBusinessesToDatatable($businesses);
        $totalBusinesses = $this->businessService->getTotalBusinesses();

        return new JsonModel([
            'draw'            => $this->params()->fromQuery('sEcho', 0),
            'recordsTotal'    => $totalBusinesses,
            'recordsFiltered' => count($dataDataTable),
            'data'            => $dataDataTable
        ]);
    }

    protected function getBusiness()
    {
        $code = $this->params()->fromRoute('code', 0);
        $business = $this->businessService->getBusinessByCode($code);

        if (is_null($business)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            throw new EntityNotFoundException();
        }
        return $business;
    }

    private function mapBusinessesToDatatable(array $businesses)
    {
        return array_map(function (Business $business) {
            return [
                'e' => [
                    'name' => $business->getName(),
                    'code' => $business->getCode(),
                    'vatNumber' => $business->getVatNumber(),
                    'domains' => $business->getDomains(),
                    'city' => $business->getCity(),
                    'phone' => $business->getPhone(),
                    'insertedTs' => $business->getInsertedTs()->format('d-m-Y H:i:s'),
                ],
                'button' => $business->getCode()
            ];
        }, $businesses);
    }
}
