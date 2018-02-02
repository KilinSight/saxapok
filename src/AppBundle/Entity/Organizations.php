<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var integer
     *
     * @ORM\Column(name="creation_date", type="integer", nullable=false)
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
     * @var string
     *
     * @ORM\Column(name="date_modify", type="text", length=65535, nullable=false)
     */
    private $dateModify;

    /**
     * @var string
     *
     * @ORM\Column(name="deleted", type="text", length=65535, nullable=false)
     */
    private $deleted;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="text", length=65535, nullable=false)
     */
    private $status;

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


}

