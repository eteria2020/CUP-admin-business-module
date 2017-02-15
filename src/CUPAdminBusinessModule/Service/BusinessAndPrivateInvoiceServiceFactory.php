<?php

namespace CUPAdminBusinessModule\Service;


use CUPAdminBusinessModule\Service\Queries\BusinessAndPrivateInvoiceQueries;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessAndPrivateInvoiceServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        $businessAndPrivateInvoiceQueries = new BusinessAndPrivateInvoiceQueries($entityManager);
        $businessInvoiceRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessInvoice');
        $privateInvoiceRepository = $entityManager->getRepository('SharengoCore\Entity\Invoices');

        return new BusinessAndPrivateInvoiceService(
            $businessAndPrivateInvoiceQueries,
            $businessInvoiceRepository,
            $privateInvoiceRepository
        );
    }
}
