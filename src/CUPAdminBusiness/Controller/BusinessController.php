<?php

namespace CUPAdminBusiness\Controller;

use BusinessCore\Entity\Business;
use BusinessCore\Exception\InvalidBusinessFormException;
use BusinessCore\Form\InputData\BusinessDataFactory;
use BusinessCore\Service\BusinessService;
use CUPAdminBusiness\Form\BusinessForm;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
     * BusinessController constructor.
     * @param Translator $translator
     * @param BusinessService $businessService
     * @param BusinessForm $businessForm
     */
    public function __construct(
        Translator $translator,
        BusinessService $businessService,
        BusinessForm $businessForm
    ) {
        $this->businessService = $businessService;
        $this->businessForm = $businessForm;
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

    public function doEditDataAction()
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

    public function editDataTabAction()
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