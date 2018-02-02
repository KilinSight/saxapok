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

