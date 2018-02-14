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
     * @ORM\Column(name="federal_district_id", type="text", length=65535, nullable=false)
     */
    private $federalDistrictId;

    /**
     * @return string
     */
    public function getFederalDistrictId()
    {
        return $this->federalDistrictId;
    }

    /**
     * @param string $federalDistrictId
     */
    public function setFederalDistrictId($federalDistrictId)
    {
        $this->federalDistrictId = $federalDistrictId;
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
     * @return int
     */
    public function getRegionId()
    {
        return $this->regionId;
    }

    /**
     * @param int $regionId
     */
    public function setRegionId($regionId)
    {
        $this->regionId = $regionId;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", length=65535, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="region_id", type="bigint")
     * @ORM\Id
     */
    private $regionId;


}

