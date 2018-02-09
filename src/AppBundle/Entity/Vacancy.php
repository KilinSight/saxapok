<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vacancy
 *
 * @ORM\Table(name="vacancy", indexes={@ORM\Index(name="region", columns={"region"})})
 * @ORM\Entity
 */
class Vacancy
{
    /**
     * @var string
     *
     * @ORM\Column(name="organization", type="text", length=65535, nullable=false)
     */
    private $organization;

    /**
     * @var string
     *
     * @ORM\Column(name="industry", type="text", length=65535, nullable=false)
     */
    private $industry;

    /**
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param string $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return string
     */
    public function getIndustry()
    {
        return $this->industry;
    }

    /**
     * @param string $industry
     */
    public function setIndustry($industry)
    {
        $this->industry = $industry;
    }

    /**
     * @return string
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * @param string $profession
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;
    }

    /**
     * @return \DateTime
     */
    public function getCreationdate()
    {
        return $this->creationdate;
    }

    /**
     * @param \DateTime $creationdate
     */
    public function setCreationdate($creationdate)
    {
        $this->creationdate = $creationdate;
    }

    /**
     * @return \DateTime
     */
    public function getDateposted()
    {
        return $this->dateposted;
    }

    /**
     * @param \DateTime $dateposted
     */
    public function setDateposted($dateposted)
    {
        $this->dateposted = $dateposted;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getHiringorganization()
    {
        return $this->hiringorganization;
    }

    /**
     * @param string $hiringorganization
     */
    public function setHiringorganization($hiringorganization)
    {
        $this->hiringorganization = $hiringorganization;
    }

    /**
     * @return float
     */
    public function getBasesalary()
    {
        return $this->basesalary;
    }

    /**
     * @param float $basesalary
     */
    public function setBasesalary($basesalary)
    {
        $this->basesalary = $basesalary;
    }

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
     * @return string
     */
    public function getEmploymenttype()
    {
        return $this->employmenttype;
    }

    /**
     * @param string $employmenttype
     */
    public function setEmploymenttype($employmenttype)
    {
        $this->employmenttype = $employmenttype;
    }

    /**
     * @return string
     */
    public function getWorkhours()
    {
        return $this->workhours;
    }

    /**
     * @param string $workhours
     */
    public function setWorkhours($workhours)
    {
        $this->workhours = $workhours;
    }

    /**
     * @return string
     */
    public function getResponsibilities()
    {
        return $this->responsibilities;
    }

    /**
     * @param string $responsibilities
     */
    public function setResponsibilities($responsibilities)
    {
        $this->responsibilities = $responsibilities;
    }

    /**
     * @return string
     */
    public function getIncentivecompensation()
    {
        return $this->incentivecompensation;
    }

    /**
     * @param string $incentivecompensation
     */
    public function setIncentivecompensation($incentivecompensation)
    {
        $this->incentivecompensation = $incentivecompensation;
    }

    /**
     * @return string
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * @param string $requirements
     */
    public function setRequirements($requirements)
    {
        $this->requirements = $requirements;
    }

    /**
     * @return string
     */
    public function getSocialprotecteds()
    {
        return $this->socialprotecteds;
    }

    /**
     * @param string $socialprotecteds
     */
    public function setSocialprotecteds($socialprotecteds)
    {
        $this->socialprotecteds = $socialprotecteds;
    }

    /**
     * @return string
     */
    public function getMetrostations()
    {
        return $this->metrostations;
    }

    /**
     * @param string $metrostations
     */
    public function setMetrostations($metrostations)
    {
        $this->metrostations = $metrostations;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return int
     */
    public function getWorkplaces()
    {
        return $this->workplaces;
    }

    /**
     * @param int $workplaces
     */
    public function setWorkplaces($workplaces)
    {
        $this->workplaces = $workplaces;
    }

    /**
     * @return string
     */
    public function getAdditionalinfo()
    {
        return $this->additionalinfo;
    }

    /**
     * @param string $additionalinfo
     */
    public function setAdditionalinfo($additionalinfo)
    {
        $this->additionalinfo = $additionalinfo;
    }

    /**
     * @return string
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param string $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * @return string
     */
    public function getVacurl()
    {
        return $this->vacurl;
    }

    /**
     * @param string $vacurl
     */
    public function setVacurl($vacurl)
    {
        $this->vacurl = $vacurl;
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
     * @var string
     *
     * @ORM\Column(name="profession", type="text", length=65535, nullable=false)
     */
    private $profession;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="date", nullable=false)
     */
    private $creationdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePosted", type="datetime", nullable=false)
     */
    private $dateposted;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="text", length=65535, nullable=false)
     */
    private $identifier;

    /**
     * @var string
     *
     * @ORM\Column(name="hiringOrganization", type="text", length=65535, nullable=false)
     */
    private $hiringorganization;

    /**
     * @var float
     *
     * @ORM\Column(name="baseSalary", type="float", precision=10, scale=0, nullable=false)
     */
    private $basesalary;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", length=65535, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="employmentType", type="text", length=65535, nullable=false)
     */
    private $employmenttype;

    /**
     * @var string
     *
     * @ORM\Column(name="workHours", type="text", length=65535, nullable=false)
     */
    private $workhours;

    /**
     * @var string
     *
     * @ORM\Column(name="responsibilities", type="text", length=65535, nullable=false)
     */
    private $responsibilities;

    /**
     * @var string
     *
     * @ORM\Column(name="incentiveCompensation", type="text", length=65535, nullable=false)
     */
    private $incentivecompensation;

    /**
     * @var string
     *
     * @ORM\Column(name="requirements", type="text", length=65535, nullable=false)
     */
    private $requirements;

    /**
     * @var string
     *
     * @ORM\Column(name="socialProtecteds", type="text", length=65535, nullable=false)
     */
    private $socialprotecteds;

    /**
     * @var string
     *
     * @ORM\Column(name="metroStations", type="text", length=65535, nullable=false)
     */
    private $metrostations;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="text", length=65535, nullable=false)
     */
    private $source;

    /**
     * @var integer
     *
     * @ORM\Column(name="workPlaces", type="integer", nullable=false)
     */
    private $workplaces;

    /**
     * @var string
     *
     * @ORM\Column(name="additionalInfo", type="text", length=65535, nullable=false)
     */
    private $additionalinfo;

    /**
     * @var string
     *
     * @ORM\Column(name="deleted", type="text", length=65535, nullable=false)
     */
    private $deleted;

    /**
     * @var string
     *
     * @ORM\Column(name="vacUrl", type="text", length=65535, nullable=false)
     */
    private $vacurl;

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
     *   @ORM\JoinColumn(name="region", referencedColumnName="id")
     * })
     */
    private $region;


}

