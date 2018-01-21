<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * Vacancy
 *
 * @ORM\Table(name="vacancy")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VacancyRepository")
 */
class Vacancy
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string")
     */
    private $region;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="organization", type="string")
     */
    private $organization;

    /**
     * @var string
     *
     * @ORM\Column(name="industry", type="string")
     */
    private $industry;

    /**
     * @var string
     *
     * @ORM\Column(name="profession", type="string")
     */
    private $profession;

    /**
     * @var string
     *
     * @ORM\Column(name="creationDate", type="string")
     */
    private $creationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="datePosted", type="string")
     */
    private $datePosted;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string")
     */
    private $identifier;


    /**
     * @var string
     *
     * @ORM\Column(name="hiringOrganization", type="string")
     */
    private $hiringOrganization;

    /**
     * @var float
     *
     * @ORM\Column(name="baseSalary", type="float")
     */
    private $baseSalary;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="employmentType", type="string")
     */
    private $employmentType;

    /**
     * @var string
     *
     * @ORM\Column(name="workHours", type="string")
     */
    private $workHours;

    /**
     * @var string
     *
     * @ORM\Column(name="responsibilities", type="string")
     */
    private $responsibilities;

    /**
     * @var string
     *
     * @ORM\Column(name="incentiveCompensation", type="string")
     */
    private $incentiveCompensation;

    /**
     * @var string
     *
     * @ORM\Column(name="requirements", type="string")
     */
    private $requirements;

    /**
     * @var string
     *
     * @ORM\Column(name="socialProtecteds", type="string")
     */
    private $socialProtecteds;

    /**
     * @var string
     *
     * @ORM\Column(name="metroStations", type="string")
     */
    private $metroStations;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string")
     */
    private $source;

    /**
     * @var integer
     *
     * @ORM\Column(name="workPlaces", type="integer")
     */
    private $workPlaces;

    /**
     * @var string
     *
     * @ORM\Column(name="additionalInfo", type="string")
     */
    private $additionalInfo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted;

    /**
     * @var string
     *
     * @ORM\Column(name="vacUrl", type="string")
     */
    private $vacUrl;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set creationDate
     *
     * @param string $creationDate
     *
     * @return Vacancy
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return string
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getVacUrl()
    {
        return $this->vacUrl;
    }

    /**
     * @param string $vacUrl
     */
    public function setVacUrl($vacUrl)
    {
        $this->vacUrl = $vacUrl;
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
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @param string $additionalInfo
     */
    public function setAdditionalInfo($additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;
    }

    /**
     * @return int
     */
    public function getWorkPlaces()
    {
        return $this->workPlaces;
    }

    /**
     * @param int $workPlaces
     */
    public function setWorkPlaces($workPlaces)
    {
        $this->workPlaces = $workPlaces;
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
     * @return string
     */
    public function getMetroStations()
    {
        return $this->metroStations;
    }

    /**
     * @param string $metroStations
     */
    public function setMetroStations($metroStations)
    {
        $this->metroStations = $metroStations;
    }

    /**
     * @return string
     */
    public function getSocialProtecteds()
    {
        return $this->socialProtecteds;
    }

    /**
     * @param string $socialProtecteds
     */
    public function setSocialProtecteds($socialProtecteds)
    {
        $this->socialProtecteds = $socialProtecteds;
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
    public function getIncentiveCompensation()
    {
        return $this->incentiveCompensation;
    }

    /**
     * @param string $incentiveCompensation
     */
    public function setIncentiveCompensation($incentiveCompensation)
    {
        $this->incentiveCompensation = $incentiveCompensation;
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
    public function getWorkHours()
    {
        return $this->workHours;
    }

    /**
     * @param string $workHours
     */
    public function setWorkHours($workHours)
    {
        $this->workHours = $workHours;
    }

    /**
     * @return string
     */
    public function getEmploymentType()
    {
        return $this->employmentType;
    }

    /**
     * @param string $employmentType
     */
    public function setEmploymentType($employmentType)
    {
        $this->employmentType = $employmentType;
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
     * @return float
     */
    public function getBaseSalary()
    {
        return $this->baseSalary;
    }

    /**
     * @param float $baseSalary
     */
    public function setBaseSalary($baseSalary)
    {
        $this->baseSalary = $baseSalary;
    }

    /**
     * @return string
     */
    public function getHiringOrganization()
    {
        return $this->hiringOrganization;
    }

    /**
     * @param string $hiringOrganization
     */
    public function setHiringOrganization($hiringOrganization)
    {
        $this->hiringOrganization = $hiringOrganization;
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
    public function getDatePosted()
    {
        return $this->datePosted;
    }

    /**
     * @param string $datePosted
     */
    public function setDatePosted($datePosted)
    {
        $this->datePosted = $datePosted;
    }

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


}

