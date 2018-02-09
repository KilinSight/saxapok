<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Regions
 *
 * @ORM\Table(name="regions")
 * @ORM\Entity
 */
class Regions
{
    /**
     * @var string
     *
     * @ORM\Column(name="region_code", type="text", length=65535, nullable=false)
     */
    private $regionCode;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", length=65535, nullable=false)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="home", type="float", precision=10, scale=0, nullable=false)
     */
    private $home;

    /**
     * @var float
     *
     * @ORM\Column(name="economic_growth", type="float", precision=10, scale=0, nullable=false)
     */
    private $economicGrowth;

    /**
     * @var integer
     *
     * @ORM\Column(name="kindergarten_count", type="integer", nullable=false)
     */
    private $kindergartenCount;

    /**
     * @return string
     */
    public function getRegionCode()
    {
        return $this->regionCode;
    }

    /**
     * @param string $regionCode
     */
    public function setRegionCode($regionCode)
    {
        $this->regionCode = $regionCode;
    }

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
     * @return float
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * @param float $home
     */
    public function setHome($home)
    {
        $this->home = $home;
    }

    /**
     * @return float
     */
    public function getEconomicGrowth()
    {
        return $this->economicGrowth;
    }

    /**
     * @param float $economicGrowth
     */
    public function setEconomicGrowth($economicGrowth)
    {
        $this->economicGrowth = $economicGrowth;
    }

    /**
     * @return int
     */
    public function getKindergartenCount()
    {
        return $this->kindergartenCount;
    }

    /**
     * @param int $kindergartenCount
     */
    public function setKindergartenCount($kindergartenCount)
    {
        $this->kindergartenCount = $kindergartenCount;
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
     * @return float
     */
    public function getPriceLevel()
    {
        return $this->priceLevel;
    }

    /**
     * @param float $priceLevel
     */
    public function setPriceLevel($priceLevel)
    {
        $this->priceLevel = $priceLevel;
    }

    /**
     * @return float
     */
    public function getUnemploymentLevel()
    {
        return $this->unemploymentLevel;
    }

    /**
     * @param float $unemploymentLevel
     */
    public function setUnemploymentLevel($unemploymentLevel)
    {
        $this->unemploymentLevel = $unemploymentLevel;
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
     * @var float
     *
     * @ORM\Column(name="avg_salary", type="float", precision=10, scale=0, nullable=false)
     */
    private $avgSalary;

    /**
     * @var float
     *
     * @ORM\Column(name="price_level", type="float", precision=10, scale=0, nullable=false)
     */
    private $priceLevel;

    /**
     * @var float
     *
     * @ORM\Column(name="unemployment_level", type="float", precision=10, scale=0, nullable=false)
     */
    private $unemploymentLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


}

