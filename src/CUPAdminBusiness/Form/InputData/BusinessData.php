<?php

namespace CUPAdminBusiness\Form\InputData;

class BusinessData
{
    private $code;
    private $name;
    private $domains;
    private $address;
    private $zipCode;
    private $province;
    private $city;
    private $vatNumber;
    private $email;
    private $phone;
    private $fax;
    private $insertedAt;
    private $paymentType;
    private $paymentFrequence;
    private $businessMailControl;


    public function __construct(
        $code,
        $name,
        $domains,
        $address,
        $zipCode,
        $province,
        $city,
        $vatNumber,
        $email,
        $phone,
        $fax,
        $insertedAt,
        $paymentType,
        $paymentFrequence,
        $businessMailControl
    ) {

        $this->code = $code;
        $this->name = $name;
        $this->domains = $domains;
        $this->address = $address;
        $this->zipCode = $zipCode;
        $this->province = $province;
        $this->city = $city;
        $this->vatNumber = $vatNumber;
        $this->email = $email;
        $this->phone = $phone;
        $this->fax = $fax;
        $this->insertedAt = $insertedAt;
        $this->paymentType = $paymentType;
        $this->paymentFrequence = $paymentFrequence;
        $this->businessMailControl = $businessMailControl;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDomains()
    {
        return $this->domains;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function getProvince()
    {
        return $this->province;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getVatNumber()
    {
        return $this->vatNumber;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getFax()
    {
        return $this->fax;
    }

    public function getInsertedAt()
    {
        return $this->insertedAt;
    }

    public function getPaymentType()
    {
        return $this->paymentType;
    }

    public function getPaymentFrequence()
    {
        return $this->paymentFrequence;
    }

    public function getBusinessMailControl()
    {
        return $this->businessMailControl;
    }



}
