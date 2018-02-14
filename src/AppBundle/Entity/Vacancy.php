<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vacancy
 *
 * @ORM\Table(name="vacancy", indexes={@ORM\Index(name="region__id", columns={"region__id"}), @ORM\Index(name="organization_id", columns={"organization_id"}), @ORM\Index(name="industry_id", columns={"industry_id"}), @ORM\Index(name="profession_id", columns={"profession_id"})})
 * @ORM\Entity
 */
class Vacancy
{
    /**
     * @var string
     *
     * @ORM\Column(name="vacancy_id", type="text", length=65535, nullable=false)
     */
    private $vacancyId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="date", nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_posted", type="datetime", nullable=false)
     */
    private $datePosted;

    /**
     * @var float
     *
     * @ORM\Column(name="base_salary", type="float", precision=10, scale=0, nullable=false)
     */
    private $baseSalary;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", length=65535, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="employment_type", type="text", length=65535, nullable=false)
     */
    private $employmentType;

    /**
     * @var string
     *
     * @ORM\Column(name="work_hours", type="text", length=65535, nullable=false)
     */
    private $workHours;

    /**
     * @var string
     *
     * @ORM\Column(name="responsibilities", type="text", length=65535, nullable=false)
     */
    private $responsibilities;

    /**
     * @var string
     *
     * @ORM\Column(name="incentive_compensation", type="text", length=65535, nullable=false)
     */
    private $incentiveCompensation;

    /**
     * @var string
     *
     * @ORM\Column(name="requirements", type="text", length=65535, nullable=false)
     */
    private $requirements;

    /**
     * @var string
     *
     * @ORM\Column(name="social_protecteds", type="text", length=65535, nullable=false)
     */
    private $socialProtecteds;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="text", length=65535, nullable=false)
     */
    private $source;

    /**
     * @var integer
     *
     * @ORM\Column(name="work_places", type="integer", nullable=false)
     */
    private $workPlaces;

    /**
     * @var string
     *
     * @ORM\Column(name="additional_info", type="text", length=65535, nullable=false)
     */
    private $additionalInfo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
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
     * @ORM\GeneratedValue(strategy="Identity")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Industries
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Industries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="industry_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $industry;

    /**
     * @var \AppBundle\Entity\Organizations
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organizations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $organization;

    /**
     * @return string
     */
    public function getVacancyId()
    {
        return $this->vacancyId;
    }

    /**
     * @param string $vacancyId
     */
    public function setVacancyId($vacancyId)
    {
        $this->vacancyId = $vacancyId;
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
     * @return \DateTime
     */
    public function getDatePosted()
    {
        return $this->datePosted;
    }

    /**
     * @param \DateTime $datePosted
     */
    public function setDatePosted($datePosted)
    {
        $this->datePosted = $datePosted;
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
     * @return Industries
     */
    public function getIndustry()
    {
        return $this->industry;
    }

    /**
     * @param Industries $industry
     */
    public function setIndustry($industry)
    {
        $this->industry = $industry;
    }

    /**
     * @return Organizations
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param Organizations $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
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
     * @return Professions
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * @param Professions $profession
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;
    }

    /**
     * @var \AppBundle\Entity\Regions
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Regions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region__id", referencedColumnName="region_id", nullable=true)
     * })
     */
    private $region;

    /**
     * @var \AppBundle\Entity\Professions
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Professions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profession_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $profession;


}

