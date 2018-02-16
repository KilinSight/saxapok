<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resumes
 *
 * @ORM\Table(name="resumes", indexes={@ORM\Index(name="region_id", columns={"region_id"}), @ORM\Index(name="profession_id", columns={"profession_id"}), @ORM\Index(name="industry_id", columns={"industry_id"})})
 * @ORM\Entity
 */
class Resumes
{
    /**
     * @var string
     *
     * @ORM\Column(name="cv_id", type="text", length=65535, nullable=false)
     */
    private $cvId;

    /**
     * @var string
     *
     * @ORM\Column(name="position_name", type="text", length=65535, nullable=false)
     */
    private $positionName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="date", nullable=false)
     */
    private $creationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="education_list", type="text", length=65535, nullable=false)
     */
    private $educationList;

    /**
     * @var string
     *
     * @ORM\Column(name="drive_licence_list", type="text", length=65535, nullable=false)
     */
    private $driveLicenceList;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="text", length=65535, nullable=false)
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publish_date", type="date", nullable=false)
     */
    private $publishDate;

    /**
     * @var string
     *
     * @ORM\Column(name="schedule_type_list", type="text", length=65535, nullable=false)
     */
    private $scheduleTypeList;

    /**
     * @var integer
     *
     * @ORM\Column(name="experience", type="integer", nullable=false)
     */
    private $experience;

    /**
     * @var float
     *
     * @ORM\Column(name="salary", type="float", precision=10, scale=0, nullable=false)
     */
    private $salary;

    /**
     * @var string
     *
     * @ORM\Column(name="skills", type="text", length=65535, nullable=false)
     */
    private $skills;

    /**
     * @var string
     *
     * @ORM\Column(name="additional_skills", type="text", length=65535, nullable=false)
     */
    private $additionalSkills;

    /**
     * @var string
     *
     * @ORM\Column(name="busy_type", type="text", length=65535, nullable=false)
     */
    private $busyType;

    /**
     * @var string
     *
     * @ORM\Column(name="relocation", type="text", length=65535, nullable=false)
     */
    private $relocation;

    /**
     * @var string
     *
     * @ORM\Column(name="candidate_id", type="text", length=65535, nullable=false)
     */
    private $candidateId;

    /**
     * @var string
     *
     * @ORM\Column(name="retraining_capability", type="text", length=65535, nullable=false)
     */
    private $retrainingCapability;

    /**
     * @var string
     *
     * @ORM\Column(name="other_info", type="text", length=65535, nullable=false)
     */
    private $otherInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="business_trips", type="text", length=65535, nullable=false)
     */
    private $businessTrips;

    /**
     * @var string
     *
     * @ORM\Column(name="add_certificates", type="text", length=65535, nullable=false)
     */
    private $addCertificates;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="Identity")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Professions
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Professions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profession_id", referencedColumnName="prof_id")
     * })
     */
    private $profession;

    /**
     * @return string
     */
    public function getCvId()
    {
        return $this->cvId;
    }

    /**
     * @param string $cvId
     */
    public function setCvId($cvId)
    {
        $this->cvId = $cvId;
    }

    /**
     * @return string
     */
    public function getPositionName()
    {
        return $this->positionName;
    }

    /**
     * @param string $positionName
     */
    public function setPositionName($positionName)
    {
        $this->positionName = $positionName;
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
     * @return string
     */
    public function getEducationList()
    {
        return $this->educationList;
    }

    /**
     * @param string $educationList
     */
    public function setEducationList($educationList)
    {
        $this->educationList = $educationList;
    }

    /**
     * @return string
     */
    public function getDriveLicenceList()
    {
        return $this->driveLicenceList;
    }

    /**
     * @param string $driveLicenceList
     */
    public function setDriveLicenceList($driveLicenceList)
    {
        $this->driveLicenceList = $driveLicenceList;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return \DateTime
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * @param \DateTime $publishDate
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;
    }

    /**
     * @return string
     */
    public function getScheduleTypeList()
    {
        return $this->scheduleTypeList;
    }

    /**
     * @param string $scheduleTypeList
     */
    public function setScheduleTypeList($scheduleTypeList)
    {
        $this->scheduleTypeList = $scheduleTypeList;
    }

    /**
     * @return int
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param int $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }

    /**
     * @return float
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * @param float $salary
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;
    }

    /**
     * @return string
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param string $skills
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;
    }

    /**
     * @return string
     */
    public function getAdditionalSkills()
    {
        return $this->additionalSkills;
    }

    /**
     * @param string $additionalSkills
     */
    public function setAdditionalSkills($additionalSkills)
    {
        $this->additionalSkills = $additionalSkills;
    }

    /**
     * @return string
     */
    public function getBusyType()
    {
        return $this->busyType;
    }

    /**
     * @param string $busyType
     */
    public function setBusyType($busyType)
    {
        $this->busyType = $busyType;
    }

    /**
     * @return string
     */
    public function getRelocation()
    {
        return $this->relocation;
    }

    /**
     * @param string $relocation
     */
    public function setRelocation($relocation)
    {
        $this->relocation = $relocation;
    }

    /**
     * @return string
     */
    public function getCandidateId()
    {
        return $this->candidateId;
    }

    /**
     * @param string $candidateId
     */
    public function setCandidateId($candidateId)
    {
        $this->candidateId = $candidateId;
    }

    /**
     * @return string
     */
    public function getRetrainingCapability()
    {
        return $this->retrainingCapability;
    }

    /**
     * @param string $retrainingCapability
     */
    public function setRetrainingCapability($retrainingCapability)
    {
        $this->retrainingCapability = $retrainingCapability;
    }

    /**
     * @return string
     */
    public function getOtherInfo()
    {
        return $this->otherInfo;
    }

    /**
     * @param string $otherInfo
     */
    public function setOtherInfo($otherInfo)
    {
        $this->otherInfo = $otherInfo;
    }

    /**
     * @return string
     */
    public function getBusinessTrips()
    {
        return $this->businessTrips;
    }

    /**
     * @param string $businessTrips
     */
    public function setBusinessTrips($businessTrips)
    {
        $this->businessTrips = $businessTrips;
    }

    /**
     * @return string
     */
    public function getAddCertificates()
    {
        return $this->addCertificates;
    }

    /**
     * @param string $addCertificates
     */
    public function setAddCertificates($addCertificates)
    {
        $this->addCertificates = $addCertificates;
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
     * @var \AppBundle\Entity\Regions
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Regions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_id", referencedColumnName="region_id")
     * })
     */
    private $region;

    /**
     * @var \AppBundle\Entity\Industries
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Industries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="industry_id", referencedColumnName="id")
     * })
     */
    private $industry;


}

