<?php

namespace CUPAdminBusinessModule\Controller;

use BusinessCore\Entity\Business;
use BusinessCore\Exception\InvalidBusinessFormException;
use BusinessCore\Exception\InvalidBusinessUserFormException;
use BusinessCore\Exception\InvalidFormDataException;
use BusinessCore\Form\InputData\BusinessConfigParams;
use BusinessCore\Form\InputData\BusinessDataFactory;
use BusinessCore\Form\InputData\BusinessUserFactory;
use BusinessCore\Service\BusinessFleetService;
use BusinessCore\Service\BusinessPaymentService;
use BusinessCore\Service\BusinessService;
use BusinessCore\Service\BusinessTimePackageService;
use BusinessCore\Service\DatatableService;
use CUPAdminBusinessModule\Form\BusinessConfigParamsForm;
use CUPAdminBusinessModule\Form\BusinessDetailsForm;
use CUPAdminBusinessModule\Form\BusinessFareForm;
use CUPAdminBusinessModule\Form\BusinessUserForm;
use Doctrine\ORM\EntityNotFoundException;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use ZfcUser\Options\UserServiceOptionsInterface;

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
     * @var BusinessFareForm
     */
    private $businessFareForm;
    /**
     * @var BusinessTimePackageService
     */
    private $businessTimePackageService;
    /**
     * @var BusinessFleetService
     */
    private $fleetService;
    /**
     * @var BusinessPaymentService
     */
    private $businessPaymentService;
    /**
     * @var BusinessUserForm
     */
    private $businessUserForm;
    /**
     * @var UserServiceOptionsInterface
     */
    private $userServiceOptions;

    /**
     * BusinessController constructor.
     * @param Translator $translator
     * @param DatatableService $datatableService
     * @param BusinessService $businessService
     * @param BusinessTimePackageService $businessTimePackageService
     * @param BusinessDetailsForm $businessDetailsForm
     * @param BusinessConfigParamsForm $businessConfigParamsForm
     * @param BusinessFareForm $businessFareForm
     * @param BusinessUserForm $businessUserForm
     * @param BusinessFleetService $fleetService
     * @param BusinessPaymentService $businessPaymentService
     * @param UserServiceOptionsInterface $userServiceOptions
     */
    public function __construct(
        Translator $translator,
        DatatableService $datatableService,
        BusinessService $businessService,
        BusinessTimePackageService $businessTimePackageService,
        BusinessDetailsForm $businessDetailsForm,
        BusinessConfigParamsForm $businessConfigParamsForm,
        BusinessFareForm $businessFareForm,
        BusinessUserForm $businessUserForm,
        BusinessFleetService $fleetService,
        BusinessPaymentService $businessPaymentService,
        UserServiceOptionsInterface $userServiceOptions
    ) {
        $this->businessService = $businessService;
        $this->translator = $translator;
        $this->datatableService = $datatableService;
        $this->businessDetailsForm = $businessDetailsForm;
        $this->businessConfigParamsForm = $businessConfigParamsForm;
        $this->businessFareForm = $businessFareForm;
        $this->businessTimePackageService = $businessTimePackageService;
        $this->fleetService = $fleetService;
        $this->businessPaymentService = $businessPaymentService;
        $this->businessUserForm = $businessUserForm;
        $this->userServiceOptions = $userServiceOptions;
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
                $data['fleet'] = $this->fleetService->findFleetById(intval($data['fleet']));

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
            $data['fleet'] = $this->fleetService->findFleetById(intval($data['fleet']));
            //if subscription fee cents was disabled, because it has already been payed
            //"data" won't contain the value, so I set it manually as the current amount
            if (!isset($data['subscriptionFeeCents'])) {
                $data['subscriptionFeeCents'] = $business->getReadableSubscriptionFee();
            }
            $inputData = BusinessDataFactory::businessConfigParamsfromArray($data);
            $this->manageChangeInBusinessSubscriptionFee($business, $inputData);
            $this->businessService->updateBusinessConfigParams($business, $inputData);
            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Parametri aziendali modificati con successo'));
        } catch (InvalidBusinessFormException $e) {
            $this->flashMessenger()->addErrorMessage($this->translator->translate($e->getMessage()));
        }
        return $this->redirect()->toRoute(
            'business/edit',
            ['code' => $business->getCode()],
            ['query' => ['tab' => 'params']]
        );
    }

    public function doEditFareAction()
    {
        $business = $this->getBusiness();
        $data = $this->getRequest()->getPost();
        try {
            $this->businessService->newBusinessFare($business, $data['motion'], $data['park']);
            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Tariffa aggiornata con successo'));
        } catch (InvalidFormDataException $e) {
            $this->flashMessenger()->addErrorMessage($this->translator->translate('Valori inseriti non corretti'));
        }

        return $this->redirect()->toRoute(
            'business/edit',
            ['code' => $business->getCode()],
            ['query' => ['tab' => 'fare']]
        );
    }

    public function doBusinessUserAction()
    {
        $business = $this->getBusiness();
        $data = $this->getRequest()->getPost();
        try {
            $user = BusinessUserFactory::businessUserfromArrayAndOptions($business, $data, $this->userServiceOptions);
            $this->businessService->persistBusinessUser($user);
            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Utente business aggiunto con successo'));
        } catch (InvalidBusinessUserFormException $e) {
            $this->flashMessenger()->addErrorMessage($this->translator->translate($e->getMessage()));
        }

        return $this->redirect()->toRoute(
            'business/edit',
            ['code' => $business->getCode()],
            ['query' => ['tab' => 'business-user']]
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
        $subscriptionPayment = $this->businessPaymentService->getBusinessSubscriptionPayment($business);
        $view = new ViewModel([
            'subscriptionPayed' => $subscriptionPayment->isPayed() || $subscriptionPayment->isExpectedPayed(),
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

    public function paymentsTabAction()
    {
        $business = $this->getBusiness();

        $view = new ViewModel([
            'business' => $business
        ]);
        $view->setTerminal(true);

        return $view;
    }

    public function fareTabAction()
    {
        /** @var Business $business */
        $business = $this->getBusiness();
        $fare = $business->getActiveBusinessFare();
        $view = new ViewModel([
            'business' => $business,
            'fare' => $fare,
            'form' => $this->businessFareForm
        ]);
        $view->setTerminal(true);

        return $view;
    }

    public function timePackagesTabAction()
    {
        $business = $this->getBusiness();
        $buyablePackages = $business->getBusinessBuyableTimePackages();
        $activeIds = [];

        foreach ($buyablePackages as $pack) {
            $activeIds[] = $pack->getTimePackage()->getId();
        }

        $view = new ViewModel([
            'business' => $business,
            'packages' => $this->businessTimePackageService->findAll(),
            'activePackagesId' => $activeIds
        ]);
        $view->setTerminal(true);

        return $view;
    }

    public function contractTabAction()
    {
        $business = $this->getBusiness();
        $view = new ViewModel([
            'business' => $business,
        ]);

        $view->setTerminal(true);
        return $view;
    }

    public function businessUserTabAction()
    {
        $business = $this->getBusiness();
        $webUser = $this->businessService->findBusinessWebuser($business);
        $view = new ViewModel([
            'webuser' => $webUser,
            'business' => $business,
            'form' => $this->businessUserForm,
        ]);

        $view->setTerminal(true);
        return $view;
    }

    public function disableContractAction()
    {
        $business = $this->getBusiness();
        $this->businessService->disableContract($business);
        return $this->redirect()->toRoute(
            'business/edit',
            ['code' => $business->getCode()],
            ['query' => ['tab' => 'contract-status']]
        );
    }

    public function setPackagesAsBuyableAction()
    {
        $business = $this->getBusiness();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $buyableIds = $this->getBuyablePackageIdsFromData($data);
            $this->businessTimePackageService->setBuyablePackagesFromIds($buyableIds, $business);
            $this->flashMessenger()->addSuccessMessage($this->translator->translate('Pacchetti acquistabili aggiornati'));
        }
        return $this->response;
    }

    public function approveEmployeeAction()
    {
        $businessCode = $this->params()->fromRoute('code', 0);
        $employeeId = $this->params()->fromRoute('id', 0);

        $this->businessService->approveEmployee($businessCode, $employeeId);
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

        $this->businessService->blockEmployee($businessCode, $employeeId);
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

        $this->businessService->unblockEmployee($businessCode, $employeeId);
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
                    'isEnabled' => $business->isEnabled()? $this->translator->translate('Abilitata'): $this->translator->translate("Non abilitata"),

                ],
                'button' => $business->getCode(),
            ];
        }, $businesses);
    }

    /**
     * This function receive raw $data from the form post
     * selected packages come in $data as 'package-1234' => 'on' where 1234 is the id of the package
     * @param $data
     * @return array
     */
    private function getBuyablePackageIdsFromData($data)
    {
        $packageIds = [];
        foreach ($data as $key => $value) {
            if (substr($key, 0, 7) === 'package' && $value === 'on') {
                $id = substr($key, 8);
                $packageIds[] = $id;
            }
        }
        return $packageIds;
    }

    public function typeaheadJsonAction()
    {
        $searchValue = $this->params()->fromQuery()['query'];
        $businesses = $this->businessService->findBySearchValue($searchValue);

        $businesses = array_map(function (Business $business) {
            return [
                'name' => $business->getName(),
                'code' => $business->getCode()
            ];
        }, $businesses);
        return new JsonModel([
            'businesses' => $businesses
        ]);
    }

    private function manageChangeInBusinessSubscriptionFee(Business $business, BusinessConfigParams $inputData)
    {
        $newAmount = $inputData->getSubscriptionFeeCents();
        $this->businessPaymentService->manageChangeInBusinessSubscriptionFee($business, $newAmount);
    }
}
