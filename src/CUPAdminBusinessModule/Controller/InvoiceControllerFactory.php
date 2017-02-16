<?php

namespace CUPAdminBusinessModule\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InvoiceControllerFactory implements FactoryInterface
{
    /**
     * Default method to be used in a Factory Class
     *
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     * @param ServiceLocatorInterface $serviceLocator
     * @return InvoiceController|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sharedServiceLocator = $serviceLocator->getServiceLocator();

        $businessAndPrivateInvoiceService = $sharedServiceLocator->get('CUPAdminBusinessModule\Service\BusinessAndPrivateInvoiceService');
        $datatableService = $sharedServiceLocator->get('BusinessCore\Service\DatatableService');
        $pdfService = $sharedServiceLocator->get('BusinessCore\Service\PdfService');

        return new InvoiceController(
            $businessAndPrivateInvoiceService,
            $datatableService,
            $pdfService
        );
    }
}
