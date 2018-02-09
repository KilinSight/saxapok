<?php

namespace AppBundle\Entity;



/**
 * Organizations
 *
 * @ORM\Table(name="organizations", indexes={@ORM\Index(name="region_code", columns={"region_code"})})
 * @ORM\Entity
 */
class Organizations
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", length=65535, nullable=false)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="date", nullable=false)
     */
    private $creationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="ogrn", type="text", length=65535, nullable=false)
     */
    private $ogrn;

    /**
     * @var string
     *
     * @ORM\Column(name="inn", type="text", length=65535, nullable=false)
     */
    private $inn;

    /**
     * @var string
     *
     * @ORM\Column(name="kpp", type="text", length=65535, nullable=false)
     */
    private $kpp;

    /**
     * @var string
     *
     * @ORM\Column(name="adress_code", type="text", length=65535, nullable=false)
     */
    private $adressCode;

    /**
     * @var string
     *
     * @ORM\Column(name="rate_company", type="text", length=65535, nullable=false)
     */
    private $rateCompany;

    /**
     * @var string
     *
     * @ORM\Column(name="business_size", type="text", length=65535, nullable=false)
     */
    private $businessSize;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modify", type="date", nullable=false)
     */
    private $dateModify;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
     */
    private $deleted;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="text", length=65535, nullable=false)
     */
    private $status;

    /**
     * @return string
     */
    public function getRateCompany()
    {
        return $this->rateCompany;
    }

    /**
     * @param string $rateCompany
     */
    public function setRateCompany($rateCompany)
    {
        $this->rateCompany = $rateCompany;
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
     * @return \DateTime
     */
    public function getDateModify()
    {
        return $this->dateModify;
    }

    /**
     * @param \DateTime $dateModify
     */
    public function setDateModify($dateModify)
    {
        $this->dateModify = $dateModify;
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
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

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

    /**
     * @return Regions
     */
    public function getRegionCode()
    {
        return $this->regionCode;
    }

    /**
     * @param Regions $regionCode
     */
    public function setRegionCode($regionCode)
    {
        $this->regionCode = $regionCode;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Regions
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Regions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_code", referencedColumnName="id")
     * })
     */
    private $regionCode;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    public function getOgrn()
    {
        return $this->ogrn;
    }

    /**
     * @param string $ogrn
     */
    public function setOgrn($ogrn)
    {
        $this->ogrn = $ogrn;
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
    public function getKpp()
    {
        return $this->kpp;
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
     * @param string $kpp
     */
    public function setKpp($kpp)
    {
        $this->kpp = $kpp;
    }


}

