<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Industries;
use AppBundle\Entity\Organizations;
use AppBundle\Entity\Professions;
use AppBundle\Entity\Regions;
use AppBundle\Entity\Resumes;
use AppBundle\Entity\Vacancy;
use Doctrine\Common\Collections\Criteria;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{

    var $reader;
    /**
    * @Route("/api", name="api_index")
     * @param Request $request
     * @return mixed
    */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }


    /**
     * @Route("/api/get_regions", name="api_regions", options = {"expose" : true})
     * @return mixed
     */
    public function getRegionsAction()
    {
        $url = 'http://opendata.trudvsem.ru/7710538364-regions/data-20180109T024910-structure-20161130T143000.xml';
//        $url = 'http://opendata.trudvsem.ru/7710538364-vacancy/data-20180113T031742-structure-20161130T143000.xml';

        $content = simplexml_load_file($url);
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/get_raw_xml", name="get_raw_xml", options = {"expose" : true})
     * @param Request $request
     * @return mixed
     */
    public function readRawXMLAction(Request $request)
    {
        $result=[];
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $url = $request->get('url');
        /** @var Container $container */
        $xmlReader = $this->container->get('simple_parser');

        return new JsonResponse($xmlReader->parse($url));
    }

    /**
     * @Route("/api/write_to_db", name="write_to_db", options = {"expose" : true})
     * @param Request $request
     * @return mixed
     */
    public function writeToDBAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $url = $request->get('url');
        $name = $request->get('name');
//        $urlsArray = [
//            'vacancies' =>'http://opendata.trudvsem.ru/7710538364-vacancy/data-20180113T031742-structure-20161130T143000.xml',
//            'resumes' => 'http://opendata.trudvsem.ru/7710538364-cv/data-20180206T053855-structure-20161130T143000.xml',
//            'organizations' => 'http://opendata.trudvsem.ru/7710538364-organizations/data-20180206T053855-structure-20161130T143000.xml',
//            'regions' => 'http://opendata.trudvsem.ru/7710538364-regions/data-20180206T053855-structure-20161130T143000.xml',
//            'professions' => 'http://opendata.trudvsem.ru/7710538364-professions/data-20180206T053855-structure-20161130T143000.xml',
//            'spheres' => 'http://opendata.trudvsem.ru/7710538364-industries/data-20180206T053855-structure-20161130T143000.xml',
//            'stats_citizens' => 'http://opendata.trudvsem.ru/7710538364-stat-citizens/data-20180205T050000-structure-20170101T000000.xml',
//            'stats_company' => 'http://opendata.trudvsem.ru/7710538364-stat-company/data-20180205T050000-structure-20170101T000000.xml'
//        ];
//        $vacancyUrl = 'http://opendata.trudvsem.ru/7710538364-vacancy/data-20180113T031742-structure-20161130T143000.xml';
//        $resumesUrl = 'http://opendata.trudvsem.ru/7710538364-cv/data-20180206T053855-structure-20161130T143000.xml';
//        $organizationsUrl = 'http://opendata.trudvsem.ru/7710538364-organizations/data-20180206T053855-structure-20161130T143000.xml';
//        $regionsUrl = 'http://opendata.trudvsem.ru/7710538364-regions/data-20180206T053855-structure-20161130T143000.xml';
//        $professionsUrl = 'http://opendata.trudvsem.ru/7710538364-professions/data-20180206T053855-structure-20161130T143000.xml';
//        $spheresUrl = 'http://opendata.trudvsem.ru/7710538364-industries/data-20180206T053855-structure-20161130T143000.xml';
//        $statsCitizensUrl = 'http://opendata.trudvsem.ru/7710538364-stat-citizens/data-20180205T050000-structure-20170101T000000.xml';
//        $statsCompaniesUrl = 'http://opendata.trudvsem.ru/7710538364-stat-company/data-20180205T050000-structure-20170101T000000.xml';

        /** @var \XMLReader $xmlReader */

        var_dump($url);
        $content = $this->ParseXML($url,$name,$limit,$offset);
        $result[]=$content;
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/get_vacancies", name="get_vacancies", options = {"expose" : true})
     * @param Request $request
     * @return mixed
     */
    public function getVacanciesAction(Request $request)
    {
        $result = [
            'success' => false
        ];
        $response['status'] = '200';
        $offset = 1;
        $limit = 1000;
        $i = 0;
        while ($response['status'] === '200') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://opendata.trudvsem.ru/api/v1/vacancies?offset=' . $offset . '&limit=' . $limit);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = json_decode(curl_exec($ch), true);

            foreach ($response['results']['vacancies'] as $i => $vacancy) {
                $vacancyRecord['vacancy_id'] = $vacancy['vacancy']['id'];
                $vacancyRecord['region_id'] = $vacancy['vacancy']['region']['region_code'];
                $vacancyRecord['organization_id'] = $vacancy['vacancy']['company']['ogrn'];
                $vacancyRecord['industry_id'] = $vacancy['vacancy']['category']['specialisation'];
                $vacancyRecord['profession_id'] = $vacancy['vacancy']['job-name'];
                $vacancyRecord['creationDate'] = $vacancy['vacancy']['creation-date'];
                $vacancyRecord['datePosted'] = $vacancy['vacancy']['creation-date'];
                $vacancyRecord['baseSalary'] = $vacancy['vacancy']['salary_min'];
                $vacancyRecord['title'] = $vacancy['vacancy']['job-name'];
                $vacancyRecord['employmentType'] = $vacancy['vacancy']['employment'];
                $vacancyRecord['workHours'] = $vacancy['vacancy']['schedule'];
                $vacancyRecord['responsibilities'] = $vacancy['vacancy']['duty'];
                $vacancyRecord['incentiveCompensation'] = 'NONE';
                $vacancyRecord['requirements'] = $vacancy['vacancy']['requirement']['qualification'];
                $vacancyRecord['socialProtecteds'] = $vacancy['vacancy']['requirement']['qualification'];
                $vacancyRecord['source'] = 'EMPLOYMENT_SERVICE';
                $vacancyRecord['workPlaces'] = 1;
                $vacancyRecord['additionalInfo'] = isset($vacancy['vacancy']['addresses']['adress'][0])?$vacancy['vacancy']['addresses']['adress'][0]['location']: ' ';
                $vacancyRecord['innerInfo'] = 'false';
                $vacancyRecord['vac_url'] = $vacancy['vacancy']['vac_url'];

                $this->setVacancyToDB($vacancyRecord);
                unset($vacancyRecord);
            }
//            $i++;
//            if ($i === 50){
//                die;
//            }
        }
        var_dump($response['status']);
        $result['success'] = true;
        return new JsonResponse($result);
    }

    /**
     * @Route("/api/truncate_db", name="truncate_db", options = {"expose" : true})
     * @param Request $request
     * @return mixed
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function truncateDBAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->delete('AppBundle\Entity\Regions');
        $qb->getQuery()->getResult();
        $qb->delete('AppBundle\Entity\Industries');
        $qb->getQuery()->getResult();
        $qb->delete('AppBundle\Entity\Organizations');
        $qb->getQuery()->getResult();
        $qb->delete('AppBundle\Entity\Professions');
        $qb->getQuery()->getResult();
        $qb->delete('AppBundle\Entity\Resumes');
        $qb->getQuery()->getResult();
        $qb->delete('AppBundle\Entity\Vacancy');
        $qb->getQuery()->getResult();

//        $connection = $em->getConnection();
//        $dbPlatform = $connection->getDatabasePlatform();
//        $connection->beginTransaction();
//        $connection->query('SET FOREIGN_KEY_CHECKS=0');
//        $q = $dbPlatform->getTruncateTableSql($em->getClassMetadata('AppBundle\Entity\Industries')->getTableName());
//        $q = $dbPlatform->getTruncateTableSql($em->getClassMetadata('AppBundle\Entity\Organizations')->getTableName());
//        $q = $dbPlatform->getTruncateTableSql($em->getClassMetadata('AppBundle\Entity\Professions')->getTableName());
//        $q = $dbPlatform->getTruncateTableSql($em->getClassMetadata('AppBundle\Entity\Regions')->getTableName());
//        $q = $dbPlatform->getTruncateTableSql($em->getClassMetadata('AppBundle\Entity\Resumes')->getTableName());
//        $q = $dbPlatform->getTruncateTableSql($em->getClassMetadata('AppBundle\Entity\Vacancy')->getTableName());
//        $connection->executeUpdate($q);
//        $connection->query('SET FOREIGN_KEY_CHECKS=1');
//        $connection->commit();

        return new JsonResponse('OK');
    }

    private function ParseXML($url,$name,$limit = 1000, $offset = 0){
        $progress = [
          'done' => false,
          'offset' => $offset + $limit,
        ];

        $em = $this->getDoctrine()->getManager();
        $xmlReader = new \XMLReader();
        $xmlReader->open($url,'utf-8',LIBXML_PARSEHUGE);
        $found = false;
        $foundAttribute = [];
        $i=0;
        while ($xmlReader->read() && $i++<$limit+$offset) {
            if ($i === 1){
                $startName = $xmlReader->name;
            }elseif($i < $offset && $i>1){
                $xmlReader->next($name);
            }
            if ($startName === $xmlReader->name && $xmlReader->nodeType === \XMLReader::END_ELEMENT){
                $progress['done'] = true;
            }
            if ($xmlReader->name === $startName){
                $xmlReader->read();
                if ($xmlReader->getAttribute('resource') !== null) {
                    $result[$xmlReader->name.'_id'] =$this->parseNode($xmlReader->getAttribute('resource'));
                    $foundAttribute['success'] = true;
//                    print_r("\n");
//                    print_r($xmlReader->name.' : '.$xmlReader->getAttribute('resource'));
//                    print_r($xmlReader->name.' : '.$this->parseNode($xmlReader->getAttribute('resource')));
                }
                if ($xmlReader->getAttribute('about') !== null) {
                    $result[$xmlReader->name.'_id'] = $this->parseNode($xmlReader->getAttribute('about'));
                    $foundAttribute['success'] = true;
                }
            }else{
                if ($xmlReader->getAttribute('resource') !== null) {
                    $result[$xmlReader->name.'_id'] =$this->parseNode($xmlReader->getAttribute('resource'));
                    $foundAttribute['success'] = true;
//                    print_r("\n");
//                    print_r($xmlReader->name.' : '.$xmlReader->getAttribute('resource'));
//                    print_r($xmlReader->name.' : '.$this->parseNode($xmlReader->getAttribute('resource')));
                }
                if ($xmlReader->getAttribute('about') !== null) {
                    $result[$xmlReader->name.'_id'] = $this->parseNode($xmlReader->getAttribute('about'));
                    $foundAttribute['success'] = true;
                }
            }
            while ($xmlReader->read() && $xmlReader->name !== $name) {
                $nextName = $xmlReader->name;
                $found = false;
                $foundAttribute['success'] = false;
                $foundAttribute['result'] = '';
                if ($xmlReader->getAttribute('resource') !== null) {
                    $result[$xmlReader->name.'_id'] =$this->parseNode($xmlReader->getAttribute('resource'));
                    $foundAttribute['success'] = true;
//                    print_r("\n");
//                    print_r($xmlReader->name.' : '.$xmlReader->getAttribute('resource'));
//                    print_r($xmlReader->name.' : '.$this->parseNode($xmlReader->getAttribute('resource')));
                }
                if ($xmlReader->getAttribute('about') !== null) {
                    $result[$xmlReader->name.'_id'] = $this->parseNode($xmlReader->getAttribute('about'));
                    $foundAttribute['success'] = true;
                }

                if ($xmlReader->nodeType !== \XMLReader::END_ELEMENT) {
                    while ($xmlReader->read() && $xmlReader->name !== $nextName) {
                        if ($xmlReader->nodeType !== \XMLReader::END_ELEMENT) {
                            if ($xmlReader->hasValue) {
                                try{
                                    $newstr = preg_replace ("/^[^a-zA-ZА-Яа-я0-9\s]*$/",'', $xmlReader->value);
                                    $newstr = str_replace("'", '', $newstr);
                                    $result['' . $nextName] = $newstr?$newstr:' ';
                                    $found = true;
                                }catch(Exception $e){
                                    $result['' . $nextName] = ' ';
                                    $found = false;
                                }
                            }
                        }elseif (!$foundAttribute['success']) {
                            if (!$found){
                                $result['' . $nextName] = ' ';
                            }
                        }
                    }
                    if ($nextName === $xmlReader->name && !$found) {
                        if (!$foundAttribute['success']){
                            $result['' . $nextName] = ' ';
                        }

                    }
                }else{
                    if (!$foundAttribute['success']) {
                        if (!$found){
                            $result['' . $nextName] = ' ';
                        }
                    }
                    $xmlReader->next();
                }
            }
            if(count($result)){
                if ($name === 'vacancy'){
                    $this->setVacancyToDB($result);
                }elseif($name === 'cv'){
                    $this->setResumeToDB($result);
                }elseif($name === 'organization'){
                    try {
                        $this->setOrganizationToDB($result);
                    } catch (ORMException $e) {
                    }
                }elseif($name === 'region'){
                    $this->setRegionToDB($result);
                }elseif($name === 'prof'){
                    $this->setProfessionToDB($result);
                }elseif($name === 'industry'){
                    $this->setIndustryToDB($result);
                }
                unset($result);
            }
        }
        $progress['total'] = $i-1;
        $progress['done'] = true;
        return $progress;
    }

    /**@param String $node
 *
 * @return integer
 */
    public function parseNode($node){
        $attribute = array_pop(explode('#',$node));
        if (count(explode('/',$attribute))){
            $attribute = array_pop(explode('/',$attribute));
        }
        return $attribute;
    }


    /**@param array $result
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function setVacancyToDB($result){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Regions $region */
        $region = $em->getRepository('AppBundle\Entity\Regions')->find($this->trimRegionCode($result['region_id']));
        /** @var Industries $industry */
        $qb = $em->createQueryBuilder();
        $qb->select('industry')->from('AppBundle\Entity\Industries', 'industry')->andWhere($qb->expr()->like('industry.name',$qb->expr()->literal($result['industry_id'])));
        $industry = $qb->getQuery()->getOneOrNullResult();
        /** @var Organizations $organization */
        $qb = $em->createQueryBuilder();
        $qb->select('organization')->from('AppBundle\Entity\Organizations', 'organization')->andWhere($qb->expr()->eq('organization.organizationId',$result['organization_id']))->setMaxResults(1);
        $organization = $qb->getQuery()->getOneOrNullResult();
        /** @var Professions $profession */
        $qb = $em->createQueryBuilder();
        $qb->select('profession')->from('AppBundle\Entity\Professions', 'profession')->andWhere('profession.name LIKE '.$qb->expr()->literal('%'.$result['profession_id'].'%').'')->setMaxResults(1);
        $profession = $qb->getQuery()->getOneOrNullResult();

        var_dump($profession);
        if($result['innerInfo'] === 'false'){
            $vac = new Vacancy();
            $vac->setVacancyId($result['vacancy_id']?$result['vacancy_id']:' ');
            $vac->setRegion($region);
            $vac->setOrganization($organization);
            $vac->setIndustry($industry?$industry:null);
            $vac->setProfession($profession);
            $vac->setCreationDate($this->transformDate($result['creationDate']?$result['creationDate']:date('Y-m-d')));
            $vac->setDatePosted($this->transformDate($result['datePosted']?$result['datePosted']:date('Y-m-d')));
            $vac->setBaseSalary($result['baseSalary']?$result['baseSalary']:' ');
            $vac->setTitle($result['title']?$result['title']:' ');
            $vac->setEmploymentType($result['employmentType']?$result['employmentType']:' ');
            $vac->setWorkHours($result['workHours']?$result['workHours']:' ');
            $vac->setResponsibilities($result['responsibilities']?$result['responsibilities']:' ');
            $vac->setIncentiveCompensation($result['incentiveCompensation']?$result['incentiveCompensation']:' ');
            $vac->setRequirements($result['requirements']?$result['requirements']:' ');
            $vac->setSocialProtecteds($result['socialProtecteds']?$result['socialProtecteds']:' ');
            $vac->setSource($result['source']?$result['source']:' ');
            $vac->setWorkPlaces($result['workPlaces']?$result['workPlaces']:' ');
            $vac->setAdditionalInfo($result['additionalInfo']?$result['additionalInfo']:' ');
            $vac->setDeleted($result['innerInfo']?$result['innerInfo']:' ');
            $vac->setVacUrl($result['vac_url']?$result['vac_url']:' ');
            $em->persist($vac);
            $em->flush();
        }
    }

    /**@param array $result*/
    private function setResumeToDB($result){
        $em = $this->getDoctrine()->getManager();
        /** @var Regions $region */
        $region = $em->getRepository('AppBundle\Entity\Regions')->find($this->trimRegionCode($result['region_id']));
        /** @var Industries $industry */
        $industry = $em->getRepository('AppBundle\Entity\Industries')->findOneBy(['industryId'=>$result['industry_id']]);
        /** @var Professions $profession */
        $profession = $em->getRepository('AppBundle\Entity\Professions')->findOneBy(['profId'=>strval($result['profession_id'])]);
        $cv = new Resumes();
        $cv->setCvId($result['cv_id']?$result['cv_id']:' ');
        $cv->setRegion($region);
        $cv->setProfession($profession?$profession:null);
        $cv->setIndustry($industry?$industry:null);
        $cv->setPositionName($result['positionName']?$result['positionName']:' ');
        $cv->setCreationDate($result['creationDate']?$this->transformDate($result['creationDate']):$this->transformDate(date('Y-m-d')));
        $cv->setEducationList($result['educationList']?$result['educationList']:' ');
        $cv->setDriveLicenceList($result['driveLicenceList']?$result['driveLicenceList']:' ');
        $cv->setCountry($result['country']?$result['country']:' ');
        $cv->setPublishDate($result['publishDate']?$this->transformDate($result['publishDate']):$this->transformDate(date('Y-m-d')));
        $cv->setScheduleTypeList($result['scheduleTypeList']?$result['scheduleTypeList']:' ');
        $cv->setExperience($result['experience']?$result['experience']:' ');
        $cv->setSalary($result['salary']?$result['salary']:' ');
        $cv->setSkills($result['skills']?$result['skills']:' ');
        $cv->setAdditionalSkills($result['additionalSkills']?$result['additionalSkills']:' ');
        $cv->setBusyType($result['busyType']?$result['busyType']:' ');
        $cv->setRelocation($result['relocation']?$result['relocation']:' ');
        $cv->setCandidateId($result['candidateId']?$result['candidateId']:' ');
        $cv->setRetrainingCapability($result['retrainingCapability']?$result['retrainingCapability']:' ');
        $cv->setOtherInfo($result['otherInfo']?$result['otherInfo']:' ');
        $cv->setBusinessTrips($result['businessTrips']?$result['businessTrips']:' ');
        $cv->setAddCertificates($result['addCertificates']?$result['addCertificates']:' ');
        $em->persist($cv);
        $em->flush();
    }

    /**@param array $result
     * @throws \Doctrine\ORM\ORMException
     */
    private function setOrganizationToDB($result){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Regions $region */
        $region = $em->getRepository('AppBundle\Entity\Regions')->find($this->trimRegionCode($result['region_id']));
        $organization = new Organizations();
        $organization->setOrganizationId($result['ogrn']?$result['ogrn']:' ');
        $organization->setRegion($region);
        $organization->setName($result['name']?$result['name']:' ');
        $organization->setCreationDate($this->transformDate($result['creationDate']?$result['creationDate']:date('Y-m-d')));
        $organization->setLegalName($result['legalName']?$result['legalName']:' ');
        $organization->setInn($result['inn']?$result['inn']:' ');
        $organization->setAdressCode($result['addressCode']?$result['addressCode']:' ');
        $organization->setFirstRateCompany($result['firstRateCompany']?$result['firstRateCompany']:' ');
        $organization->setBusinessSize($result['businessSize']?$result['businessSize']:' ');
        $organization->setDeleted($result['innerInfo']?$result['innerInfo']:' ');
        $em->persist($organization);
        $em->flush();
    }

    /**@param array $result*/
    private function setRegionToDB($result){
        $em = $this->getDoctrine()->getManager();
        $region = new Regions();
        $region->setRegionId($this->trimRegionCode($result['code']));
        $region->setFederalDistrictId($result['federal_district_id']?$result['federal_district_id']:' ');
        $region->setName($result['name']?$result['name']:' ');
        $em->persist($region);
        $em->flush();
    }

    /**@param array $result*/
    private function setProfessionToDB($result){
        $em = $this->getDoctrine()->getManager();
        $profession = new Professions();
        $profession->setProfId($result['prof_id']?$result['prof_id']:' ');
        $profession->setCategory($result['category']?$result['category']:' ');
        $profession->setName($result['name']?$result['name']:' ');
        $profession->setEtks($result['etks']?$result['etks']:' ');
        $profession->setActive($result['active']?$result['active']:' ');
        $profession->setModifyDate($result['dateModify']?$this->transformDate($result['dateModify']):$this->transformDate(date('Y-m-d')));
        $profession->setDeleted($result['deleted']?$result['deleted']:' ');
        $em->persist($profession);
        $em->flush();
    }

    /**@param array $result*/
    private function setIndustryToDB($result){
        $em = $this->getDoctrine()->getManager();
        $industry = new Industries();
        $industry->setIndustryId($result['industry_id']?$result['industry_id']:' ');
        $industry->setCode($result['code']?$result['code']:' ');
        $industry->setName($result['name']?$result['name']:' ');
        $industry->setCreationDate($result['creationDate']?$this->transformDate($result['creationDate']):$this->transformDate(date('Y-m-d')));
        $industry->setActive($result['active']?$result['active']:' ');
        $industry->setModifyDate($result['dateModify']?$this->transformDate($result['dateModify']):$this->transformDate(date('Y-m-d')));
        $industry->setDeleted($result['deleted']?$result['deleted']:' ');
        $em->persist($industry);
        $em->flush();
    }

    /** @param string $code */
    private function trimRegionCode($code){
        return intval(substr($code,0,2));
    }

    /** @param string $code
     * @return \DateTime
     */
    private function transformDate ($code){
        $dateClass = new \DateTime();
        $date = $dateClass->modify($code);
        return $date;
    }
}
