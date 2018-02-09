<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatCitizens
 *
 * @ORM\Table(name="stat_citizens", indexes={@ORM\Index(name="region_code", columns={"region_code"})})
 * @ORM\Entity
 */
class StatCitizens
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", length=65535, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="resume_count", type="integer", nullable=false)
     */
    private $resumeCount;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="text", length=65535, nullable=false)
     */
    private $currency;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_salary", type="float", precision=10, scale=0, nullable=false)
     */
    private $avgSalary;

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
     * @return int
     */
    public function getResumeCount()
    {
        return $this->resumeCount;
    }

    /**
     * @param int $resumeCount
     */
    public function setResumeCount($resumeCount)
    {
        $this->resumeCount = $resumeCount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getAvgSalary()
    {
        return $this->avgSalary;
    }

    /**
     * @param float $avgSalary
     */
    public function setAvgSalary($avgSalary)
    {
        $this->avgSalary = $avgSalary;
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


}

