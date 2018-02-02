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

