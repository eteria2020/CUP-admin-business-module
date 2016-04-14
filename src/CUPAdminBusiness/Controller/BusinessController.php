<?php

namespace CUPAdminBusiness\Controller;

use BusinessCore\Entity\Business;
use BusinessCore\Exception\InvalidBusinessFormException;
use BusinessCore\Form\InputData\BusinessDataFactory;
use BusinessCore\Service\BusinessService;
use BusinessCore\Service\DatatableService;
use CUPAdminBusiness\Form\BusinessForm;

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
     * @var BusinessForm
     */

    private $businessForm;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var DatatableService
     */
    private $datatableService;

    /**
     * BusinessController constructor.
     * @param Translator $translator
     * @param DatatableService $datatableService
     * @param BusinessService $businessService
     * @param BusinessForm $businessForm
     */
    public function __construct(
        Translator $translator,
        DatatableService $datatableService,
        BusinessService $businessService,
        BusinessForm $businessForm
    ) {
        $this->businessService = $businessService;
        $this->businessForm = $businessForm;
        $this->translator = $translator;
        $this->datatableService = $datatableService;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function addAction()
    {
        $form = $this->businessForm;

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();

            try {
                $inputData = BusinessDataFactory::businessDatafromArray($data);
                $inputParams = BusinessDataFactory::businessParamsfromArray($data);

                $this->businessService->addBusiness($inputData, $inputParams);

                $this->flashMessenger()->addSuccessMessage($this->translator->translate('Azienda aggiunta con successo'));

                return $this->redirect()->toRoute('business');
            } catch (InvalidBusinessFormException $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
                return $this->redirect()->toRoute('business/add');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translator->translate("Si è verificato un errore durante l'inserimento, per favore riprova"));
                return $this->redirect()->toRoute('business/add');
            }
        }
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction()
    {
        $code = $this->params()->fromRoute('code', 0);
        $business = $this->businessService->getBusinessByCode($code);
        $tab = $this->params()->fromQuery('tab', 'info');

        return new ViewModel([
            'business' => $business,
            'tab' => $tab
        ]);
    }

    public function doEditDetailsAction()
    {
        $business = $this->getBusiness();
        $data = $this->getRequest()->getPost()->toArray();
        try {
            $inputData = BusinessDataFactory::businessDatafromArray($data);
            $this->businessService->updateBusiness($business, $inputData);
            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Azienda modificata con successo'));
        } catch (InvalidBusinessFormException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translator->translate('Si è verificato un errore durante la modifica'));
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
            $inputData = BusinessDataFactory::businessParamsfromArray($data);
            $this->businessService->updateBusiness($business, $inputData);
            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Parametri aziendali modificati con successo'));
        } catch (InvalidBusinessFormException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translator->translate('Si è verificato un errore durante la modifica'));
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
            'business' => $business
        ]);
        $view->setTerminal(true);

        return $view;
    }

    public function editDetailsTabAction()
    {
        return $this->editView();
    }

    public function editParamsTabAction()
    {
        return $this->editView();
    }

    private function editView()
    {
        /** @var Business $business */
        $business = $this->getBusiness();
        $form = $this->businessForm;

        $view = new ViewModel([
            'business' => $business,
            'form' => $form
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

    public function removeEmployeeAction()
    {
        $businessCode = $this->params()->fromRoute('code', 0);
        $employeeId = $this->params()->fromRoute('id', 0);

        try {
            $this->businessService->removeEmployee($businessCode, $employeeId);

            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Dipendente eliminato con successo'));

        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

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

        try {
            $this->businessService->blockEmployee($businessCode, $employeeId);

            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Dipendente bloccato con successo'));

        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

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

        try {
            $this->businessService->unblockEmployee($businessCode, $employeeId);

            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Dipendente sbloccato con successo'));

        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

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
