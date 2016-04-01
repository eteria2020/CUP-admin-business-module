<?php

namespace CUPAdminBusiness\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Business
 *
 * @ORM\Table(name="business", schema="businesses")
 * @ORM\Entity(repositoryClass="CUPAdminBusiness\Entity\Repository\BusinessRepository")

 */
class Business
{
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(name="domains", type="json_array", nullable=true)
     */
    private $domains;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", nullable=false)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", nullable=false)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="vat_number", type="string", nullable=false)
     */
    private $vatNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", nullable=false)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_type", type="string", nullable=false)
     */
    private $paymentType;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_frequence", type="string", nullable=false)
     */
    private $paymentFrequence;

    /**
     * @var bool
     *
     * @ORM\Column(name="business_mail_control", type="boolean", nullable=false)
     */
    private $businessMailControl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inserted_ts", type="datetime", nullable=false)
     */
    private $insertedTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_ts", type="datetime", nullable=false)
     */
    private $updatedTs;

    /**
     * Business constructor.
     * @param string $code
     * @param string $name
     * @param string $domains
     * @param string $address
     * @param string $zipCode
     * @param string $province
     * @param string $city
     * @param string $vatNumber
     * @param string $email
     * @param string $phone
     * @param string $fax
     * @param \DateTime $insertedTs
     */
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
        \DateTime $insertedTs
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
        $this->insertedTs = $insertedTs;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return \DateTime
     */
    public function getInsertedTs()
    {
        return $this->insertedTs;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVatNumber()
    {
        return $this->vatNumber;
    }

    /**
     * @return string
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedTs()
    {
        return $this->updatedTs;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param array $domains
     */
    public function setDomains($domains)
    {
        $this->domains = $domains;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @param string $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @param string $vatNumber
     */
    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = $vatNumber;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @param \DateTime $updatedTs
     */
    public function setUpdatedTs($updatedTs)
    {
        $this->updatedTs = $updatedTs;
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @return string
     */
    public function getPaymentFrequence()
    {
        return $this->paymentFrequence;
    }

    /**
     * @return boolean
     */
    public function isBusinessMailControl()
    {
        return $this->businessMailControl;
    }

    /**
     * @param \DateTime $insertedTs
     */
    public function setInsertedTs($insertedTs)
    {
        $this->insertedTs = $insertedTs;
    }

    /**
     * @param boolean $businessMailControl
     */
    public function setBusinessMailControl($businessMailControl)
    {
        $this->businessMailControl = $businessMailControl;
    }

    /**
     * @param string $paymentFrequence
     */
    public function setPaymentFrequence($paymentFrequence)
    {
        $this->paymentFrequence = $paymentFrequence;
    }

    /**
     * @param string $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
    }


}
