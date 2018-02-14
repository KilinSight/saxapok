<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organizations
 *
 * @ORM\Table(name="organizations", indexes={@ORM\Index(name="region_id", columns={"region_id"})})
 * @ORM\Entity
 */
class Organizations
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", length=65535, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="date", nullable=false)
     */
    private $creationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="legal_name", type="text", length=65535, nullable=false)
     */
    private $legalName;

    /**
     * @var string
     *
     * @ORM\Column(name="inn", type="text", length=65535, nullable=false)
     */
    private $inn;

    /**
     * @var string
     *
     * @ORM\Column(name="adress_code", type="text", length=65535, nullable=false)
     */
    private $adressCode;

    /**
     * @var string
     *
     * @ORM\Column(name="first_rate_company", type="text", length=65535, nullable=false)
     */
    private $firstRateCompany;

    /**
     * @var string
     *
     * @ORM\Column(name="business_size", type="text", length=65535, nullable=false)
     */
    private $businessSize;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return string
     */
    public function getLegalName()
    {
        return $this->legalName;
    }

    /**
     * @param string $legalName
     */
    public function setLegalName($legalName)
    {
        $this->legalName = $legalName;
    }

    /**
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * @param string $inn
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
    }

    /**
     * @return string
     */
    public function getAdressCode()
    {
        return $this->adressCode;
    }

    /**
     * @param string $adressCode
     */
    public function setAdressCode($adressCode)
    {
        $this->adressCode = $adressCode;
    }

    /**
     * @return string
     */
    public function getFirstRateCompany()
    {
        return $this->firstRateCompany;
    }

    /**
     * @param string $firstRateCompany
     */
    public function setFirstRateCompany($firstRateCompany)
    {
        $this->firstRateCompany = $firstRateCompany;
    }

    /**
     * @return string
     */
    public function getBusinessSize()
    {
        return $this->businessSize;
    }

    /**
     * @param string $businessSize
     */
    public function setBusinessSize($businessSize)
    {
        $this->businessSize = $businessSize;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * @return Organizations
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * @param Organizations $organizationId
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;
    }

    /**
     * @return Regions
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param Regions $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
     */
    private $deleted;

    /**
     * @var string
     *
     * @ORM\Column(name="organization_id", type="text")
     */
    private $organizationId;

    /**
     * @var integer
     *
     * @ORM\Column(name="organization_id", type="int")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="Identity")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Regions
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Regions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_id", referencedColumnName="region_id")
     * })
     */
    private $region;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


}

