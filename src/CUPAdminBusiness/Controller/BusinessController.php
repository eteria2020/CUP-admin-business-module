<?php

namespace CUPAdminBusiness\Controller;

use CUPAdminBusiness\Entity\Business;
use CUPAdminBusiness\Form\BusinessForm;
use CUPAdminBusiness\Form\InputData\BusinessDataFactory;
use CUPAdminBusiness\Service\BusinessService;
use Doctrine\ORM\EntityNotFoundException;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class BusinessController extends AbstractActionController
{
    /**
     * @var BusinessService
     */
    private $businessService;

    /**
     * @var BusinessDataFactory
     */
    private $businessDataFactory;
    /**
     * @var BusinessForm
     */
    private $businessForm;


    /**
     * BusinessController constructor.
     * @param BusinessService $businessService
     * @param BusinessForm $businessForm
     * @param BusinessDataFactory $businessDataFactory
     */
    public function __construct(
        BusinessService $businessService,
        BusinessForm $businessForm,
        BusinessDataFactory $businessDataFactory
    ) {
        $this->businessService = $businessService;
        $this->businessForm = $businessForm;
        $this->businessDataFactory = $businessDataFactory;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function addAction()
    {
        $translator = $this->TranslatorPlugin();
        $form = $this->businessForm;

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();

            try {
                $inputData = $this->businessDataFactory->fromArray($data);

                $this->businessService->addBusiness($inputData);

                $this->flashMessenger()->addSuccessMessage($translator->translate('Azienda aggiunta con successo'));

                return $this->redirect()->toRoute('business');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());

                return $this->redirect()->toRoute('business/add');
            }

        }
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction()
    {
        $translator = $this->TranslatorPlugin();
        $code = $this->params()->fromRoute('code', 0);
        $business = $this->businessService->getBusinessByCode($code);
        $tab = $this->params()->fromQuery('tab', 'edit');

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();

            try {
                $this->businessService->updateBusiness($business, $data);

                $this->flashMessenger()->addSuccessMessage($translator->translate('Azienda modificata con successo'));

                return $this->redirect()->toRoute('business');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());

                return $this->redirect()->toRoute('business');
            }
        }

        return new ViewModel([
            'business' => $business,
            'tab' => $tab
        ]);
    }

    public function infoTabAction()
    {
        /** @var Business $business */
        $business = $this->getBusiness();

        $view = new ViewModel([
            'business' => $business,
        ]);
        $view->setTerminal(true);

        return $view;
    }

    public function editTabAction()
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

    public function paramsTabAction()
    {
        /** @var Business $business */
        $business = $this->getBusiness();

        $view = new ViewModel([
            'business' => $business,
        ]);
        $view->setTerminal(true);

        return $view;
    }

    public function datatableAction()
    {
        $filters = $this->params()->fromPost();
        $filters['withLimit'] = true;
        $dataDataTable = $this->businessService->getDataDataTable($filters);
        $totalBusinesses = $this->businessService->getTotalBusinesses();
        $recordsFiltered = $this->getRecordsFiltered($filters, $totalBusinesses);

        return new JsonModel([
            'draw'            => $this->params()->fromQuery('sEcho', 0),
            'recordsTotal'    => $totalBusinesses,
            'recordsFiltered' => $recordsFiltered,
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

    protected function getRecordsFiltered($filters, $totalBusinesses)
    {
        if (empty($filters['searchValue']) && !isset($filters['columnValueWithoutLike'])) {
            return $totalBusinesses;
        } else {
            $as_filters['withLimit'] = false;
            return $this->businessService->getDataDataTable($filters, true);
        }
    }
}
