<?php

namespace CUPAdminBusiness\Form;


use CUPAdminBusiness\Form\Helper\BusinessPaymentHelper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessConfigParamsFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return BusinessForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $translator = $serviceLocator->get('translator');
        $paymentHelper = new BusinessPaymentHelper($translator);

        return new BusinessConfigParamsForm($translator, $paymentHelper);
    }
}