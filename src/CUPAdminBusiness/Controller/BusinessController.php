<?php

namespace CUPAdminBusiness\Controller;

use BusinessCore\Entity\Business;
use BusinessCore\Form\InputData\BusinessDataFactory;
use BusinessCore\Service\BusinessService;
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
     * @var BusinessDataFactory
     */
    private $businessDataFactory;
    /**
     * @var BusinessForm
     */
    private $businessForm;
    /**
     * @var Translator
     */
    private $translator;


    /**
     * BusinessController constructor.
     * @param Translator $translator
     * @param BusinessService $businessService
     * @param BusinessForm $businessForm
     * @param BusinessDataFactory $businessDataFactory
     */
    public function __construct(
        Translator $translator,
        BusinessService $businessService,
        BusinessForm $businessForm,
        BusinessDataFactory $businessDataFactory
    ) {
        $this->businessService = $businessService;
        $this->businessForm = $businessForm;
        $this->businessDataFactory = $businessDataFactory;
        $this->translator = $translator;
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
                $inputData = $this->businessDataFactory->fromArray($data);

                $this->businessService->addBusiness($inputData);

                $this->flashMessenger()->addSuccessMessage($this->translator->translate('Azienda aggiunta con successo'));

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
        $code = $this->params()->fromRoute('code', 0);
        $business = $this->businessService->getBusinessByCode($code);
        $tab = $this->params()->fromQuery('tab', 'info');

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $tab = $data['tabType'];
            try {
                $this->businessService->updateBusiness($business, $data);

                $this->flashMessenger()->addSuccessMessage($this->translator->translate('Azienda modificata con successo'));

            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());

            }
            return $this->redirect()->toRoute(
                'business/edit',
                ['code' => $business->getCode()],
                ['query' => ['tab' => $tab]]
            );
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
            'business' => $business
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
        $form = $this->businessForm;

        $view = new ViewModel([
            'business' => $business,
            'form' => $form
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
