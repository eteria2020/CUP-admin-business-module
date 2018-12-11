<?php
namespace CUPAdminBusinessModule\Controller;

use BusinessCore\Entity\BusinessInvoice;
use BusinessCore\Service\DatatableService;
use BusinessCore\Service\PdfService;
use CUPAdminBusinessModule\Service\BusinessAndPrivateInvoiceService;

use SharengoCore\Entity\Invoices as PrivateInvoice;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class InvoiceController extends AbstractActionController
{
    /**
     * @var BusinessAndPrivateInvoiceService
     */
    private $businessAndPrivateInvoiceService;
    /**
     * @var DatatableService
     */
    private $datatableService;
    /**
     * @var PdfService
     */
    private $pdfService;

    public function __construct(
        BusinessAndPrivateInvoiceService $businessAndPrivateInvoiceService,
        DatatableService $datatableService,
        PdfService $pdfService
    ) {
        $this->businessAndPrivateInvoiceService = $businessAndPrivateInvoiceService;
        $this->datatableService = $datatableService;
        $this->pdfService = $pdfService;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function privateInvoicePdfAction()
    {
        $invoiceId = urldecode($this->params('id'));
        $this->redirect()->toRoute('pdf/invoices', ['id' => $invoiceId]);
    }

    public function businessInvoicePdfAction()
    {
        $invoiceId = urldecode($this->params('id'));
        $businessInvoice = $this->businessAndPrivateInvoiceService->findBusinessInvoiceById($invoiceId);
        return $this->generatePdfResponse($businessInvoice);
    }

    private function generatePdfResponse(BusinessInvoice $invoice)
    {
        $pdf = $this->pdfService->generatePdfFromInvoice($invoice);
        $response = new Response();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'application/pdf');
        $headers->addHeaderLine(
            'Content-Disposition',
            "attachment; filename=\"Fattura-" . $invoice->getInvoiceNumber() . ".pdf\""
        );
        $headers->addHeaderLine('Content-Length', strlen($pdf));

        $response->setContent($pdf);

        return $response;
    }

    public function datatableAction()
    {
        $filters = $this->params()->fromPost();
        $searchCriteria = $this->datatableService->getSearchCriteria($filters);
        $invoices = $this->businessAndPrivateInvoiceService->searchInvoices($searchCriteria);
        $invoicesWithoutPagination = $this->businessAndPrivateInvoiceService->countFilteredInvoiceWithoutPagination($searchCriteria);
        $dataDataTable = $this->mapBusinessInvoicesToDatatable($invoices);
        $totalInvoices = $this->businessAndPrivateInvoiceService->getTotalInvoices();

        return new JsonModel([
            'draw'            => $this->params()->fromQuery('sEcho', 0),
            'recordsTotal'    => $totalInvoices,
            'recordsFiltered' => $invoicesWithoutPagination,
            'data'            => $dataDataTable
        ]);
    }

    private function mapBusinessInvoicesToDatatable(array $invoices)
    {
        return array_map(function ($invoice) {
            return [
                'invoice_number' => $invoice['invoice_number'],
                'date' => $this->formatInvoiceDate($invoice['date']),
                'name' => $invoice['name'],
                'amount' => $this->formatAmount($invoice['total']),
                'type' => $this->formatType($invoice['type']),
                'invoice_pdf_url' => $this->getPdfUrl($invoice)
            ];
        }, $invoices);
    }

    private function formatInvoiceDate($date)
    {
        $date = strval($date);
        $year = substr($date, 0, 4);
        $month = substr($date, 4, 2);
        $day = substr($date, 6, 2);
        $dateTime = date_create()->setDate($year, $month, $day);
        return $dateTime->format('d-m-Y');
    }

    private function formatAmount($amount)
    {
        $amount = intval($amount);
        return number_format($amount / 100, 2) . ' €';
    }

    private function getPdfUrl($invoice)
    {
        $isBusiness = $invoice['is_business'];
        $invoiceId = $invoice['id'];
        if ($isBusiness) {
            $url = $this->url()->fromRoute('invoices-download/business', ['id' => $invoiceId]);
        } else {
            $url = $this->url()->fromRoute('invoices-download/private', ['id' => $invoiceId]);
        }
        return "<a href='" . $url . "' download='".$invoiceId.".pdf'><i class='fa fa-download'></i></a>";
    }

    private function formatType($type)
    {
        $translator = $this->TranslatorPlugin();
        switch ($type) {
            case 'FIRST_PAYMENT':
                return $translator->translate("Primo pagamento");
            case 'TRIP':
                return $translator->translate("Corsa");
            case 'PENALTY':
                return $translator->translate("Sanzione");
            case 'BONUS_PACKAGE':
                return $translator->translate("Pacchetto bonus");
        }
        return $type;
    }
}
