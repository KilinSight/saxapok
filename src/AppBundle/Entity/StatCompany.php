<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatCompany
 *
 * @ORM\Table(name="stat_company", indexes={@ORM\Index(name="region_code", columns={"region_code"})})
 * @ORM\Entity
 */
class StatCompany
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
     * @ORM\Column(name="all_count", type="integer", nullable=false)
     */
    private $allCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="micro50_count", type="integer", nullable=false)
     */
    private $micro50Count;

    /**
     * @var integer
     *
     * @ORM\Column(name="small100_count", type="integer", nullable=false)
     */
    private $small100Count;

    /**
     * @var integer
     *
     * @ORM\Column(name="middle250_count", type="integer", nullable=false)
     */
    private $middle250Count;

    /**
     * @var integer
     *
     * @ORM\Column(name="big500_count", type="integer", nullable=false)
     */
    private $big500Count;

    /**
     * @var integer
     *
     * @ORM\Column(name="large_over500_count", type="integer", nullable=false)
     */
    private $largeOver500Count;

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
    public function getAllCount()
    {
        return $this->allCount;
    }

    /**
     * @param int $allCount
     */
    public function setAllCount($allCount)
    {
        $this->allCount = $allCount;
    }

    /**
     * @return int
     */
    public function getMicro50Count()
    {
        return $this->micro50Count;
    }

    /**
     * @param int $micro50Count
     */
    public function setMicro50Count($micro50Count)
    {
        $this->micro50Count = $micro50Count;
    }

    /**
     * @return int
     */
    public function getSmall100Count()
    {
        return $this->small100Count;
    }

    /**
     * @param int $small100Count
     */
    public function setSmall100Count($small100Count)
    {
        $this->small100Count = $small100Count;
    }

    /**
     * @return int
     */
    public function getMiddle250Count()
    {
        return $this->middle250Count;
    }

    /**
     * @param int $middle250Count
     */
    public function setMiddle250Count($middle250Count)
    {
        $this->middle250Count = $middle250Count;
    }

    /**
     * @return int
     */
    public function getBig500Count()
    {
        return $this->big500Count;
    }

    /**
     * @param int $big500Count
     */
    public function setBig500Count($big500Count)
    {
        $this->big500Count = $big500Count;
    }

    /**
     * @return int
     */
    public function getLargeOver500Count()
    {
        return $this->largeOver500Count;
    }

    /**
     * @param int $largeOver500Count
     */
    public function setLargeOver500Count($largeOver500Count)
    {
        $this->largeOver500Count = $largeOver500Count;
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

